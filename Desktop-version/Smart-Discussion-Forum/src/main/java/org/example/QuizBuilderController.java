package org.example;

import java.net.http.HttpClient;
import java.net.http.HttpResponse;
import java.util.ArrayList;
import java.util.List;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.control.RadioButton;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.scene.control.TextInputDialog;
import javafx.scene.control.Toggle;
import javafx.scene.control.ToggleGroup;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class QuizBuilderController {

    // Configure tab
    @FXML private TextField titleField;
    @FXML private TextArea descField;
    @FXML private ComboBox<Category> categoryCombo;
    @FXML private TextField startTimeField;
    @FXML private TextField timeLimitField;
    @FXML private TextField totalMarksField;
    @FXML private TextField passingScoreField;
    @FXML private CheckBox shuffleCheckBox;

    // Questions tab
    @FXML private VBox questionListContainer;
    @FXML private Label editorTitleLabel;
    @FXML private ComboBox<String> questionTypeCombo;
    @FXML private TextArea questionPromptArea;
    @FXML private VBox mcqBox;
    @FXML private RadioButton mcqRadioA, mcqRadioB, mcqRadioC, mcqRadioD;
    @FXML private TextField mcqTextA, mcqTextB, mcqTextC, mcqTextD;
    @FXML private HBox tfBox;
    @FXML private RadioButton tfTrueRadio, tfFalseRadio;
    @FXML private VBox saBox;
    @FXML private TextField saAnswerField;

    // Review tab
    @FXML private Label summaryTitleLabel;
    @FXML private Label summaryDescLabel;
    @FXML private Label summaryCountLabel;
    @FXML private VBox reviewQuestionsContainer;

    // Panes
    @FXML private VBox configurePane;
    @FXML private HBox questionsPane;
    @FXML private VBox reviewPane;
    @FXML private Button navConfigure, navQuestions, navReview;
    @FXML private Button finishButton;

    private final ObjectMapper mapper = new ObjectMapper();
    private final ToggleGroup mcqGroup = new ToggleGroup();
    private final ToggleGroup tfGroup = new ToggleGroup();

    private Long quizId = null;
    private final List<Question> questions = new ArrayList<>();
    private final List<Category> categories = new ArrayList<>();
    private int currentEditingIndex = -1;

    private static final String BASE_URL = "http://127.0.0.1:8000/api";

    @FXML
    public void initialize() {
        mcqRadioA.setToggleGroup(mcqGroup);
        mcqRadioB.setToggleGroup(mcqGroup);
        mcqRadioC.setToggleGroup(mcqGroup);
        mcqRadioD.setToggleGroup(mcqGroup);
        tfTrueRadio.setToggleGroup(tfGroup);
        tfFalseRadio.setToggleGroup(tfGroup);

        questionTypeCombo.getItems().addAll("mcq", "tf", "sa");
        questionTypeCombo.setValue("mcq");
        questionTypeCombo.setOnAction(e -> toggleAnswerFields());
        toggleAnswerFields();

        loadCategories();
    }

    private void loadCategories() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/categories")
                .GET()
                .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            var loaded = List.of(mapper.readValue(response.body(), Category[].class));
                            categories.addAll(loaded);
                            categoryCombo.getItems().setAll(loaded);
                        } else {
                            showAlert("Could not load categories (status " + response.statusCode() + ")");
                        }
                    } catch (Exception ex) {
                        ex.printStackTrace();
                    }
                })
        );
    }

    // ---------- Tab switching ----------

    @FXML
    void showConfigureTab(ActionEvent e) {
        setActivePane(configurePane, null, null);
    }

    @FXML
    void showQuestionsTab(ActionEvent e) {
        if (quizId == null) {
            ensureQuizCreated(() -> setActivePane(null, questionsPane, null));
        } else {
            setActivePane(null, questionsPane, null);
        }
    }

    @FXML
    void showReviewTab(ActionEvent e) {
        setActivePane(null, null, reviewPane);
        renderReviewList();
    }

    private void setActivePane(VBox configure, HBox questionsP, VBox review) {
        configurePane.setVisible(configure != null); configurePane.setManaged(configure != null);
        questionsPane.setVisible(questionsP != null); questionsPane.setManaged(questionsP != null);
        reviewPane.setVisible(review != null); reviewPane.setManaged(review != null);
    }

    // ---------- Quiz creation (lazy, mirrors create.blade.php) ----------

    private void ensureQuizCreated(Runnable onDone) {
        if (quizId != null) { onDone.run(); return; }

        if (categoryCombo.getValue() == null) {
            showAlert("Please select a category before continuing.");
            return;
        }

        try {
            Category selected = categoryCombo.getValue();

            var payload = mapper.createObjectNode();
            payload.put("title", titleField.getText().isBlank() ? "Untitled Quiz" : titleField.getText());
            payload.put("description", descField.getText());
            payload.put("category_id", selected.getCategoryId());
            payload.put("start_time", startTimeField.getText().isBlank() ? null : startTimeField.getText());
            payload.put("duration_minutes", parseIntOrDefault(timeLimitField.getText(), 60));
            payload.put("total_marks", parseIntOrDefault(totalMarksField.getText(), 100));
            payload.put("passing_score", parseIntOrDefault(passingScoreField.getText(), 70));
            payload.put("shuffle_questions", shuffleCheckBox.isSelected());

            HttpClient client = HttpClient.newHttpClient();
            var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/quizzes")
                    .POST(java.net.http.HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload)))
                    .build();

            client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                    Platform.runLater(() -> {
                        try {
                            if (response.statusCode() == 201) {
                                JsonNode body = mapper.readTree(response.body());
                                quizId = body.get("id").asLong();
                                onDone.run();
                            } else {
                                showAlert("Could not save quiz configuration (status " + response.statusCode() + ")");
                            }
                        } catch (Exception ex) {
                            ex.printStackTrace();
                        }
                    })
            );
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    // ---------- Add Questions ----------

    private void toggleAnswerFields() {
        String type = questionTypeCombo.getValue();
        mcqBox.setVisible("mcq".equals(type)); mcqBox.setManaged("mcq".equals(type));
        tfBox.setVisible("tf".equals(type)); tfBox.setManaged("tf".equals(type));
        saBox.setVisible("sa".equals(type)); saBox.setManaged("sa".equals(type));
    }

    @FXML
    void resetEditor() {
        currentEditingIndex = -1;
        editorTitleLabel.setText("Adding New Question");
        questionPromptArea.clear();
        questionTypeCombo.setValue("mcq");
        mcqTextA.clear(); mcqTextB.clear(); mcqTextC.clear(); mcqTextD.clear();
        mcqGroup.selectToggle(null);
        tfGroup.selectToggle(null);
        saAnswerField.clear();
        toggleAnswerFields();
    }

    @FXML
    void saveQuestion() {
        String prompt = questionPromptArea.getText();
        if (prompt == null || prompt.isBlank()) { showAlert("Please enter a question prompt."); return; }

        String type = questionTypeCombo.getValue();
        String correctAnswer;
        List<QuestionOption> options = null;

        if ("mcq".equals(type)) {
            Toggle selected = mcqGroup.getSelectedToggle();
            if (selected == null) { showAlert("Please select a grading key."); return; }
            correctAnswer = (String) selected.getUserData();

            options = List.of(
                    new QuestionOption("A", mcqTextA.getText()),
                    new QuestionOption("B", mcqTextB.getText()),
                    new QuestionOption("C", mcqTextC.getText()),
                    new QuestionOption("D", mcqTextD.getText())
            );
            for (QuestionOption o : options) {
                if (o.getOptionText() == null || o.getOptionText().isBlank()) {
                    showAlert("Please fill in text for all four options.");
                    return;
                }
            }
        } else if ("tf".equals(type)) {
            Toggle selected = tfGroup.getSelectedToggle();
            if (selected == null) { showAlert("Please select True or False."); return; }
            correctAnswer = selected == tfTrueRadio ? "True" : "False";
        } else {
            correctAnswer = saAnswerField.getText();
            if (correctAnswer == null || correctAnswer.isBlank()) { showAlert("Please enter the correct keyword."); return; }
        }

        if (quizId == null) { showAlert("Quiz isn't saved yet — go back to Configure Quiz first."); return; }

        Question q = new Question();
        q.setType(type);
        q.setPrompt(prompt);
        q.setCorrectAnswer(correctAnswer);
        q.setOptions(options);

        submitQuestion(q);
    }

    private void submitQuestion(Question q) {
        try {
            var payload = mapper.createObjectNode();
            payload.put("type", q.getType());
            payload.put("prompt", q.getPrompt());
            payload.put("correct_answer", q.getCorrectAnswer());
            if (q.getOptions() != null) {
                payload.set("options", mapper.valueToTree(q.getOptions()));
            }

            boolean editing = currentEditingIndex > -1 && questions.get(currentEditingIndex).getId() != null;
            String url = editing
                    ? BASE_URL + "/desktop/questions/" + questions.get(currentEditingIndex).getId()
                    : BASE_URL + "/desktop/quizzes/" + quizId + "/questions";

            var requestBuilder = Session.authorizedRequestBuilder(url);
            var request = editing
                    ? requestBuilder.PUT(java.net.http.HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload))).build()
                    : requestBuilder.POST(java.net.http.HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload))).build();

            HttpClient client = HttpClient.newHttpClient();
            client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                    Platform.runLater(() -> {
                        try {
                            if (response.statusCode() == 201 || response.statusCode() == 200) {
                                Question saved = mapper.readValue(response.body(), Question.class);
                                if (editing) {
                                    questions.set(currentEditingIndex, saved);
                                } else {
                                    questions.add(saved);
                                }
                                renderQuestionList();
                                resetEditor();
                            } else {
                                showAlert("Could not save question (status " + response.statusCode() + ")");
                            }
                        } catch (Exception ex) {
                            ex.printStackTrace();
                        }
                    })
            );
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void renderQuestionList() {
        questionListContainer.getChildren().clear();
        for (int i = 0; i < questions.size(); i++) {
            int idx = i;
            Question q = questions.get(i);
            Label item = new Label("Q" + (i + 1) + ": " + truncate(q.getPrompt(), 30));
            item.getStyleClass().add("card");
            item.setPadding(new javafx.geometry.Insets(8));
            item.setOnMouseClicked(e -> editQuestion(idx));
            questionListContainer.getChildren().add(item);
        }
    }

    private void editQuestion(int idx) {
        currentEditingIndex = idx;
        Question q = questions.get(idx);
        editorTitleLabel.setText("Editing Question " + (idx + 1));
        questionPromptArea.setText(q.getPrompt());
        questionTypeCombo.setValue(q.getType());
        toggleAnswerFields();

        if ("mcq".equals(q.getType()) && q.getOptions() != null) {
            for (QuestionOption o : q.getOptions()) {
                switch (o.getOptionKey()) {
                    case "A" -> mcqTextA.setText(o.getOptionText());
                    case "B" -> mcqTextB.setText(o.getOptionText());
                    case "C" -> mcqTextC.setText(o.getOptionText());
                    case "D" -> mcqTextD.setText(o.getOptionText());
                }
            }
            switch (q.getCorrectAnswer()) {
                case "A" -> mcqGroup.selectToggle(mcqRadioA);
                case "B" -> mcqGroup.selectToggle(mcqRadioB);
                case "C" -> mcqGroup.selectToggle(mcqRadioC);
                case "D" -> mcqGroup.selectToggle(mcqRadioD);
            }
        } else if ("tf".equals(q.getType())) {
            tfGroup.selectToggle("True".equals(q.getCorrectAnswer()) ? tfTrueRadio : tfFalseRadio);
        } else {
            saAnswerField.setText(q.getCorrectAnswer());
        }
    }

    // ---------- Review & Publish ----------

    private void renderReviewList() {
        summaryTitleLabel.setText(titleField.getText().isBlank() ? "Untitled Quiz" : titleField.getText());
        summaryDescLabel.setText(descField.getText().isBlank() ? "No description provided." : descField.getText());
        summaryCountLabel.setText(questions.size() + " Questions");

        reviewQuestionsContainer.getChildren().clear();
        for (int i = 0; i < questions.size(); i++) {
            Question q = questions.get(i);
            Label item = new Label("Q" + (i + 1) + " [" + q.getType() + "]: " + q.getPrompt());
            item.getStyleClass().add("card");
            item.setPadding(new javafx.geometry.Insets(8));
            item.setWrapText(true);
            reviewQuestionsContainer.getChildren().add(item);
        }
    }

    @FXML
    void handleFinishAndSave(ActionEvent event) {
        finishButton.setDisable(true);
        String originalText = finishButton.getText();
        finishButton.setText("Saving...");

        ensureQuizCreated(() -> {
            if (questions.isEmpty()) {
                showAlert("Quiz saved as a draft. Add questions to publish it later.");
                finishButton.setDisable(false);
                finishButton.setText(originalText);
                return;
            }

            finishButton.setText("Publishing...");
            publishQuiz(originalText);
        });
    }

    private void publishQuiz(String originalButtonText) {
    try {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/quizzes/" + quizId + "/publish")
                .POST(java.net.http.HttpRequest.BodyPublishers.noBody())
                .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    finishButton.setDisable(false);
                    finishButton.setText(originalButtonText);

                    if (response.statusCode() == 200) {
                        promptForAnnouncement();
                    } else {
                        try {
                            JsonNode body = mapper.readTree(response.body());
                            showAlert(body.has("message") ? body.get("message").asText() : "Could not publish quiz.");
                        } catch (Exception ex) {
                            showAlert("Could not publish quiz.");
                        }
                    }
                })
        );
    } catch (Exception ex) {
        ex.printStackTrace();
    }
}

