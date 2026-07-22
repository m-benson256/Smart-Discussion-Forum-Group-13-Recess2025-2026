package org.example;

import java.net.http.HttpClient;
import java.net.http.HttpResponse;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.beans.property.SimpleStringProperty;
import javafx.fxml.FXML;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;

public class LecturerReportsController {

    @FXML private TableView<ReportRowView> reportsTable;
    @FXML private TableColumn<ReportRowView, String> studentColumn;
    @FXML private TableColumn<ReportRowView, String> quizColumn;
    @FXML private TableColumn<ReportRowView, String> scoreColumn;
    @FXML private TableColumn<ReportRowView, String> statusColumn;

    private final ObjectMapper mapper = new ObjectMapper();
    private static final String BASE_URL = "http://127.0.0.1:8000/api";

    @FXML
    public void initialize() {
        studentColumn.setCellValueFactory(new PropertyValueFactory<>("studentName"));
        quizColumn.setCellValueFactory(new PropertyValueFactory<>("quizTitle"));
        scoreColumn.setCellValueFactory(new PropertyValueFactory<>("scoreLabel"));
        statusColumn.setCellValueFactory(new PropertyValueFactory<>("statusLabel"));

        loadReports();
    }

    private void loadReports() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/lecturer/reports")
                .GET().build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            List<ReportRow> rows = List.of(mapper.readValue(response.body(), ReportRow[].class));
                            renderTable(rows);
                        } else {
                            System.err.println("Failed to load reports: " + response.statusCode());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                })
        );
    }

    private void renderTable(List<ReportRow> rows) {
        List<ReportRowView> views = rows.stream().map(r -> {
            Integer totalMarks = r.getTotalMarks();
            Integer score = r.getScore() == null ? 0 : r.getScore();
            boolean passed = totalMarks == null || totalMarks == 0 || ((double) score / totalMarks) >= 0.5;

            String scoreLabel = totalMarks != null ? score + "/" + totalMarks : String.valueOf(score);
            String statusLabel = passed ? "PASSED" : "FAILED";

            return new ReportRowView(r.getStudentName(), r.getQuizTitle(), scoreLabel, statusLabel);
        }).toList();

        reportsTable.getItems().setAll(views);
    }

    public static class ReportRowView {
        private final SimpleStringProperty studentName;
        private final SimpleStringProperty quizTitle;
        private final SimpleStringProperty scoreLabel;
        private final SimpleStringProperty statusLabel;

        public ReportRowView(String studentName, String quizTitle, String scoreLabel, String statusLabel) {
            this.studentName = new SimpleStringProperty(studentName);
            this.quizTitle = new SimpleStringProperty(quizTitle);
            this.scoreLabel = new SimpleStringProperty(scoreLabel);
            this.statusLabel = new SimpleStringProperty(statusLabel);
        }

        public String getStudentName() { return studentName.get(); }
        public String getQuizTitle() { return quizTitle.get(); }
        public String getScoreLabel() { return scoreLabel.get(); }
        public String getStatusLabel() { return statusLabel.get(); }
    }
}