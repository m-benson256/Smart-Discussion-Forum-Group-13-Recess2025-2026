package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class MyTopicsController {

    @FXML private VBox myTopicsContainer;
    @FXML private VBox recommendedContainer;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        AppState.setSelectedGroupId(null);
        fetchMyTopics();
        fetchRecommendedTopics();
    }

    // Filters by the topic's user_id against Session.getUserId() — the web matches by
    // author *name* (t.author === state.user), but names aren't guaranteed unique, so ID is safer
    private void fetchMyTopics() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/topics")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderMyTopics);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderMyTopics(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode topics = mapper.readTree(response.body());
                myTopicsContainer.getChildren().clear();

                boolean any = false;
                for (JsonNode topic : topics) {
                    if (!topic.has("user_id") || topic.get("user_id").asLong() != Session.getUserId()) continue;
                    any = true;
                    myTopicsContainer.getChildren().add(TopicCardFactory.build(topic, "my_topics_view.fxml"));
                }
                if (!any) {
                    Label empty = new Label("You haven't started any topics yet.");
                    empty.getStyleClass().add("muted-label");
                    myTopicsContainer.getChildren().add(empty);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private void fetchRecommendedTopics() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/recommended-topics")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderRecommended);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderRecommended(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode topics = mapper.readTree(response.body());
                recommendedContainer.getChildren().clear();

                boolean any = false;
                for (JsonNode topic : topics) {
                    any = true;
                    recommendedContainer.getChildren().add(TopicCardFactory.build(topic,"my_topics_view.fxml"));
                }
                if (!any) {
                    Label empty = new Label("No recommendations yet — pick some interests to get suggestions.");
                    empty.getStyleClass().add("muted-label");
                    recommendedContainer.getChildren().add(empty);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }
}