private void promptForAnnouncement() {
    String quizTitle = titleField.getText().isBlank() ? "Untitled Quiz" : titleField.getText();
    String defaultMessage = "A new quiz \"" + quizTitle + "\" has been posted. Duration: "
            + timeLimitField.getText() + " minutes.";

    TextInputDialog dialog = new TextInputDialog(defaultMessage);
    dialog.setTitle("Publish Announcement");
    dialog.setHeaderText("Quiz \"" + quizTitle + "\" is published! Write an announcement for your students:");
    dialog.setContentText("Message:");

    dialog.showAndWait().ifPresent(message -> {
        if (!message.isBlank()) {
            postAnnouncement(message.trim());
        } else {
            LecturerDashboardController.navigateTo("lecturer_overview_view.fxml");
        }
    });
}

private void postAnnouncement(String message) {
    try {
        var payload = mapper.createObjectNode();
        payload.put("content", message);

        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/quizzes/" + quizId + "/announce")
                .POST(java.net.http.HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload)))
                .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    if (response.statusCode() != 201) {
                        showAlert("Quiz published, but the announcement could not be posted.");
                    }
                    LecturerDashboardController.navigateTo("lecturer_overview_view.fxml");
                })
        );
    } catch (Exception ex) {
        ex.printStackTrace();
    }
}

    // ---------- Helpers ----------

    private int parseIntOrDefault(String text, int fallback) {
        try { return Integer.parseInt(text.trim()); } catch (Exception e) { return fallback; }
    }

    private String truncate(String s, int max) {
        if (s == null) return "";
        return s.length() > max ? s.substring(0, max) + "..." : s;
    }

    private void showAlert(String message) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION, message);
        alert.showAndWait();
    }
}