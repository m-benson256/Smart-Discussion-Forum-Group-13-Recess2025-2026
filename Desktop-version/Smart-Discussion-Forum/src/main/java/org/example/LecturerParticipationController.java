package org.example;

import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.beans.property.SimpleStringProperty;
import javafx.fxml.FXML;
import javafx.scene.control.Alert;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;

public class LecturerParticipationController {

    @FXML private TextField pointsPerMessageField;
    @FXML private TextField pointsPerReactionField;
    @FXML private TextField maxScoreField;

    @FXML private TableView<ScoreRow> scoresTable;
    @FXML private TableColumn<ScoreRow, String> studentColumn;
    @FXML private TableColumn<ScoreRow, String> messagesColumn;
    @FXML private TableColumn<ScoreRow, String> reactionsColumn;
    @FXML private TableColumn<ScoreRow, String> scoreColumn;

    private final ObjectMapper mapper = new ObjectMapper();
    private static final String BASE_URL = "http://127.0.0.1:8000/api";

    @FXML
    public void initialize() {
        studentColumn.setCellValueFactory(new javafx.scene.control.cell.PropertyValueFactory<>("studentName"));
        messagesColumn.setCellValueFactory(new javafx.scene.control.cell.PropertyValueFactory<>("messages"));
        reactionsColumn.setCellValueFactory(new javafx.scene.control.cell.PropertyValueFactory<>("reactions"));
        scoreColumn.setCellValueFactory(new javafx.scene.control.cell.PropertyValueFactory<>("scoreLabel"));

        loadCriteria();
        loadScores();
    }

    private void loadCriteria() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/lecturer/participation/criteria")
                .GET().build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            ParticipationCriteria c = mapper.readValue(response.body(), ParticipationCriteria.class);
                            pointsPerMessageField.setText(String.valueOf(c.getPointsPerMessage()));
                            pointsPerReactionField.setText(String.valueOf(c.getPointsPerReactionGiven()));
                            maxScoreField.setText(String.valueOf(c.getMaxScore()));
                        } else {
                            System.err.println("Failed to load criteria: " + response.statusCode());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                })
        );
    }

    @FXML
    void handleSaveCriteria() {
        try {
            var payload = mapper.createObjectNode();
            payload.put("points_per_message", parseIntOrDefault(pointsPerMessageField.getText(), 1));
            payload.put("points_per_reaction_given", parseIntOrDefault(pointsPerReactionField.getText(), 0));
            payload.put("max_score", parseIntOrDefault(maxScoreField.getText(), 100));

            HttpClient client = HttpClient.newHttpClient();
            var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/lecturer/participation/criteria")
                    .POST(HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload)))
                    .build();

            client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                    Platform.runLater(() -> {
                        if (response.statusCode() == 200) {
                            loadScores(); // scores depend on criteria, refresh them
                        } else {
                            showAlert("Could not save criteria (status " + response.statusCode() + ")");
                        }
                    })
            );
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void loadScores() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/lecturer/participation/scores")
                .GET().build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            List<StudentScore> scores = List.of(mapper.readValue(response.body(), StudentScore[].class));
                            renderScores(scores);
                        } else {
                            System.err.println("Failed to load scores: " + response.statusCode());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                })
        );
    }

    private void renderScores(List<StudentScore> scores) {
        List<ScoreRow> rows = scores.stream().map(s -> new ScoreRow(
                s.getStudentName(),
                String.valueOf(s.getMessageCount()),
                String.valueOf(s.getReactionsGivenCount()),
                s.getScore() + " / " + s.getMaxScore()
        )).toList();

        scoresTable.getItems().setAll(rows);
    }

    private int parseIntOrDefault(String text, int fallback) {
        try { return Integer.parseInt(text.trim()); } catch (Exception e) { return fallback; }
    }

    private void showAlert(String message) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION, message);
        alert.showAndWait();
    }

    public static class ScoreRow {
        private final SimpleStringProperty studentName;
        private final SimpleStringProperty messages;
        private final SimpleStringProperty reactions;
        private final SimpleStringProperty scoreLabel;

        public ScoreRow(String studentName, String messages, String reactions, String scoreLabel) {
            this.studentName = new SimpleStringProperty(studentName);
            this.messages = new SimpleStringProperty(messages);
            this.reactions = new SimpleStringProperty(reactions);
            this.scoreLabel = new SimpleStringProperty(scoreLabel);
        }

        public String getStudentName() { return studentName.get(); }
        public String getMessages() { return messages.get(); }
        public String getReactions() { return reactions.get(); }
        public String getScoreLabel() { return scoreLabel.get(); }
    }
}