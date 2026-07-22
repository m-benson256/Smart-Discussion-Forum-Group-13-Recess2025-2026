package org.example;

import java.net.http.HttpClient;
import java.net.http.HttpResponse;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.Locale;

import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Label;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class LecturerQuizzesController {

    @FXML private FlowPane quizListContainer;

    private final ObjectMapper mapper = new ObjectMapper();
    private List<Quiz> allQuizzes = List.of();
    private String currentFilter = "all";

    private static final DateTimeFormatter DUE_DATE_FORMAT =
            DateTimeFormatter.ofPattern("MMM d", Locale.ENGLISH);

    @FXML
    public void initialize() {
        loadQuizzes();
    }

    private void loadQuizzes() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(
                "http://127.0.0.1:8000/api/desktop/lecturer/quizzes")
                .GET()
                .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleResponse);
    }

    private void handleResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                if (response.statusCode() == 200) {
                    allQuizzes = List.of(mapper.readValue(response.body(), Quiz[].class));
                    renderList();
                } else {
                    System.err.println("Failed to load quizzes: " + response.statusCode());
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    @FXML void showAllQuizzes(ActionEvent e) { currentFilter = "all"; renderList(); }
    @FXML void showScheduled(ActionEvent e) { currentFilter = "scheduled"; renderList(); }
    @FXML void showPastDue(ActionEvent e) { currentFilter = "pastdue"; renderList(); }

    private void renderList() {
        quizListContainer.getChildren().clear();

        List<Quiz> filtered = allQuizzes.stream().filter(q -> {
            if ("all".equals(currentFilter)) return true;
            if (q.getStartTime() == null) return false;
            LocalDateTime start = LocalDateTime.parse(q.getStartTime().replace(" ", "T"));
            boolean isFuture = start.isAfter(LocalDateTime.now());
            return "scheduled".equals(currentFilter) ? isFuture : !isFuture;
        }).toList();

        for (Quiz quiz : filtered) {
            quizListContainer.getChildren().add(buildQuizCard(quiz));
        }
    }

    private VBox buildQuizCard(Quiz quiz) {
        VBox card = new VBox(10);
        card.getStyleClass().add("card");
        card.setPadding(new Insets(16));
        card.setPrefWidth(280);

        // Status badge pill
        boolean isPublished = "published".equals(quiz.getStatus());
        Label badge = new Label(isPublished ? "ACTIVE" : "DRAFT");
        badge.setStyle(
                "-fx-background-color: " + (isPublished ? "#dcfce7" : "#fef9c3") + ";" +
                "-fx-text-fill: " + (isPublished ? "#16a34a" : "#ca8a04") + ";" +
                "-fx-font-size: 11px; -fx-font-weight: bold; " +
                "-fx-background-radius: 12; -fx-padding: 3 10 3 10;"
        );
        badge.setMaxWidth(javafx.scene.layout.Region.USE_PREF_SIZE);

        Label title = new Label(quiz.getTitle());
        title.getStyleClass().add("card-title");
        title.setStyle("-fx-font-size: 17px;");

        String dueText = quiz.getStartTime() != null
                ? "Due " + LocalDateTime.parse(quiz.getStartTime().replace(" ", "T")).format(DUE_DATE_FORMAT)
                : "Due No due date set";
        int marks = quiz.getTotalMarks() == null ? 0 : quiz.getTotalMarks();
        Label meta = new Label(dueText + " · " + marks + " Marks");
        meta.getStyleClass().add("muted-label");

        int submissions = quiz.getSubmissionsCount() == null ? 0 : quiz.getSubmissionsCount();
        Label submissionsLabel = new Label(submissions + " Submissions");
        submissionsLabel.getStyleClass().add("muted-label");

        Label editLink = new Label("Edit Quiz");
        editLink.setStyle("-fx-text-fill: #2563eb; -fx-font-weight: bold; -fx-cursor: hand;");
        editLink.setOnMouseClicked(e -> {
            // TODO: wire to an edit flow once quiz-editing is in scope
        });

        HBox bottomRow = new HBox();
        bottomRow.getChildren().addAll(submissionsLabel, editLink);
        HBox.setHgrow(submissionsLabel, javafx.scene.layout.Priority.ALWAYS);

        card.getChildren().addAll(badge, title, meta, bottomRow);
        return card;
    }

    @FXML
    void handleCreateQuiz(ActionEvent event) {
        LecturerDashboardController.navigateTo("quiz_builder_view.fxml");
    }
}