package org.example;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Label;
import javafx.scene.layout.StackPane;

public class StudentDashboardController {

    @FXML private StackPane contentArea;
    @FXML private Label welcomeLabel;

    private static StudentDashboardController instance;
    private Object currentController;

    @FXML
    public void initialize() {
        instance=this;
        welcomeLabel.setText("Welcome back, " + Session.getUserName());
        loadView("groups_view.fxml"); // default landing tab, mirrors updateView('groups') on web
    }

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

    @FXML
    void handleLogout(ActionEvent event) {
        Session.clear();
        App.switchScene("login_view.fxml", 900, 700);
    }
}
