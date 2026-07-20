package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.VBox;

import javafx.scene.control.TableCell;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.OffsetDateTime;
import java.time.format.DateTimeFormatter;

public class QuizzesController {

    @FXML private FlowPane incomingContainer;
    @FXML private TableView<QuizRow> missedTable;
    @FXML private TableColumn<QuizRow, String> missedTitleCol;
    @FXML private TableColumn<QuizRow, String> missedCategoryCol;
    @FXML private TableColumn<QuizRow, String> missedDueCol;
    @FXML private TableView<QuizRow> submittedTable;
    @FXML private TableColumn<QuizRow, String> submittedTitleCol;
    @FXML private TableColumn<QuizRow, String> submittedCategoryCol;
    @FXML private TableColumn<QuizRow, String> submittedDateCol;
    @FXML private TableColumn<QuizRow, String> submittedScoreCol;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        missedTitleCol.setCellValueFactory(new PropertyValueFactory<>("title"));
        missedCategoryCol.setCellValueFactory(new PropertyValueFactory<>("category"));
        missedDueCol.setCellValueFactory(new PropertyValueFactory<>("detail"));

        submittedTitleCol.setCellValueFactory(new PropertyValueFactory<>("title"));
        submittedCategoryCol.setCellValueFactory(new PropertyValueFactory<>("category"));
        submittedDateCol.setCellValueFactory(new PropertyValueFactory<>("detail"));
        submittedScoreCol.setCellValueFactory(new PropertyValueFactory<>("score"));

        // NEW: color "Was Due" red, like the web's text-red-500
        missedDueCol.setCellFactory(col -> new TableCell<>() {
            @Override
            protected void updateItem(String item, boolean empty) {
                super.updateItem(item, empty);
                setText(empty ? null : item);
                getStyleClass().remove("text-danger");
                if (!empty) getStyleClass().add("text-danger");
            }
        });

// NEW: color Score green/bold, like the web's text-green-600
        submittedScoreCol.setCellFactory(col -> new TableCell<>() {
            @Override
            protected void updateItem(String item, boolean empty) {
                super.updateItem(item, empty);
                setText(empty ? null : item);
                getStyleClass().remove("text-success");
                if (!empty) getStyleClass().add("text-success");
            }
        });

// NEW: nicer empty-state text than the default "No content in table"
        missedTable.setPlaceholder(new Label("No due quizzes."));
        submittedTable.setPlaceholder(new Label("No quizzes submitted yet."));

        fetchQuizzes();
    }

    private void fetchQuizzes() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/student/quizzes")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderQuizzes);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderQuizzes(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode quizzes = mapper.readTree(response.body());

                incomingContainer.getChildren().clear();
                ObservableList<QuizRow> missedRows = FXCollections.observableArrayList();
                ObservableList<QuizRow> submittedRows = FXCollections.observableArrayList();

                for (JsonNode quiz : quizzes) {
                    String status = quiz.get("status").asText();
                    String title = quiz.get("title").asText();
                    String category = quiz.has("description") && !quiz.get("description").isNull()
                        ? quiz.get("description").asText() : "";

                    switch (status) {
                        case "incoming":
                            incomingContainer.getChildren().add(buildIncomingCard(quiz, title, category));
                            break;
                        case "missed":
                            String due = formatDate(quiz.has("start_time") ? quiz.get("start_time").asText() : null, "Not scheduled");
                            missedRows.add(new QuizRow(title, category, due, null));
                            break;
                        case "submitted":
                            String submittedDate = formatDate(quiz.has("submitted_at") ? quiz.get("submitted_at").asText() : null, "—");
                            String score = (quiz.has("score") && !quiz.get("score").isNull())
                                ? quiz.get("score").asText() + "/" + quiz.get("total_marks").asText()
                                : "—";
                            submittedRows.add(new QuizRow(title, category, submittedDate, score));
                            break;
                    }
                }



                missedTable.setItems(missedRows);
                submittedTable.setItems(submittedRows);

            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private VBox buildIncomingCard(JsonNode quiz, String title, String category) {
        VBox card = new VBox(8);
        card.getStyleClass().add("card");
        card.setPadding(new Insets(16));
        card.setPrefWidth(260);

        Label categoryLabel = new Label(category);
        categoryLabel.getStyleClass().add("muted-label");

        Label titleLabel = new Label(title);
        titleLabel.getStyleClass().add("card-title");

        String duration = quiz.has("duration_minutes") ? quiz.get("duration_minutes").asText() + " mins" : "";
        Label durationLabel = new Label(duration);
        durationLabel.getStyleClass().add("muted-label");

        Button startButton = new Button("Start Quiz");
        startButton.getStyleClass().add("button-primary");
        startButton.setMaxWidth(Double.MAX_VALUE);

        long quizId = quiz.get("id").asLong();
        startButton.setOnAction(e -> startQuiz(quizId));

        card.getChildren().addAll(categoryLabel, titleLabel, durationLabel, startButton);
        return card;
    }

    // Calls the real /start endpoint, then hands off to a placeholder screen —
    // the actual question-by-question quiz UI is its own build step, see note above
    private void startQuiz(long quizId) {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/quizzes/" + quizId + "/start")
                .POST(HttpRequest.BodyPublishers.noBody())
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> Platform.runLater(() -> {
                    AppState.setSelectedQuizId(quizId);
                    StudentDashboardController.navigateTo("quiz_attempt_view.fxml");
                }));

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private String formatDate(String isoTimestamp, String fallback) {
        if (isoTimestamp == null) return fallback;
        try {
            OffsetDateTime dt = OffsetDateTime.parse(isoTimestamp);
            return dt.atZoneSameInstant(java.time.ZoneId.systemDefault())
                .format(DateTimeFormatter.ofPattern("MMM d, yyyy"));
        } catch (Exception e) {
            return isoTimestamp;
        }
    }
}
