package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import javafx.geometry.Insets;
import javafx.scene.Cursor;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

import static org.example.AppState.returnView;

// Shared across Group Details, Discussions, and My Topics — all three render
// the same card style, same as the web's single renderTopicItem() function.
public class TopicCardFactory {

    public static VBox build(JsonNode topic, String returnView) {
        VBox card = new VBox(4);
        card.getStyleClass().add("card");
        card.setPadding(new Insets(14));
        card.setCursor(Cursor.HAND);

        String title = topic.hasNonNull("title") ? topic.get("title").asText() : "Untitled topic"; // CHANGED
        String author = (topic.hasNonNull("user") && topic.get("user").hasNonNull("name"))          // CHANGED
            ? topic.get("user").get("name").asText() : "Unknown";
        int replies = topic.has("messages_count") ? topic.get("messages_count").asInt() : 0;


        Label titleLabel = new Label(title);
        titleLabel.getStyleClass().add("card-title");

        HBox metaRow = new HBox(6);
        Label authorLabel = new Label("Posted by " + author);
        Label repliesLabel = new Label(" • " + replies + " replies");
        authorLabel.getStyleClass().add("muted-label");
        repliesLabel.getStyleClass().add("muted-label");
        metaRow.getChildren().addAll(authorLabel, repliesLabel);

        card.getChildren().addAll(titleLabel, metaRow);

        long topicId = topic.get("id").asLong();
        Long groupId = (topic.has("group_id") && !topic.get("group_id").isNull())
            ? topic.get("group_id").asLong() : null;

        card.setOnMouseClicked(e -> {
            AppState.setSelectedTopicId(topicId);
            AppState.setSelectedTopicTitle(title);
            AppState.setSelectedGroupId(groupId);
            AppState.setReturnView(returnView);
            // derived from the topic itself — see note below
            StudentDashboardController.navigateTo("topic_chat_view.fxml");
        });

        return card;
    }
}
