package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Label;

import java.net.URL;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.ResourceBundle;

public class AdminOverviewController implements Initializable {

    private static final String STATS_URL = "http://127.0.0.1:8000/api/desktop/admin/stats";

    @FXML private Label totalUsersLabel;
    @FXML private Label activeUsersLabel;
    @FXML private Label inactiveUsersLabel;
    @FXML private Label blockedUsersLabel;

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

                totalUsersLabel.setText(String.valueOf(stats.get("total_users").asInt()));
                activeUsersLabel.setText(String.valueOf(stats.get("active_users").asInt()));
                inactiveUsersLabel.setText(String.valueOf(stats.get("inactive_users").asInt()));
                blockedUsersLabel.setText(String.valueOf(stats.get("blocked_users").asInt()));

            } catch (Exception e) {
                totalUsersLabel.setText("err");
            }
        });
    }
}
