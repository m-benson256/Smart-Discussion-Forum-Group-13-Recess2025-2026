package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;

import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.List;
import java.util.Optional;

public class LecturerDiscussionsController {

    @FXML private VBox topicListContainer;
    @FXML private VBox messageListContainer;
    @FXML private Label selectedTopicTitle;
    @FXML private TextField replyField;

    private final ObjectMapper mapper = new ObjectMapper();
    private static final String BASE_URL = "http://127.0.0.1:8000/api";
    private Long currentTopicId = null;

    @FXML
    public void initialize() {
        loadTopics();
    }

    // ---------- Topics ----------

    private void loadTopics() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/topics").GET().build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            List<Topic> topics = List.of(mapper.readValue(response.body(), Topic[].class));
                            renderTopics(topics);
                        } else {
                            System.err.println("Failed to load topics: " + response.statusCode());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                })
        );
    }

    private void renderTopics(List<Topic> topics) {
        topicListContainer.getChildren().clear();
        for (Topic t : topics) {
            VBox card = new VBox(2);
            card.getStyleClass().add("card");
            card.setPadding(new Insets(10));
            card.setStyle("-fx-cursor: hand;");

            Label title = new Label(t.getTitle());
            title.getStyleClass().add("card-title");
            title.setStyle("-fx-font-size: 13px;");

            int count = t.getMessagesCount() == null ? 0 : t.getMessagesCount();
            String authorName = t.getUser() != null ? t.getUser().getName() : "Unknown";
            Label meta = new Label(authorName + " · " + count + " replies");
            meta.getStyleClass().add("muted-label");

            card.getChildren().addAll(title, meta);
            card.setOnMouseClicked(e -> selectTopic(t));
            topicListContainer.getChildren().add(card);
        }
    }

    private void selectTopic(Topic topic) {
        currentTopicId = topic.getId();
        selectedTopicTitle.setText(topic.getTitle());
        loadMessages(topic.getId());
    }

    @FXML
    void handleNewTopic() {
        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("New Topic");

        TextField titleField = new TextField();
        titleField.setPromptText("Title");
        TextArea contentArea = new TextArea();
        contentArea.setPromptText("What would you like to discuss?");
        contentArea.setPrefRowCount(4);

        VBox content = new VBox(10, new Label("Title"), titleField, new Label("Content"), contentArea);
        content.setPadding(new Insets(12));
        dialog.getDialogPane().setContent(content);
        dialog.getDialogPane().getButtonTypes().addAll(ButtonType.OK, ButtonType.CANCEL);

        Optional<ButtonType> result = dialog.showAndWait();
        if (result.isPresent() && result.get() == ButtonType.OK) {
            if (titleField.getText().isBlank() || contentArea.getText().isBlank()) {
                showAlert("Title and content are both required.");
                return;
            }
            submitNewTopic(titleField.getText(), contentArea.getText());
        }
    }

    private void submitNewTopic(String title, String content) {
        try {
            var payload = mapper.createObjectNode();
            payload.put("title", title);
            payload.put("content", content);

            HttpClient client = HttpClient.newHttpClient();
            var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/topics")
                    .POST(HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload)))
                    .build();

            client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                    Platform.runLater(() -> {
                        if (response.statusCode() == 201) {
                            loadTopics();
                        } else {
                            showAlert("Could not create topic (status " + response.statusCode() + ")");
                        }
                    })
            );
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    // ---------- Messages ----------

    private void loadMessages(Long topicId) {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/topics/" + topicId + "/messages")
                .GET().build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            List<Message> messages = List.of(mapper.readValue(response.body(), Message[].class));
                            renderMessages(messages);
                        } else {
                            System.err.println("Failed to load messages: " + response.statusCode());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                })
        );
    }

    private void renderMessages(List<Message> messages) {
        messageListContainer.getChildren().clear();
        if (messages.isEmpty()) {
            Label empty = new Label("No replies yet — be the first to respond.");
            empty.getStyleClass().add("muted-label");
            messageListContainer.getChildren().add(empty);
            return;
        }
        for (Message m : messages) {
            messageListContainer.getChildren().add(buildMessageBubble(m));
        }
    }

    private VBox buildMessageBubble(Message m) {
        VBox bubble = new VBox(4);
        bubble.getStyleClass().add("bubble-them");
        bubble.setPadding(new Insets(10));
        bubble.setMaxWidth(500);

        String authorName = m.getUser() != null ? m.getUser().getName() : "Unknown";
        Label author = new Label(authorName);
        author.setStyle("-fx-font-weight: bold; -fx-font-size: 12px;");

        Label body = new Label(m.getBody());
        body.setWrapText(true);

        bubble.getChildren().addAll(author, body);
        return bubble;
    }

    @FXML
    void handleSendReply() {
        String text = replyField.getText();
        if (text == null || text.isBlank()) return;
        if (currentTopicId == null) {
            showAlert("Select a topic first.");
            return;
        }

        try {
            var payload = mapper.createObjectNode();
            payload.put("body", text);

            HttpClient client = HttpClient.newHttpClient();
            var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/topics/" + currentTopicId + "/messages")
                    .POST(HttpRequest.BodyPublishers.ofString(mapper.writeValueAsString(payload)))
                    .build();

            client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                    Platform.runLater(() -> {
                        try {
                            if (response.statusCode() == 201) {
                                // Note: this endpoint wraps its response as {"status":..,"message":{...}}
                                JsonNode root = mapper.readTree(response.body());
                                Message sent = mapper.treeToValue(root.get("message"), Message.class);
                                messageListContainer.getChildren().add(buildMessageBubble(sent));
                                replyField.clear();
                            } else {
                                showAlert("Could not send reply (status " + response.statusCode() + ")");
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    })
            );
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void showAlert(String message) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION, message);
        alert.showAndWait();
    }
}