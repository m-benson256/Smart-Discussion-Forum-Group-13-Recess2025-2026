package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.node.ObjectNode;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.scene.text.Text;
import javafx.scene.text.TextFlow;
import javafx.util.Duration;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.OffsetDateTime;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

public class QuizAttemptController {

    private static final String BASE_URL = "http://127.0.0.1:8000/api";

    @FXML private Label titleLabel;
    @FXML private HBox timerBadge;
    @FXML private Label timerLabel;
    @FXML private ProgressBar progressBar;
    @FXML private Label progressLabel;
    @FXML private VBox questionsContainer;
    @FXML private Button submitButton;

    private final ObjectMapper mapper = new ObjectMapper();

    private Long attemptId;
    private JsonNode quizData;
    private final Map<Long, String> answers = new LinkedHashMap<>();

    private Timeline timerTimeline;
    private OffsetDateTime deadlineTime;   // NEW: the actual absolute deadline, recomputed each tick
    private boolean quizSubmitted = false;
    private boolean autoSubmitTriggered = false;

    @FXML
    public void initialize() {
        loadQuiz();
    }

    private void loadQuiz() {
        Long quizId = AppState.getSelectedQuizId();
        if (quizId == null) {
            showAlertAndReturn(Alert.AlertType.ERROR, "No quiz selected.");
            return;
        }

        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    BASE_URL + "/desktop/quizzes/" + quizId + "/start")
                .POST(HttpRequest.BodyPublishers.noBody())
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleQuizLoaded)
                .exceptionally(ex -> {
                    Platform.runLater(() -> showAlertAndReturn(Alert.AlertType.ERROR, "Could not load quiz."));
                    return null;
                });

        } catch (Exception e) {
            e.printStackTrace();
            showAlertAndReturn(Alert.AlertType.ERROR, "Could not load quiz.");
        }
    }

    private void handleQuizLoaded(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode body = mapper.readTree(response.body());

                if (response.statusCode() != 200) {
                    String message = body.has("message") ? body.get("message").asText() : "Could not load quiz.";
                    showAlertAndReturn(Alert.AlertType.WARNING, message);
                    return;
                }

                attemptId = body.get("attempt_id").asLong();
                quizData = body.get("quiz");

                titleLabel.setText(quizData.get("title").asText());

                if (body.hasNonNull("deadline")) {
                    deadlineTime = OffsetDateTime.parse(body.get("deadline").asText());
                } else {
                    // NEW: fallback deadline anchored to the moment we loaded, not a plain countdown int
                    deadlineTime = OffsetDateTime.now().plusMinutes(quizData.get("duration_minutes").asInt());
                }

                long secondsLeft = java.time.Duration.between(OffsetDateTime.now(), deadlineTime).getSeconds();
                if (secondsLeft <= 0) {
                    autoSubmitTriggered = true;
                    submitQuizInternal();
                    return;
                }

                startTimer();
                renderQuestions();

            } catch (Exception e) {
                e.printStackTrace();
                showAlertAndReturn(Alert.AlertType.ERROR, "Could not process server response.");
            }
        });
    }

    // ------------------------------------------------------------------
    // Timer — NEW: recomputes remaining time from the real deadline every
    // tick (instead of decrementing a counter), so there's no drift, and
    // it submits IMMEDIATELY at zero instead of waiting on a popup first.
    // ------------------------------------------------------------------

    private void startTimer() {
        updateTimerDisplay();
        timerTimeline = new Timeline(new KeyFrame(Duration.seconds(1), e -> {
            long secondsLeft = java.time.Duration.between(OffsetDateTime.now(), deadlineTime).getSeconds();

            if (secondsLeft <= 0) {
                timerBadge.getStyleClass().add("quiz-timer-badge-warning");
                timerLabel.setText("Time Remaining: 0:00");
                timerTimeline.stop();
                autoSubmitTriggered = true;
                submitQuizInternal();   // submit immediately, no blocking popup first
                return;
            }
            updateTimerDisplay((int) secondsLeft);
        }));
        timerTimeline.setCycleCount(Timeline.INDEFINITE);
        timerTimeline.play();
    }

    private void updateTimerDisplay() {
        long secondsLeft = java.time.Duration.between(OffsetDateTime.now(), deadlineTime).getSeconds();
        updateTimerDisplay((int) Math.max(secondsLeft, 0));
    }

    private void updateTimerDisplay(int secondsLeft) {
        int minutes = secondsLeft / 60;
        int seconds = secondsLeft % 60;
        timerLabel.setText(String.format("Time Remaining: %d:%02d", minutes, seconds));

        timerBadge.getStyleClass().remove("quiz-timer-badge-warning");
        if (secondsLeft <= 60) {
            timerBadge.getStyleClass().add("quiz-timer-badge-warning");
        }
    }

    // ------------------------------------------------------------------
    // Rendering questions
    // ------------------------------------------------------------------

    private void renderQuestions() {
        questionsContainer.getChildren().clear();
        JsonNode questions = quizData.get("questions");

        int idx = 0;
        for (JsonNode question : questions) {
            questionsContainer.getChildren().add(buildQuestionCard(question, idx));
            idx++;
        }
    }

    private VBox buildQuestionCard(JsonNode question, int idx) {
        long questionId = question.get("id").asLong();
        String type = question.get("type").asText();
        String prompt = question.get("prompt").asText();

        VBox card = new VBox(16);
        card.getStyleClass().add("quiz-question-card");
        card.setPadding(new Insets(24));

        Label numberBadge = new Label(String.format("QUESTION %02d", idx + 1));
        numberBadge.getStyleClass().add("category-badge");

        Text promptText = new Text(prompt);
        promptText.getStyleClass().add("quiz-prompt-text");
        TextFlow promptFlow = new TextFlow(promptText);

        card.getChildren().addAll(numberBadge, promptFlow, buildAnswerArea(question, questionId, type));
        return card;
    }

    private javafx.scene.Node buildAnswerArea(JsonNode question, long questionId, String type) {
        switch (type) {
            case "mcq": {
                VBox optionsBox = new VBox(10);
                ToggleGroup group = new ToggleGroup();

                for (JsonNode opt : question.get("options")) {
                    String optionKey = opt.get("option_key").asText();
                    String optionText = opt.get("option_text").asText();

                    RadioButton radio = new RadioButton(optionText);
                    radio.setToggleGroup(group);
                    radio.getStyleClass().add("quiz-option-label");
                    radio.setMaxWidth(Double.MAX_VALUE);
                    radio.setOnAction(e -> setAnswer(questionId, optionKey));

                    optionsBox.getChildren().add(radio);
                }
                return optionsBox;
            }
            case "tf": {
                HBox tfBox = new HBox(16);
                ToggleGroup group = new ToggleGroup();

                ToggleButton trueBtn = new ToggleButton("True");
                ToggleButton falseBtn = new ToggleButton("False");

                for (ToggleButton btn : List.of(trueBtn, falseBtn)) {
                    btn.setToggleGroup(group);
                    btn.getStyleClass().add("quiz-tf-button");
                    btn.setMaxWidth(Double.MAX_VALUE);
                    HBox.setHgrow(btn, javafx.scene.layout.Priority.ALWAYS);
                }
                trueBtn.setOnAction(e -> setAnswer(questionId, "True"));
                falseBtn.setOnAction(e -> setAnswer(questionId, "False"));

                tfBox.getChildren().addAll(trueBtn, falseBtn);
                return tfBox;
            }
            default: {
                TextArea textArea = new TextArea();
                textArea.setPromptText("Type your answer here...");
                textArea.setPrefRowCount(6);
                textArea.setWrapText(true);
                textArea.getStyleClass().add("quiz-textarea");
                textArea.textProperty().addListener((obs, oldVal, newVal) -> setAnswer(questionId, newVal));
                return textArea;
            }
        }
    }

    private void setAnswer(long questionId, String value) {
        answers.put(questionId, value);
        updateProgress();
        autosaveAnswer(questionId, value);
    }

    private void updateProgress() {
        int total = quizData.get("questions").size();
        int answered = answers.size();
        double percent = total > 0 ? (double) answered / total : 0;

        progressBar.setProgress(percent);
        progressLabel.setText(Math.round(percent * 100) + "% Complete");
    }

    private void autosaveAnswer(long questionId, String value) {
        try {
            ObjectNode payload = mapper.createObjectNode();
            payload.put("question_id", questionId);
            payload.put("selected_answer", value);

            HttpRequest request = Session.authorizedRequestBuilder(
                    BASE_URL + "/desktop/attempts/" + attemptId + "/answer")
                .POST(HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload)))
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .exceptionally(ex -> {
                    System.err.println("Autosave failed: " + ex.getMessage());
                    return null;
                });
        } catch (Exception e) {
            System.err.println("Autosave failed: " + e.getMessage());
        }
    }

    // ------------------------------------------------------------------
    // Submission
    // ------------------------------------------------------------------

    @FXML
    void handleSubmitQuiz(ActionEvent event) {
        submitQuizInternal();
    }

    private void submitQuizInternal() {
        if (quizSubmitted) return;
        quizSubmitted = true;

        if (timerTimeline != null) {
            timerTimeline.stop();
        }
        submitButton.setDisable(true);

        try {
            List<Map<String, Object>> answerList = new ArrayList<>();
            for (Map.Entry<Long, String> entry : answers.entrySet()) {
                Map<String, Object> a = new LinkedHashMap<>();
                a.put("question_id", entry.getKey());
                a.put("selected_answer", entry.getValue());
                answerList.add(a);
            }

            Map<String, Object> payload = new LinkedHashMap<>();
            payload.put("answers", answerList);

            HttpRequest request = Session.authorizedRequestBuilder(
                    BASE_URL + "/desktop/attempts/" + attemptId + "/submit")
                .POST(HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload)))
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleSubmitResponse)
                .exceptionally(ex -> {
                    Platform.runLater(() -> {
                        quizSubmitted = false;
                        submitButton.setDisable(false);
                        new Alert(Alert.AlertType.ERROR, "Could not submit quiz. Please try again.", ButtonType.OK).showAndWait();
                    });
                    return null;
                });

        } catch (Exception e) {
            quizSubmitted = false;
            submitButton.setDisable(false);
            e.printStackTrace();
        }
    }

    private void handleSubmitResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode body = mapper.readTree(response.body());

                if (response.statusCode() < 200 || response.statusCode() >= 300) {
                    quizSubmitted = false;
                    submitButton.setDisable(false);
                    String message = body.has("message") ? body.get("message").asText() : "Could not submit quiz.";
                    new Alert(Alert.AlertType.WARNING, message, ButtonType.OK).showAndWait();
                    return;
                }

                String prefix = autoSubmitTriggered ? "Time is up. " : "";
                String message = String.format("%sQuiz submitted! Score: %s/%s (%s/%s correct)",
                    prefix,
                    body.get("score").asText(),
                    body.get("total_marks").asText(),
                    body.get("correct_count").asText(),
                    body.get("total_questions").asText());

                new Alert(Alert.AlertType.INFORMATION, message, ButtonType.OK).showAndWait();
                StudentDashboardController.navigateTo("quizzes_view.fxml");

            } catch (Exception e) {
                e.printStackTrace();
                new Alert(Alert.AlertType.ERROR, "Could not process server response.", ButtonType.OK).showAndWait();
            }
        });
    }

    private void showAlertAndReturn(Alert.AlertType type, String message) {
        new Alert(type, message, ButtonType.OK).showAndWait();
        StudentDashboardController.navigateTo("quizzes_view.fxml");
    }
}