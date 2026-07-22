package org.example;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Label;
import javafx.scene.layout.StackPane;

public class LecturerDashboardController {

    @FXML private StackPane contentArea;
    @FXML private Label welcomeLabel;

    private static LecturerDashboardController instance;
    private Object currentController;

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
}