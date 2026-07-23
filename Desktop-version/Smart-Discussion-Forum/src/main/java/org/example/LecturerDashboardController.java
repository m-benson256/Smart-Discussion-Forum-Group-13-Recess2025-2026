package org.example;

import java.net.http.HttpClient;
import java.net.http.HttpResponse;

import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.StackPane;
import javafx.scene.layout.VBox;

public class LecturerDashboardController {

    @FXML private StackPane contentArea;
    @FXML private Label welcomeLabel;
    @FXML private TextField searchField;
    @FXML private VBox searchResultsPanel;

    private static LecturerDashboardController instance;
    private Object currentController;
    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        instance = this;
        welcomeLabel.setText("Welcome Lecturer, " + Session.getUserName());
        loadView("lecturer_overview_view.fxml"); // default landing tab, mirrors view-dashboard on web
    }

    private void loadView(String fxmlFile) {
        try {
            if (currentController instanceof PollingView pollingView) {
                pollingView.stopPolling();
            }

            FXMLLoader loader = new FXMLLoader(getClass().getResource(fxmlFile));
            Parent view = loader.load();
            currentController = loader.getController();
            contentArea.getChildren().setAll(view);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void navigateTo(String fxmlFile) {
        if (instance != null) {
            instance.loadView(fxmlFile);
        }
    }

    @FXML void handleNavOverview(ActionEvent event)      { loadView("lecturer_overview_view.fxml"); }
    @FXML void handleNavQuizzes(ActionEvent event)        { loadView("lecturer_quizzes_view.fxml"); }
    @FXML void handleNavGroups(ActionEvent event)         { loadView("lecturer_groups_view.fxml"); }
    @FXML void handleNavParticipation(ActionEvent event)  { loadView("lecturer_participation_view.fxml"); }
    @FXML void handleNavDiscussions(ActionEvent event)    { loadView("lecturer_discussions_view.fxml"); }
    @FXML void handleNavReports(ActionEvent event)        { loadView("lecturer_reports_view.fxml"); }
    @FXML void handleNavAnnouncements(ActionEvent event)  { loadView("lecturer_announcements_view.fxml"); }

    @FXML
    void handleLogout(ActionEvent event) {
        Session.clear();
        App.switchScene("login_view.fxml", 900, 700);
    }

    // ---------- Search ----------

    @FXML
    void handleSearch(ActionEvent event) {
        String query = searchField.getText();
        if (query == null || query.isBlank()) {
            hideSearchResults();
            return;
        }

        try {
            HttpClient client = HttpClient.newHttpClient();
            var request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/lecturer/search?q=" +
                            java.net.URLEncoder.encode(query, java.nio.charset.StandardCharsets.UTF_8))
                    .GET().build();

            client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                    Platform.runLater(() -> {
                        try {
                            if (response.statusCode() == 200) {
                                SearchResults results = mapper.readValue(response.body(), SearchResults.class);
                                renderSearchResults(results);
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    })
            );
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void renderSearchResults(SearchResults results) {
    searchResultsPanel.getChildren().clear();

    boolean hasResults = !results.getQuizzes().isEmpty() || !results.getStudents().isEmpty();
    if (!hasResults) {
        Label empty = new Label("No results found.");
        empty.getStyleClass().add("muted-label");
        searchResultsPanel.getChildren().add(empty);
    } else {
        if (!results.getQuizzes().isEmpty()) {
            Label header = new Label("QUIZZES");
            header.getStyleClass().add("muted-label");
            searchResultsPanel.getChildren().add(header);
            for (SearchQuizResult q : results.getQuizzes()) {
                Label item = new Label(q.getTitle() + " · " + q.getStatus());
                item.setStyle("-fx-cursor: hand;");
                item.setOnMouseClicked(e -> {
                    hideSearchResults();
                    QuizBuilderController.openForEdit(q.getId());
                });
                searchResultsPanel.getChildren().add(item);
            }
        }
        if (!results.getStudents().isEmpty()) {
            Label header = new Label("STUDENTS");
            header.getStyleClass().add("muted-label");
            searchResultsPanel.getChildren().add(header);
            for (SearchStudentResult s : results.getStudents()) {
                Label item = new Label(s.getName() + " · " + s.getEmail());
                item.setStyle("-fx-cursor: hand;");
                item.setOnMouseClicked(e -> {
                    hideSearchResults();
                    loadView("lecturer_participation_view.fxml");
                });
                searchResultsPanel.getChildren().add(item);
            }
        }
    }

    searchResultsPanel.setVisible(true);
    searchResultsPanel.setManaged(true);
    }

    private void hideSearchResults() {
        searchResultsPanel.setVisible(false);
        searchResultsPanel.setManaged(false);
        searchField.clear();
    }
}