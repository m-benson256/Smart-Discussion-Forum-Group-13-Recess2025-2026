package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Label;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class GroupsController {

    @FXML private FlowPane groupsContainer;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        fetchGroups();
    }

    private void fetchGroups() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder("http://127.0.0.1:8000/api/desktop/groups")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderGroups);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderGroups(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode groups = mapper.readTree(response.body());
                groupsContainer.getChildren().clear();

                for (JsonNode group : groups) {
                    groupsContainer.getChildren().add(buildGroupCard(group));
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private VBox buildGroupCard(JsonNode group) {
        VBox card = new VBox(8);
        card.getStyleClass().add("card");
        card.setPrefWidth(260);
        card.setCursor(javafx.scene.Cursor.HAND);
        long groupId = group.get("id").asLong();

        String name = group.get("name").asText();
        String description = group.has("description") && !group.get("description").isNull()
            ? group.get("description").asText() : "";
        int memberCount = group.get("members_count").asInt();
        String visibility = group.get("visibility").asText();

        Label nameLabel = new Label(name + ("private".equals(visibility) ? "  \uD83D\uDD12" : ""));
        nameLabel.getStyleClass().add("title-label");
        nameLabel.setStyle("-fx-font-size: 15px;");

        Label descLabel = new Label(description);
        descLabel.setWrapText(true);
        descLabel.getStyleClass().add("muted-label");

        Label countLabel = new Label(memberCount + " members");
        countLabel.getStyleClass().add("muted-label");

        card.getChildren().addAll(nameLabel, descLabel, countLabel);
        card.setPadding(new Insets(16));

        // NEW: clicking the card navigates to details
        card.setOnMouseClicked(e -> {
            AppState.setSelectedGroupId(groupId);
            StudentDashboardController.navigateTo("group_details_view.fxml");
        });

        return card;
    }
}
