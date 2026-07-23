package org.example;


import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Label;
import javafx.scene.layout.StackPane;
import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.animation.PauseTransition;
import javafx.application.Platform;
import javafx.geometry.Insets;
import javafx.scene.control.TextField;
import javafx.scene.layout.VBox;
import javafx.stage.Popup;
import javafx.util.Duration;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class StudentDashboardController {

    @FXML private StackPane contentArea;
    @FXML private Label welcomeLabel;

    private static StudentDashboardController instance;
    private Object currentController;
    @FXML private TextField searchField;

    private final ObjectMapper mapper = new ObjectMapper(); // NEW
    private final Popup searchPopup = new Popup(); // NEW
    private PauseTransition searchDebounce;

    @FXML
    public void initialize() {
        instance = this;
        welcomeLabel.setText("Welcome back, " + Session.getUserName());
        loadView("groups_view.fxml");
        setupSearch(); // NEW
    }

    // NEW
    private void setupSearch() {
        searchPopup.setAutoHide(true);

        searchDebounce = new PauseTransition(Duration.millis(300));
        searchDebounce.setOnFinished(e -> performSearch(searchField.getText().trim()));

        searchField.textProperty().addListener((obs, oldVal, newVal) -> {
            searchDebounce.stop();
            if (newVal == null || newVal.trim().isEmpty()) {
                searchPopup.hide();
                return;
            }
            searchDebounce.playFromStart();
        });
    }

    // NEW
    private void performSearch(String query) {
        if (query.isEmpty()) return;

        try {
            String encoded = java.net.URLEncoder.encode(query, java.nio.charset.StandardCharsets.UTF_8);
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/student/search?q=" + encoded)
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderSearchResults);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    // NEW
    private void renderSearchResults(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode data = mapper.readTree(response.body());

                VBox container = new VBox(2);
                container.getStyleClass().add("search-popup");
                container.setPadding(new Insets(6));
                container.setPrefWidth(Math.max(searchField.getWidth(), 280));

                JsonNode groups = data.path("groups");
                JsonNode topics = data.path("topics");
                JsonNode quizzes = data.path("quizzes");

                boolean any = false;

                if (groups.isArray() && groups.size() > 0) {
                    any = true;
                    container.getChildren().add(sectionHeader("Groups"));
                    for (JsonNode g : groups) {
                        container.getChildren().add(searchRow(g.get("name").asText(), () -> {
                            AppState.setSelectedGroupId(g.get("id").asLong());
                            navigateTo("group_details_view.fxml");
                        }));
                    }
                }

                if (topics.isArray() && topics.size() > 0) {
                    any = true;
                    container.getChildren().add(sectionHeader("Topics"));
                    for (JsonNode t : topics) {
                        container.getChildren().add(searchRow(t.get("title").asText(), () -> {
                            Long groupId = (t.has("group_id") && !t.get("group_id").isNull())
                                ? t.get("group_id").asLong() : null;
                            AppState.setSelectedTopicId(t.get("id").asLong());
                            AppState.setSelectedTopicTitle(t.get("title").asText());
                            AppState.setSelectedGroupId(groupId);
                            AppState.setReturnView("discussions_view.fxml");
                            navigateTo("topic_chat_view.fxml");
                        }));
                    }
                }

                if (quizzes.isArray() && quizzes.size() > 0) {
                    any = true;
                    container.getChildren().add(sectionHeader("Quizzes"));
                    for (JsonNode q : quizzes) {
                        container.getChildren().add(searchRow(q.get("title").asText(), () -> {
                            AppState.setSelectedQuizId(q.get("id").asLong());
                            navigateTo("quiz_attempt_view.fxml");
                        }));
                    }
                }

                if (!any) {
                    Label empty = new Label("No results found");
                    empty.getStyleClass().add("muted-label");
                    empty.setPadding(new Insets(10));
                    container.getChildren().add(empty);
                }

                searchPopup.getContent().setAll(container);

                if (!searchPopup.isShowing()) {
                    var bounds = searchField.localToScreen(searchField.getBoundsInLocal());
                    searchPopup.show(searchField, bounds.getMinX(), bounds.getMaxY() + 4);
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    // NEW
    private Label sectionHeader(String text) {
        Label header = new Label(text.toUpperCase());
        header.getStyleClass().add("search-section-header");
        return header;
    }

    // NEW
    private Label searchRow(String text, Runnable onClick) {
        Label row = new Label(text);
        row.getStyleClass().add("search-result-row");
        row.setMaxWidth(Double.MAX_VALUE);
        row.setCursor(javafx.scene.Cursor.HAND);
        row.setOnMouseClicked(e -> {
            searchPopup.hide();
            onClick.run();
        });
        return row;
    }


    @FXML void handleNavPendingRequests(ActionEvent event) { loadView("pending_requests_view.fxml"); }

    private void loadView(String fxmlFile) {
        try {
            // NEW: stop polling on the outgoing view before we swap it out
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

    @FXML void handleNavGroups(ActionEvent event) { loadView("groups_view.fxml"); }
    @FXML void handleNavDiscussions(ActionEvent event) { loadView("discussions_view.fxml"); }
    @FXML void handleNavMyTopics(ActionEvent event) { loadView("my_topics_view.fxml"); }
    @FXML void handleNavQuizzes(ActionEvent event) { loadView("quizzes_view.fxml"); }
    @FXML void handleNavPerformance(ActionEvent event) { loadView("performance_view.fxml"); }
    @FXML void handleNavAnnouncements(ActionEvent event) { loadView("announcements_view.fxml"); }

    @FXML
    void handleLogout(ActionEvent event) {
        Session.clear();
        App.switchScene("login_view.fxml", 900, 700);
    }
}
