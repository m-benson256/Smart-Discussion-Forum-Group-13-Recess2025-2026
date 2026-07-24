package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class DiscussionsController {

    @FXML private VBox topicsContainer;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        AppState.setSelectedGroupId(null); // clear any leftover group context from a previous screen
        fetchTopics();
    }

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
                JsonNode topics = mapper.readTree(response.body());

                topicsContainer.getChildren().clear();

                boolean any = false;



                for (JsonNode topic : topics) {
                    boolean hasGroup = topic.has("group_id") && !topic.get("group_id").isNull();
                    if (hasGroup) continue;

                    any = true;
                    topicsContainer.getChildren().add(TopicCardFactory.build(topic,  "discussions_view.fxml"));
                }
                if (!any) {
                    Label empty = new Label("No discussions yet.");
                    empty.getStyleClass().add("muted-label");
                    topicsContainer.getChildren().add(empty);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    @FXML
    void handleCreateTopic(ActionEvent event) {
        TopicDialogHelper.showCreateTopicDialog(null, this::fetchTopics);
    }
}
