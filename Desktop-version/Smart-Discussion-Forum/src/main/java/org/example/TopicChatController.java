package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.ScrollPane;
import javafx.scene.control.TextField;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.util.Duration;
import javafx.scene.control.*;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.OffsetDateTime;
import java.time.format.DateTimeFormatter;

// NEW: lets StudentDashboardController stop our polling before swapping views away
public class TopicChatController implements PollingView {

    @FXML private Label topicTitleLabel;
    @FXML private VBox messagesContainer;
    @FXML private ScrollPane scrollPane;
    @FXML private TextField messageInput;

    private final ObjectMapper mapper = new ObjectMapper();
    private Timeline pollingTimeline;
    private Long topicId;

    @FXML
    public void initialize() {
        topicId = AppState.getSelectedTopicId();
        String title = AppState.getSelectedTopicTitle();
        topicTitleLabel.setText(title != null ? title : "Discussion");

        fetchMessages();

        // NEW: mirrors the web's startMessagePolling — refresh every 4 seconds
        pollingTimeline = new Timeline(new KeyFrame(Duration.seconds(4), e -> fetchMessages()));
        pollingTimeline.setCycleCount(Timeline.INDEFINITE);
        pollingTimeline.play();
    }

    @Override
    public void stopPolling() {
        if (pollingTimeline != null) {
            pollingTimeline.stop();
        }
    }



