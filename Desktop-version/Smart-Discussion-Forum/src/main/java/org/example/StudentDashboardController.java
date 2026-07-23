package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.StackPane;
import javafx.scene.layout.VBox;
import javafx.util.Duration;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.OffsetDateTime;

public class StudentDashboardController {

    private static final String BASE_URL = "http://127.0.0.1:8000/api";

    @FXML private StackPane contentArea;
    @FXML private Label welcomeLabel;

    private static StudentDashboardController instance;
    private Object currentController;

    private final ObjectMapper mapper = new ObjectMapper();

    // NEW: quiz-start popup state
    private StackPane overlayRoot;       // wraps the WHOLE window (sidebar + content)
    private StackPane quizPopupNode;     // currently showing popup, or null
    private Timeline activeQuizPollTimeline;
    private Timeline quizPopupCountdown;
    private Long lastPoppedQuizId = null;

    @FXML
    public void initialize() {
        instance = this;
        welcomeLabel.setText("Welcome back, " + Session.getUserName());
        loadView("groups_view.fxml"); // default landing tab, mirrors updateView('groups') on web

        // NEW: once this view is actually attached to a Scene, wrap the real
        // root (BorderPane) in a StackPane so the popup can sit on top of
        // EVERYTHING - sidebar, top bar, content - not just contentArea.
        // This poll runs for the whole dashboard session, so it deliberately
        // does NOT implement PollingView (that's for per-tab polling only).
        welcomeLabel.sceneProperty().addListener((obs, oldScene, newScene) -> {
            if (newScene != null && overlayRoot == null) {
                wrapRootForOverlay(newScene);
                checkForActiveQuiz();     // immediate check, mirrors web
                startActiveQuizPolling();  // then every 15s
            }
        });
    }

    private void wrapRootForOverlay(Scene scene) {
        Parent originalRoot = scene.getRoot();
        overlayRoot = new StackPane(originalRoot);
        scene.setRoot(overlayRoot);
    }

    // ------------------------------------------------------------------
    // NEW: Quiz-start popup (mirrors checkForActiveQuiz / openQuizPopup on web)
    // ------------------------------------------------------------------

    private void startActiveQuizPolling() {
        activeQuizPollTimeline = new Timeline(new KeyFrame(Duration.seconds(15), e -> checkForActiveQuiz()));
        activeQuizPollTimeline.setCycleCount(Timeline.INDEFINITE);
        activeQuizPollTimeline.play();
    }

    private void checkForActiveQuiz() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/student/active-quiz")
                .build(); // GET by default

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleActiveQuizResponse)
                .exceptionally(ex -> {
                    System.err.println("Failed to check active quiz: " + ex.getMessage());
                    return null;
                });
        } catch (Exception e) {
            System.err.println("Failed to check active quiz: " + e.getMessage());
        }
    }

    private void handleActiveQuizResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                if (response.statusCode() != 200) return;
                JsonNode data = mapper.readTree(response.body());

                boolean active = data.hasNonNull("active") && data.get("active").asBoolean();
                if (!active) return;

                long id = data.get("id").asLong();
                if (lastPoppedQuizId != null && lastPoppedQuizId == id) return; // already popped this one

                lastPoppedQuizId = id;
                openQuizPopup(data);
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private void openQuizPopup(JsonNode data) {
        if (quizPopupNode != null) return; // one already showing

        long quizId = data.get("id").asLong();
        String title = data.get("title").asText();
        int durationMinutes = data.get("duration_minutes").asInt();
        OffsetDateTime deadline = OffsetDateTime.parse(data.get("start_time").asText())
                .plusMinutes(durationMinutes);

        Label titleLabel = new Label(title);
        titleLabel.getStyleClass().add("quiz-popup-title");

        Label durationLabel = new Label("Duration: " + durationMinutes + " minutes");
        Label countdownLabel = new Label("--:--");
        countdownLabel.getStyleClass().add("quiz-popup-countdown");

        Button startButton = new Button("Start Quiz Now");
        startButton.getStyleClass().add("button-primary");
        startButton.setOnAction(e -> goToQuiz(quizId));

        VBox card = new VBox(12, titleLabel, durationLabel, countdownLabel, startButton);
        card.setAlignment(Pos.CENTER);
        card.setPadding(new Insets(32));
        card.setMaxWidth(360);
        card.getStyleClass().add("quiz-popup-card");

        quizPopupNode = new StackPane(card);
        quizPopupNode.setStyle("-fx-background-color: rgba(15,23,42,0.6);"); // matches web's bg-slate-900/60
        quizPopupNode.setPickOnBounds(true); // blocks clicks to sidebar/content underneath

        overlayRoot.getChildren().add(quizPopupNode);

        quizPopupCountdown = new Timeline(new KeyFrame(Duration.seconds(1), e -> {
            long secondsLeft = java.time.Duration.between(OffsetDateTime.now(), deadline).getSeconds();
            if (secondsLeft <= 0) {
                quizPopupCountdown.stop();
                goToQuiz(quizId); // ran out while still on the popup - auto-enter, mirrors web
                return;
            }
            countdownLabel.setText(String.format("%d:%02d", secondsLeft / 60, secondsLeft % 60));
        }));
        quizPopupCountdown.setCycleCount(Timeline.INDEFINITE);
        quizPopupCountdown.play();
    }

    private void goToQuiz(long quizId) {
        if (quizPopupCountdown != null) quizPopupCountdown.stop();
        if (quizPopupNode != null) {
            overlayRoot.getChildren().remove(quizPopupNode);
            quizPopupNode = null;
        }
        AppState.setSelectedQuizId(quizId);
        navigateTo("quiz_attempt_view.fxml");
    }

    // ------------------------------------------------------------------
    // Existing view-switching logic (unchanged)
    // ------------------------------------------------------------------

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

    @FXML void handleNavGroups(ActionEvent event) { loadView("groups_view.fxml"); }
    @FXML void handleNavDiscussions(ActionEvent event) { loadView("discussions_view.fxml"); }
    @FXML void handleNavMyTopics(ActionEvent event) { loadView("my_topics_view.fxml"); }
    @FXML void handleNavQuizzes(ActionEvent event) { loadView("quizzes_view.fxml"); }
    @FXML void handleNavPerformance(ActionEvent event) { loadView("performance_view.fxml"); }

    @FXML
    void handleLogout(ActionEvent event) {
        if (activeQuizPollTimeline != null) activeQuizPollTimeline.stop();
        Session.clear();
        App.switchScene("login_view.fxml", 900, 700);
    }
}