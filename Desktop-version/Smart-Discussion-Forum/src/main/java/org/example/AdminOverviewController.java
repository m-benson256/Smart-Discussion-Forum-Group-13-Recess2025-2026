package org.example;

import java.io.IOException;
import java.net.URL;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.ResourceBundle;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Parent;
import javafx.scene.chart.PieChart;
import javafx.scene.control.ButtonType;
import javafx.scene.control.Dialog;
import javafx.scene.control.Label;
import javafx.scene.input.MouseEvent;

public class AdminOverviewController implements Initializable {

    private static final String STATS_URL = "http://127.0.0.1:8000/api/desktop/admin/stats";

    @FXML private Label totalUsersLabel;
    @FXML private Label activeUsersLabel;
    @FXML private Label inactiveUsersLabel;
    @FXML private Label blockedUsersLabel;
    @FXML private PieChart userStatusChart;

    private final ObjectMapper mapper = new ObjectMapper();
    private final HttpClient client = HttpClient.newHttpClient();

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        loadStats();
    }

    private void loadStats() {
        HttpRequest request = Session.authorizedRequestBuilder(STATS_URL)
            .GET()
            .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
            .thenAccept(this::handleStatsResponse)
            .exceptionally(ex -> {
                Platform.runLater(() -> totalUsersLabel.setText("err"));
                return null;
            });
    }

    private void handleStatsResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                if (response.statusCode() != 200) {
                    totalUsersLabel.setText("err " + response.statusCode());
                    return;
                }

                JsonNode stats = mapper.readTree(response.body());

                int totalUsers = stats.get("total_users").asInt();
                int activeUsers = stats.get("active_users").asInt();
                int inactiveUsers = stats.get("inactive_users").asInt();
                int blockedUsers = stats.get("blocked_users").asInt();

                totalUsersLabel.setText(String.valueOf(totalUsers));
                activeUsersLabel.setText(String.valueOf(activeUsers));
                inactiveUsersLabel.setText(String.valueOf(inactiveUsers));
                blockedUsersLabel.setText(String.valueOf(blockedUsers));
                populateStatusChart(activeUsers, inactiveUsers, blockedUsers);

            } catch (Exception e) {
                totalUsersLabel.setText("err");
            }
        });
    }

    private void populateStatusChart(int active, int inactive, int blocked) {
        userStatusChart.getData().clear();

        userStatusChart.getData().add(new PieChart.Data("Active", active));
        userStatusChart.getData().add(new PieChart.Data("Inactive", inactive));
        userStatusChart.getData().add(new PieChart.Data("Blocked", blocked));
    }

    @FXML
    void handleTotalUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleActiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleInactiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleBlockedUsersClick(MouseEvent event) {
        loadIntoContent("admin_warnings_view.fxml");
    }

    @FXML
    void handleExpandChart() {
        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("User Status Distribution");
        dialog.getDialogPane().getButtonTypes().add(ButtonType.CLOSE);

        PieChart expandedChart = new PieChart();
        expandedChart.setLegendVisible(true);
        expandedChart.setLabelsVisible(true);
        expandedChart.setPrefSize(760, 520);
        expandedChart.getData().addAll(userStatusChart.getData());
        expandedChart.setStyle("-fx-pie-inner-radius: 0.6;");

        dialog.getDialogPane().setContent(expandedChart);
        dialog.showAndWait();
    }

    private void loadIntoContent(String fxmlFileName) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/org/example/" + fxmlFileName));
            Parent panel = loader.load();
            Parent root = totalUsersLabel.getScene().getRoot();
            var contentArea = (javafx.scene.layout.StackPane) root.lookup("#contentArea");
            if (contentArea != null) {
                contentArea.getChildren().setAll(panel);
            }
        } catch (IOException ignored) {
        }
                int totalUsers = stats.get("total_users").asInt();
                int activeUsers = stats.get("active_users").asInt();
                int inactiveUsers = stats.get("inactive_users").asInt();
                int blockedUsers = stats.get("blocked_users").asInt();

                totalUsersLabel.setText(String.valueOf(totalUsers));
                activeUsersLabel.setText(String.valueOf(activeUsers));
                inactiveUsersLabel.setText(String.valueOf(inactiveUsers));
                blockedUsersLabel.setText(String.valueOf(blockedUsers));
                populateStatusChart(activeUsers, inactiveUsers, blockedUsers);

            } catch (Exception e) {
                totalUsersLabel.setText("err");
            }
        });
    }

    private void populateStatusChart(int active, int inactive, int blocked) {
        userStatusChart.getData().clear();

        userStatusChart.getData().add(new PieChart.Data("Active", active));
        userStatusChart.getData().add(new PieChart.Data("Inactive", inactive));
        userStatusChart.getData().add(new PieChart.Data("Blocked", blocked));
    }

    @FXML
    void handleTotalUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleActiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleInactiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleBlockedUsersClick(MouseEvent event) {
        loadIntoContent("admin_warnings_view.fxml");
    }

    @FXML
    void handleExpandChart() {
        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("User Status Distribution");
        dialog.getDialogPane().getButtonTypes().add(ButtonType.CLOSE);

        PieChart expandedChart = new PieChart();
        expandedChart.setLegendVisible(true);
        expandedChart.setLabelsVisible(true);
        expandedChart.setPrefSize(760, 520);
        expandedChart.getData().addAll(userStatusChart.getData());
        expandedChart.setStyle("-fx-pie-inner-radius: 0.6;");

        dialog.getDialogPane().setContent(expandedChart);
        dialog.showAndWait();
    }

    private void loadIntoContent(String fxmlFileName) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/org/example/" + fxmlFileName));
            Parent panel = loader.load();
            Parent root = totalUsersLabel.getScene().getRoot();
            var contentArea = (javafx.scene.layout.StackPane) root.lookup("#contentArea");
            if (contentArea != null) {
                contentArea.getChildren().setAll(panel);
            }
        } catch (IOException ignored) {
        }
                int totalUsers = stats.get("total_users").asInt();
                int activeUsers = stats.get("active_users").asInt();
                int inactiveUsers = stats.get("inactive_users").asInt();
                int blockedUsers = stats.get("blocked_users").asInt();

                totalUsersLabel.setText(String.valueOf(totalUsers));
                activeUsersLabel.setText(String.valueOf(activeUsers));
                inactiveUsersLabel.setText(String.valueOf(inactiveUsers));
                blockedUsersLabel.setText(String.valueOf(blockedUsers));
                populateStatusChart(activeUsers, inactiveUsers, blockedUsers);

            } catch (Exception e) {
                totalUsersLabel.setText("err");
            }
        });
    }

    private void populateStatusChart(int active, int inactive, int blocked) {
        userStatusChart.getData().clear();

        userStatusChart.getData().add(new PieChart.Data("Active", active));
        userStatusChart.getData().add(new PieChart.Data("Inactive", inactive));
        userStatusChart.getData().add(new PieChart.Data("Blocked", blocked));
    }

    @FXML
    void handleTotalUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleActiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleInactiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleBlockedUsersClick(MouseEvent event) {
        loadIntoContent("admin_warnings_view.fxml");
    }

    @FXML
    void handleExpandChart() {
        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("User Status Distribution");
        dialog.getDialogPane().getButtonTypes().add(ButtonType.CLOSE);

        PieChart expandedChart = new PieChart();
        expandedChart.setLegendVisible(true);
        expandedChart.setLabelsVisible(true);
        expandedChart.setPrefSize(760, 520);
        expandedChart.getData().addAll(userStatusChart.getData());
        expandedChart.setStyle("-fx-pie-inner-radius: 0.6;");

        dialog.getDialogPane().setContent(expandedChart);
        dialog.showAndWait();
    }

    private void loadIntoContent(String fxmlFileName) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/org/example/" + fxmlFileName));
            Parent panel = loader.load();
            Parent root = totalUsersLabel.getScene().getRoot();
            var contentArea = (javafx.scene.layout.StackPane) root.lookup("#contentArea");
            if (contentArea != null) {
                contentArea.getChildren().setAll(panel);
            }
        } catch (IOException ignored) {
        }
                int totalUsers = stats.get("total_users").asInt();
                int activeUsers = stats.get("active_users").asInt();
                int inactiveUsers = stats.get("inactive_users").asInt();
                int blockedUsers = stats.get("blocked_users").asInt();

                totalUsersLabel.setText(String.valueOf(totalUsers));
                activeUsersLabel.setText(String.valueOf(activeUsers));
                inactiveUsersLabel.setText(String.valueOf(inactiveUsers));
                blockedUsersLabel.setText(String.valueOf(blockedUsers));
                populateStatusChart(activeUsers, inactiveUsers, blockedUsers);

            } catch (Exception e) {
                totalUsersLabel.setText("err");
            }
        });
    }

    private void populateStatusChart(int active, int inactive, int blocked) {
        userStatusChart.getData().clear();

        userStatusChart.getData().add(new PieChart.Data("Active", active));
        userStatusChart.getData().add(new PieChart.Data("Inactive", inactive));
        userStatusChart.getData().add(new PieChart.Data("Blocked", blocked));
    }

    @FXML
    void handleTotalUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleActiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleInactiveUsersClick(MouseEvent event) {
        loadIntoContent("admin_users_view.fxml");
    }

    @FXML
    void handleBlockedUsersClick(MouseEvent event) {
        loadIntoContent("admin_warnings_view.fxml");
    }

    @FXML
    void handleExpandChart() {
        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("User Status Distribution");
        dialog.getDialogPane().getButtonTypes().add(ButtonType.CLOSE);

        PieChart expandedChart = new PieChart();
        expandedChart.setLegendVisible(true);
        expandedChart.setLabelsVisible(true);
        expandedChart.setPrefSize(760, 520);
        expandedChart.getData().addAll(userStatusChart.getData());
        expandedChart.setStyle("-fx-pie-inner-radius: 0.6;");

        dialog.getDialogPane().setContent(expandedChart);
        dialog.showAndWait();
    }

    private void loadIntoContent(String fxmlFileName) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/org/example/" + fxmlFileName));
            Parent panel = loader.load();
            Parent root = totalUsersLabel.getScene().getRoot();
            var contentArea = (javafx.scene.layout.StackPane) root.lookup("#contentArea");
            if (contentArea != null) {
                contentArea.getChildren().setAll(panel);
            }
        } catch (IOException ignored) {
        }
    }
}
