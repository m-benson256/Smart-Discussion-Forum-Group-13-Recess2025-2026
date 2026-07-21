package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Priority;
import javafx.scene.layout.Region;
import javafx.scene.layout.StackPane;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class PerformanceController {

    @FXML private HBox statsRow;
    @FXML private VBox historyContainer;

    private final ObjectMapper mapper = new ObjectMapper();
    private JsonNode perfStats;
    private int submittedCount = 0;

    @FXML
    public void initialize() {
        fetchQuizHistory();
        fetchPerformanceStats();
    }

    private void fetchQuizHistory() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/student/quizzes")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderHistory);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderHistory(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode quizzes = mapper.readTree(response.body());
                historyContainer.getChildren().clear();
                submittedCount = 0;

                boolean any = false;
                for (JsonNode quiz : quizzes) {
                    boolean actuallySubmitted = (quiz.has("submitted_at") && !quiz.get("submitted_at").isNull())
                        || (quiz.has("score") && !quiz.get("score").isNull());
                    if (!actuallySubmitted) continue;

                    any = true;
                    submittedCount++;
                    historyContainer.getChildren().add(buildHistoryRow(quiz));
                }

                if (!any) {
                    Label empty = new Label("No submitted quizzes yet.");
                    empty.getStyleClass().add("muted-label");
                    historyContainer.getChildren().add(empty);
                }

                renderStatsCards(); // "Quizzes Completed" depends on this count, so refresh cards here too

            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private VBox buildHistoryRow(JsonNode quiz) {
        String title = quiz.get("title").asText();
        String category = quiz.has("description") && !quiz.get("description").isNull()
            ? quiz.get("description").asText() : "";
        String dueDate = quiz.has("start_time") && !quiz.get("start_time").isNull()
            ? quiz.get("start_time").asText() : "";

        int scoreValue = quiz.has("score") && !quiz.get("score").isNull() ? quiz.get("score").asInt() : 0;
        int totalMarks = quiz.has("total_marks") && !quiz.get("total_marks").isNull() ? quiz.get("total_marks").asInt() : 0;
        String scoreLabel = totalMarks > 0 ? scoreValue + "/" + totalMarks : "—";
        double percent = totalMarks > 0 ? (scoreValue * 100.0 / totalMarks) : 0; // CHANGED — real percentage, see note above

        Label titleLabel = new Label(title);
        titleLabel.getStyleClass().add("card-title");

        Label metaLabel = new Label(category + " • Completed on " + dueDate);
        metaLabel.getStyleClass().add("muted-label");

        VBox left = new VBox(4, titleLabel, metaLabel);

        Label scoreLabelNode = new Label(scoreLabel);
        scoreLabelNode.setStyle("-fx-font-size: 18px; -fx-font-weight: bold; -fx-text-fill: #1e293b;");

        Region barBg = new Region();
        barBg.setPrefSize(96, 6);
        barBg.setStyle("-fx-background-color: #f1f5f9; -fx-background-radius: 4;");

        Region barFill = new Region();
        barFill.setPrefSize(96 * (percent / 100.0), 6);
        barFill.setStyle("-fx-background-color: #22c55e; -fx-background-radius: 4;");

        StackPane barStack = new StackPane(barBg, barFill);
        barStack.setAlignment(Pos.CENTER_LEFT);

        VBox right = new VBox(6, scoreLabelNode, barStack);
        right.setAlignment(Pos.CENTER_RIGHT);

        HBox row = new HBox(left, right);
        row.setAlignment(Pos.CENTER_LEFT);
        row.setSpacing(20);
        row.setPadding(new Insets(16));
        HBox.setHgrow(left, Priority.ALWAYS);
        row.getStyleClass().add("history-row");

        return new VBox(row);
    }

    private void fetchPerformanceStats() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/student/performance-stats")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleStatsResponse);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void handleStatsResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                perfStats = mapper.readTree(response.body());
                renderStatsCards();
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private void renderStatsCards() {
        statsRow.getChildren().clear();

        String avgScore = (perfStats != null && perfStats.has("average_score") && !perfStats.get("average_score").isNull())
            ? perfStats.get("average_score").asText() + "%" : "—";

        String rank = (perfStats != null && perfStats.has("global_rank") && !perfStats.get("global_rank").isNull())
            ? "#" + perfStats.get("global_rank").asText() + " of " + perfStats.get("total_ranked_students").asText()
            : "—";

        statsRow.getChildren().addAll(
            buildStatCard("Average Score", avgScore, true),
            buildStatCard("Quizzes Completed", String.valueOf(submittedCount), false),
            buildStatCard("Global Rank", rank, false)
        );
    }

    private VBox buildStatCard(String label, String value, boolean highlight) {
        Label labelNode = new Label(label);
        labelNode.getStyleClass().add("muted-label");

        Label valueNode = new Label(value);
        valueNode.setStyle("-fx-font-size: 26px; -fx-font-weight: bold; -fx-text-fill: "
            + (highlight ? "#2563eb;" : "#1e293b;"));

        VBox card = new VBox(6, labelNode, valueNode);
        card.getStyleClass().add("card");
        card.setPadding(new Insets(18));
        card.setPrefWidth(220);

        return card;
    }
}
