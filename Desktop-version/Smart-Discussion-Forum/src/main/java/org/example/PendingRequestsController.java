package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Priority;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class PendingRequestsController {

    @FXML private VBox requestsContainer;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        fetchRequests();
    }

    private void fetchRequests() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/my-pending-requests")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderRequests);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderRequests(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode requests = mapper.readTree(response.body());
                requestsContainer.getChildren().clear();

                if (!requests.elements().hasNext()) {
                    Label empty = new Label("No pending requests right now.");
                    empty.getStyleClass().add("muted-label");
                    requestsContainer.getChildren().add(empty);
                    return;
                }

                for (JsonNode req : requests) {
                    requestsContainer.getChildren().add(buildRequestRow(req));
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private HBox buildRequestRow(JsonNode req) {
        HBox row = new HBox(12);
        row.getStyleClass().add("card");
        row.setPadding(new Insets(12));

        long requestId = req.get("id").asLong();
        String userName = req.get("user").get("name").asText();
        String userEmail = req.get("user").get("email").asText();
        String groupName = req.get("group").get("name").asText();

        VBox info = new VBox(2);
        Label nameLabel = new Label(userName + "  (" + userEmail + ")");
        nameLabel.getStyleClass().add("card-title");
        Label groupLabel = new Label("Requesting to join: " + groupName);
        groupLabel.getStyleClass().add("muted-label");
        info.getChildren().addAll(nameLabel, groupLabel);
        HBox.setHgrow(info, Priority.ALWAYS);

        Button approveBtn = new Button("Approve");
        approveBtn.getStyleClass().add("button-primary");
        approveBtn.setOnAction(e -> respondToRequest(requestId, "approve"));

        Button rejectBtn = new Button("Reject");
        rejectBtn.getStyleClass().add("button-secondary");
        rejectBtn.setOnAction(e -> respondToRequest(requestId, "reject"));

        row.getChildren().addAll(info, approveBtn, rejectBtn);
        return row;
    }

    private void respondToRequest(long requestId, String action) {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/group-requests/" + requestId + "/" + action)
                .POST(HttpRequest.BodyPublishers.noBody())
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> Platform.runLater(this::fetchRequests)); // refresh list

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
