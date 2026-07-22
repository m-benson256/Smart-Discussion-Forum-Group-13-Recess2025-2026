package org.example;

import java.net.http.HttpClient;
import java.net.http.HttpResponse;
import java.time.LocalDateTime;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;

public class LecturerOverviewController {

    @FXML private Label welcomeLabelOverview;
    @FXML private Label totalStudentsLabel;
    @FXML private Label topicsLabel;
    @FXML private VBox quizListContainer;

    private final ObjectMapper mapper = new ObjectMapper();
    private List<Quiz> allQuizzes = List.of();
    private String currentFilter = "all"; // all | scheduled | pastdue

    @FXML
public void initialize() {
    welcomeLabelOverview.setText("Welcome Lecturer, " + Session.getUserName());
    loadQuizzes();
    loadDashboardStats();
}

private void loadDashboardStats() {
    HttpClient client = HttpClient.newHttpClient();
    var request = Session.authorizedRequestBuilder(
            "http://127.0.0.1:8000/api/desktop/lecturer/dashboard-stats")
            .GET()
            .build();

    client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
            .thenAccept(this::handleStatsResponse);
}

private void handleStatsResponse(HttpResponse<String> response) {
    Platform.runLater(() -> {
        try {
            if (response.statusCode() == 200) {
                var node = mapper.readTree(response.body());
                totalStudentsLabel.setText(String.valueOf(node.get("total_students").asInt()));
                topicsLabel.setText(String.valueOf(node.get("topics").asInt()));
            } else {
                System.err.println("Failed to load dashboard stats: " + response.statusCode());
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    });
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
            if (q.getStartTime() == null) return "all".equals(currentFilter);
            LocalDateTime start = LocalDateTime.parse(q.getStartTime().replace(" ", "T"));
            boolean isFuture = start.isAfter(LocalDateTime.now());
            return "scheduled".equals(currentFilter) ? isFuture : !isFuture;
        }).toList();

        for (Quiz quiz : filtered) {
            quizListContainer.getChildren().add(buildQuizCard(quiz));
        }
    }

    private VBox buildQuizCard(Quiz quiz) {
        VBox card = new VBox(4);
        card.getStyleClass().add("card");
        card.setPadding(new Insets(16));

        Label title = new Label(quiz.getTitle());
        title.getStyleClass().add("card-title");
        title.setStyle("-fx-font-size: 16px;");

        String statusOrDate = "published".equals(quiz.getStatus())
                ? (quiz.getStartTime() != null ? quiz.getStartTime() : "published")
                : "Draft";

        int questions = quiz.getQuestionsCount() == null ? 0 : quiz.getQuestionsCount();
        int minutes = quiz.getDurationMinutes() == null ? 0 : quiz.getDurationMinutes();

        Label meta = new Label(statusOrDate + " · " + questions + " Questions · " + minutes + " Mins");
        meta.getStyleClass().add("muted-label");

        card.getChildren().addAll(title, meta);
        return card;
    }

    @FXML
    void handleCreateQuiz(ActionEvent event) {
        LecturerDashboardController.navigateTo("quiz_builder_view.fxml");
    }
    
    
}