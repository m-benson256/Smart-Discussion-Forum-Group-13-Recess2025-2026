package org.example;

import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.geometry.Insets;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.Optional;

public class TopicDialogHelper {

    private static final ObjectMapper mapper = new ObjectMapper();

    // groupId == null means an ungrouped topic (Discussions tab)
    public static void showCreateTopicDialog(Long groupId, Runnable onPosted) {
        Dialog<ButtonType> dialog = new Dialog<>();
        dialog.setTitle("Create New Topic");
        dialog.getDialogPane().getButtonTypes().addAll(ButtonType.CANCEL, ButtonType.OK);

        TextField titleField = new TextField();
        titleField.setPromptText("e.g., Introduction to CSS Grid");
        TextArea contentArea = new TextArea();
        contentArea.setPromptText("Start the discussion...");
        contentArea.setPrefRowCount(4);

        VBox content = new VBox(10,
            new Label("Topic Title"), titleField,
            new Label("Post content"), contentArea);
        content.setPadding(new Insets(12));
        dialog.getDialogPane().setContent(content);

        Optional<ButtonType> result = dialog.showAndWait();
        if (result.isPresent() && result.get() == ButtonType.OK) {
            String title = titleField.getText().trim();
            String body = contentArea.getText().trim();
            if (title.isEmpty() || body.isEmpty()) return;

            post(title, body, groupId, onPosted);
        }
    }

    private static void post(String title, String content, Long groupId, Runnable onPosted) {
        try {
            var node = mapper.createObjectNode()
                .put("title", title)
                .put("content", content);
            if (groupId != null) {
                node.put("group_id", groupId);
            } else {
                node.putNull("group_id");
            }

            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/topics")
                .header("Content-Type", "application/json")
                .POST(HttpRequest.BodyPublishers.ofString(node.toString()))
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> Platform.runLater(onPosted));

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
