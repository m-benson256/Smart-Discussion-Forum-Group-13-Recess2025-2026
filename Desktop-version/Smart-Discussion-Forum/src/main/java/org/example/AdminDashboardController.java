package org.example;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Parent;
import javafx.scene.layout.StackPane;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

public class AdminDashboardController implements Initializable {

    @FXML private StackPane contentArea;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        showOverview(null);
    }

    @FXML
    void showOverview(ActionEvent event) {
        loadIntoContent("admin_overview_view.fxml");
    }

    @FXML
    void showUsers(ActionEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void showGroups(ActionEvent event) {
        loadIntoContent("admin_groups_view.fxml");
    }

    @FXML
    void showWarnings(ActionEvent event) {
        loadIntoContent("admin_warnings_view.fxml");
    }

    @FXML
    void handleLogout(ActionEvent event) {
        Session.clear();
        App.switchScene("login_view.fxml", 900, 700);
    }

    private void loadIntoContent(String fxmlFileName) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/org/example/" + fxmlFileName));
            Parent panel = loader.load();
            contentArea.getChildren().setAll(panel);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
