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

import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Modality;
import javafx.stage.Stage;

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

    @FXML
    void handleCreateGroup(javafx.event.ActionEvent event) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("create_group_dialog.fxml"));
            Parent root = loader.load();

            CreateGroupController controller = loader.getController();
            controller.setOnGroupCreated(this::fetchGroups); // refresh list once created

            Stage dialogStage = new Stage();
            dialogStage.setTitle("Create Group");
            dialogStage.initModality(Modality.APPLICATION_MODAL);
            Scene scene = new Scene(root, 400, 400);
            scene.getStylesheets().add(getClass().getResource("style.css").toExternalForm());
            dialogStage.setScene(scene);
            dialogStage.showAndWait();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private VBox buildGroupCard(JsonNode group) {
        VBox card = new VBox(8);
        card.getStyleClass().add("card");
        card.setPrefWidth(260);
        long groupId = group.get("id").asLong();

        String name = group.get("name").asText();
        String description = group.has("description") && !group.get("description").isNull()
            ? group.get("description").asText() : "";
        int memberCount = group.get("members_count").asInt();
        String visibility = group.get("visibility").asText();
        boolean isBlocked = group.has("is_blocked") && group.get("is_blocked").asBoolean(); // NEW

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

        if (isBlocked) {
            // NEW: blocked groups are visible but inert — no click handler, dimmed, badge shown
            Label blockedBadge = new Label("BLOCKED");
            blockedBadge.getStyleClass().add("blocked-badge");
            card.getChildren().add(blockedBadge);
            card.setOpacity(0.5);
            card.setCursor(javafx.scene.Cursor.DEFAULT);
        } else {
            card.setCursor(javafx.scene.Cursor.HAND);
            card.setOnMouseClicked(e -> {
                AppState.setSelectedGroupId(groupId);
                StudentDashboardController.navigateTo("group_details_view.fxml");
            });
        }

        return card;
    }

}