    private void fetchMessages() {
        if (topicId == null) return;

        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/topics/" + topicId + "/messages")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderMessages);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderMessages(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode messages = mapper.readTree(response.body());

                // Preserve whatever's currently typed, same as the web preserves the draft


                messagesContainer.getChildren().clear();

                for (JsonNode msg : messages) {
                    messagesContainer.getChildren().add(buildMessageBubble(msg));
                }
                // Auto-scroll to bottom, mirrors chatBox.scrollTop = chatBox.scrollHeight
                Platform.runLater(() -> scrollPane.setVvalue(1.0));

            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private VBox buildMessageBubble(JsonNode msg) {
        long messageId = msg.get("id").asLong();
        boolean isMe = msg.has("user_id") && msg.get("user_id").asLong() == Session.getUserId();
        String author = msg.has("user") && !msg.get("user").isNull()
            ? msg.get("user").get("name").asText() : "Unknown";
        String text = msg.get("body").asText();
        String time = formatTime(msg.has("created_at") ? msg.get("created_at").asText() : null);

        Label meta = new Label(author + "  •  " + time);
        meta.getStyleClass().add("muted-label");

        Label bubble = new Label(text);
        bubble.setWrapText(true);
        bubble.setPadding(new Insets(10, 14, 10, 14));
        bubble.getStyleClass().add(isMe ? "bubble-me" : "bubble-them");

        // NEW: like button
        int likeCount = msg.has("liked_by_count") ? msg.get("liked_by_count").asInt() : 0;
        boolean myLike = msg.has("liked_by_me") && msg.get("liked_by_me").asBoolean();
        Button likeBtn = new Button((myLike ? "♥ " : "♡ ") + likeCount);
        likeBtn.getStyleClass().add("link-label");
        likeBtn.setOnAction(e -> toggleLike(messageId));

        // NEW: reaction badges — clicking an existing one toggles it off (same as the web)
        HBox reactionsRow = new HBox(4);
        if (msg.has("grouped_reactions")) {
            for (JsonNode r : msg.get("grouped_reactions")) {
                String emoji = r.get("emoji").asText();
                int count = r.get("count").asInt();
                Button badge = new Button(emoji + " " + count);
                badge.getStyleClass().add(r.has("me") && r.get("me").asBoolean() ? "reaction-badge-active" : "reaction-badge");
                badge.setOnAction(e -> toggleReaction(messageId, emoji));
                reactionsRow.getChildren().add(badge);
            }
        }

        // NEW: flag indicator — visual only, matches the web (flagging itself happens via right-click menu)
        boolean myFlag = msg.has("flagged_by_me") && msg.get("flagged_by_me").asBoolean();
        HBox actionsRow = new HBox(10, likeBtn, reactionsRow);
        actionsRow.setAlignment(Pos.CENTER_LEFT);
        if (myFlag) {
            Label flagIcon = new Label("⚑ flagged");
            flagIcon.getStyleClass().add("muted-label");
            actionsRow.getChildren().add(flagIcon);
        }

        VBox column = new VBox(4, meta, bubble, actionsRow);
        column.setMaxWidth(420);

        HBox row = new HBox(column);
        row.setAlignment(isMe ? Pos.CENTER_RIGHT : Pos.CENTER_LEFT);

        // NEW: right-click menu — replaces the web's contextmenu + floating emoji picker
        ContextMenu contextMenu = new ContextMenu();
        String[] quickEmojis = { "👍", "❤️", "😂", "😮", "😢" };
        for (String emoji : quickEmojis) {
            MenuItem item = new MenuItem(emoji);
            item.setOnAction(e -> toggleReaction(messageId, emoji));
            contextMenu.getItems().add(item);
        }
        contextMenu.getItems().add(new SeparatorMenuItem());
        MenuItem flagItem = new MenuItem(myFlag ? "Unflag Message" : "Flag Message");
        flagItem.setOnAction(e -> toggleFlag(messageId));
        contextMenu.getItems().add(flagItem);

        bubble.setOnContextMenuRequested(e -> contextMenu.show(bubble, e.getScreenX(), e.getScreenY()));

        return new VBox(row);
    }

    // NEW
    private void toggleLike(long messageId) {
        postAction("http://127.0.0.1:8000/api/desktop/messages/" + messageId + "/like", null);
    }

    // NEW
    private void toggleReaction(long messageId, String emoji) {
        String json = mapper.createObjectNode().put("emoji", emoji).toString();
        postAction("http://127.0.0.1:8000/api/desktop/messages/" + messageId + "/react", json);
    }

    // NEW
    private void toggleFlag(long messageId) {
        postAction("http://127.0.0.1:8000/api/desktop/messages/" + messageId + "/flag", null);
    }

    // NEW: shared helper — every action just re-fetches afterward rather than patching local state,
// which also means a message that gets auto-hidden after a flag threshold simply won't
// reappear in the next fetch, no special-case removal code needed
    private void postAction(String url, String jsonBodyOrNull) {
        try {
            HttpRequest.Builder builder = Session.authorizedRequestBuilder(url);
            if (jsonBodyOrNull != null) {
                builder.header("Content-Type", "application/json")
                    .POST(HttpRequest.BodyPublishers.ofString(jsonBodyOrNull));
            } else {
                builder.POST(HttpRequest.BodyPublishers.noBody());
            }

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(builder.build(), HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> Platform.runLater(this::fetchMessages));

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private String formatTime(String isoTimestamp) {
        if (isoTimestamp == null) return "";
        try {
            OffsetDateTime dt = OffsetDateTime.parse(isoTimestamp);
            return dt.atZoneSameInstant(java.time.ZoneId.systemDefault())
                .format(DateTimeFormatter.ofPattern("HH:mm"));
        } catch (Exception e) {
            return isoTimestamp;
        }
    }

    @FXML
    void handleSend(ActionEvent event) {
        String text = messageInput.getText().trim();
        if (text.isEmpty() || topicId == null) return;

        try {
            String json = mapper.createObjectNode()
                .put("body", text)
                .toString();

            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/topics/" + topicId + "/messages")
                .header("Content-Type", "application/json")
                .POST(HttpRequest.BodyPublishers.ofString(json))
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> Platform.runLater(() -> {
                    messageInput.clear();
                    fetchMessages();
                }));

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @FXML
    void handleBack(ActionEvent event) {
        stopPolling();
        String returnView = AppState.getReturnView();
        StudentDashboardController.navigateTo(returnView != null ? returnView : "discussions_view.fxml");
    }
}

