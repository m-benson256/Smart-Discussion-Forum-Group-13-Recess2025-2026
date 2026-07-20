package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.Cursor;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.Optional;

public class GroupDetailsController {

    @FXML private Label nameLabel;
    @FXML private Label descriptionLabel;
    @FXML private Label memberCountLabel;
    @FXML private Button actionButton;
    @FXML private Button createTopicButton;
    @FXML private VBox topicsContainer;

    private final ObjectMapper mapper = new ObjectMapper();
    private JsonNode currentGroup;

    @FXML
    public void initialize() {
        fetchGroupDetails();
    }

    private void fetchGroupDetails() {
        Long groupId = AppState.getSelectedGroupId();
        if (groupId == null) return;

        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/groups/" + groupId)
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderDetails);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderDetails(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                currentGroup = mapper.readTree(response.body());

                nameLabel.setText(currentGroup.get("name").asText());

                String description = currentGroup.has("description") && !currentGroup.get("description").isNull()
                    ? currentGroup.get("description").asText() : "";
                descriptionLabel.setText(description);

                memberCountLabel.setText(currentGroup.get("members_count").asInt() + " members");

                updateActionButton();
                fetchTopics(); // NEW: once we know isMember/isCreator, load the topics list

            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private void updateActionButton() {
        boolean isCreator = currentGroup.has("created_by")
            && currentGroup.get("created_by").asLong() == Session.getUserId();
        boolean isMember = currentGroup.get("is_member").asBoolean();
        String visibility = currentGroup.get("visibility").asText();

        if (isCreator) {
            actionButton.setText("You created this group");
            actionButton.setDisable(true);
        } else if (isMember) {
            actionButton.setText("Leave Group");
            actionButton.setDisable(false);
        } else if ("private".equals(visibility)) {
            actionButton.setText("Request to Join");
            actionButton.setDisable(false);
        } else {
            actionButton.setText("Join Group");
            actionButton.setDisable(false);
        }

        // NEW: mirrors the web's `group.isMember || group.isCreator` check for showing "Create Topic"
        boolean canPost = isMember || isCreator;
        createTopicButton.setVisible(canPost);
        createTopicButton.setManaged(canPost);
    }

    // NEW: fetches ALL topics and filters client-side by groupId —
    // same approach the web dashboard uses (fetchTopics() + state.topics.filter(t => t.groupId === ...))
    private void fetchTopics() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/topics")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderTopics);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderTopics(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode allTopics = mapper.readTree(response.body());
                Long groupId = AppState.getSelectedGroupId();

                topicsContainer.getChildren().clear();

                boolean any = false;
                for (JsonNode topic : allTopics) {
                    JsonNode groupIdNode = topic.get("group_id");
                    if (groupIdNode == null || groupIdNode.isNull()
                        || groupIdNode.asLong() != groupId) {
                        continue; // not this group's topic
                    }
                    any = true;
                    topicsContainer.getChildren().add(TopicCardFactory.build(topic, "group_details_view.fxml"));
                }

                if (!any) {
                    Label empty = new Label("No topics yet — be the first to start a discussion.");
                    empty.getStyleClass().add("muted-label");
                    topicsContainer.getChildren().add(empty);
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }



    // NEW: mirrors the web's topic-modal + save-topic handler
    @FXML
    void handleCreateTopic(ActionEvent event) {
        TopicDialogHelper.showCreateTopicDialog(AppState.getSelectedGroupId(), this::fetchTopics);
    }




    @FXML
    void handleActionButton(ActionEvent event) {
        Long groupId = AppState.getSelectedGroupId();
        String currentText = actionButton.getText();

        String endpoint = "Leave Group".equals(currentText)
            ? "http://127.0.0.1:8000/api/desktop/groups/" + groupId + "/leave"
            : "http://127.0.0.1:8000/api/desktop/groups/" + groupId + "/join";

        try {
            HttpRequest request = Session.authorizedRequestBuilder(endpoint)
                .POST(HttpRequest.BodyPublishers.noBody())
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> Platform.runLater(this::fetchGroupDetails));

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @FXML
    void handleBack(ActionEvent event) {
        StudentDashboardController.navigateTo("groups_view.fxml");
    }
}
