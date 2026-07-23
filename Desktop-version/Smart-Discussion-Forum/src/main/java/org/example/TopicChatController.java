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
import javafx.scene.layout.StackPane;
import javafx.scene.Cursor;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

import javafx.scene.control.Separator;
import javafx.scene.layout.GridPane;
import javafx.stage.Popup;

import javafx.scene.layout.Region;
import javafx.scene.text.TextFlow;

import javafx.stage.FileChooser;
import java.io.File;
import java.nio.file.Path;

import javafx.scene.Node;
import javafx.scene.image.ImageView; // not strictly needed here since EmojiImages handles it, but harmless if left out

// NEW: lets StudentDashboardController stop our polling before swapping views away
public class TopicChatController implements PollingView {

    @FXML private Label topicTitleLabel;
    @FXML private VBox messagesContainer;
    @FXML private ScrollPane scrollPane;
    @FXML private TextField messageInput;

    private final ObjectMapper mapper = new ObjectMapper();
    private Timeline pollingTimeline;
    private Long topicId;
    private String lastMessagesJson;

    @FXML private Button attachButton; // NEW
    @FXML private Button emojiButton;  // NEW

    private static final String[] QUICK_EMOJIS = { // NEW — shared list, used by both popups below
        "😀","😃","😄","😁","😆",
        "😂","🤣","😊","😍","😘",
        "🙂","😉","😎","🤔","😐",
        "👍","❤️","😢","😮","😡"
    };

    @FXML
    public void initialize() {
        topicId = AppState.getSelectedTopicId();
        String title = AppState.getSelectedTopicTitle();
        topicTitleLabel.setText(title != null ? title : "Discussion");

        attachButton.setGraphic(EmojiImages.buildNode("📎", 18)); // NEW
        emojiButton.setGraphic(EmojiImages.buildNode("😊", 18));  // NEW

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
        fetchMessages(true); // polling / initial load: jump to bottom only if you were already caught up
    }

    private void fetchMessages(boolean allowAutoScroll) {
        if (topicId == null) return;

        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/topics/" + topicId + "/messages")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> renderMessages(response, allowAutoScroll));

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderMessages(HttpResponse<String> response, boolean allowAutoScroll) {
        Platform.runLater(() -> {
            try {
                String body = response.body();

                if (body.equals(lastMessagesJson)) {
                    return;
                }
                lastMessagesJson = body;

                JsonNode messages = mapper.readTree(body);

                System.out.println(body);

                double currentVvalue = scrollPane.getVvalue();
                boolean wasNearBottom = currentVvalue >= 0.98;

                messagesContainer.getChildren().clear();
                for (JsonNode msg : messages) {
                    messagesContainer.getChildren().add(buildMessageBubble(msg));
                }

                if (allowAutoScroll && wasNearBottom) {
                    Platform.runLater(() -> scrollPane.setVvalue(1.0));
                } else if (!allowAutoScroll) {
                    Platform.runLater(() -> scrollPane.setVvalue(currentVvalue));
                }

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

        // NEW: avatar circle with the sender's first initial
        Label initial = new Label(author.isEmpty() ? "?" : author.substring(0, 1).toUpperCase());
        StackPane avatar = new StackPane(initial);
        avatar.getStyleClass().add("avatar-circle");

        // NEW: bold name + grey timestamp, side by side
        Label nameLabel = new Label(author);
        nameLabel.getStyleClass().add("message-name");
        Label timeLabel = new Label(time);
        timeLabel.getStyleClass().add("message-time");
        HBox headerRow = new HBox(6, nameLabel, timeLabel);

        boolean hasAttachment = msg.has("attachment_url") && !msg.get("attachment_url").isNull();

        VBox contentBox = new VBox(6);
        contentBox.getStyleClass().add(isMe ? "bubble-me" : "bubble-them");
        contentBox.setPadding(new Insets(10, 14, 10, 14));
        contentBox.setMaxWidth(400);

        if (!text.isEmpty()) {
            TextFlow textFlow = EmojiImages.buildRichText(text, 16);
            textFlow.setMaxWidth(370);
            contentBox.getChildren().add(textFlow);
        }

        if (hasAttachment) {
            contentBox.getChildren().add(buildAttachmentNode(msg, isMe));
        }

        // CHANGED: like control is now a Label, not a Button
        int likeCount = msg.has("liked_by_count") ? msg.get("liked_by_count").asInt() : 0;
        boolean myLike = msg.has("liked_by_me") && msg.get("liked_by_me").asBoolean();

        Node likeIcon = EmojiImages.buildNode(myLike ? "❤️" : "🤍", 14);
        Label likeCountLabel = new Label(String.valueOf(likeCount));
        likeCountLabel.getStyleClass().add("like-control");

        HBox likeControl = new HBox(4, likeIcon, likeCountLabel);
        likeControl.setAlignment(Pos.CENTER_LEFT);
        likeControl.setCursor(Cursor.HAND);
        likeControl.setOnMouseClicked(e -> toggleLike(messageId));

        // CHANGED: reaction badges are now Labels
        HBox reactionsRow = new HBox(4);
        if (msg.has("grouped_reactions")) {
            for (JsonNode r : msg.get("grouped_reactions")) {
                String emoji = r.get("emoji").asText();
                int count = r.get("count").asInt();

                Node icon = EmojiImages.buildNode(emoji, 14);
                Label countLabel = new Label(String.valueOf(count));

                HBox badge = new HBox(3, icon, countLabel);
                badge.setAlignment(Pos.CENTER);
                badge.getStyleClass().add(r.has("me") && r.get("me").asBoolean() ? "reaction-badge-active" : "reaction-badge");
                badge.setCursor(Cursor.HAND);
                badge.setOnMouseClicked(e -> toggleReaction(messageId, emoji));
                reactionsRow.getChildren().add(badge);
            }
        }

        boolean myFlag = msg.has("flagged_by_me") && msg.get("flagged_by_me").asBoolean();
        HBox actionsRow = new HBox(10, likeControl, reactionsRow);
        actionsRow.setAlignment(Pos.CENTER_LEFT);
        if (myFlag) {
            Node flagIcon = EmojiImages.buildNode("⚠️", 14); // CHANGED — was a plain Label
            Label flagText = new Label("flagged");
            flagText.getStyleClass().add("muted-label");

            HBox flagBadge = new HBox(4, flagIcon, flagText);
            flagBadge.setAlignment(Pos.CENTER_LEFT);

            actionsRow.getChildren().add(flagBadge);
        }

        VBox column = new VBox(4, headerRow, contentBox, actionsRow);
        column.setMaxWidth(420);

        // NEW: mirror alignment onto the header/actions rows too, so text hugs the right edge for my own messages
        if (isMe) {
            headerRow.setAlignment(Pos.CENTER_RIGHT);
            actionsRow.setAlignment(Pos.CENTER_RIGHT);
        }

        HBox row = new HBox(10);
        row.setAlignment(isMe ? Pos.TOP_RIGHT : Pos.TOP_LEFT);
        if (isMe) {
            row.getChildren().add(column); // no avatar for your own messages — matches most chat apps' convention
        } else {
            row.getChildren().addAll(avatar, column);
        }

        // Context menu (right-click) — unchanged from before



        contentBox.setOnContextMenuRequested(e -> showReactionPopup(contentBox, messageId, myFlag, e.getScreenX(), e.getScreenY()));

        return new VBox(row);
    }

    // NEW: replaces the old ContextMenu — gives a proper grid layout for quick emoji, like the web version
     private void showReactionPopup(Region bubble, long messageId, boolean myFlag, double screenX, double screenY) {
        Popup popup = new Popup();
        popup.setAutoHide(true);

        VBox container = new VBox(8);
        container.setPadding(new Insets(12));
        container.getStyleClass().add("emoji-popup");

        Label header = new Label("QUICK EMOJI");
        header.getStyleClass().add("emoji-popup-header");

        GridPane grid = buildEmojiGrid(emoji -> {
            toggleReaction(messageId, emoji);
            popup.hide();
        });

        Separator separator = new Separator();

        Node flagIcon = EmojiImages.buildNode("⚠️", 14);
        Label flagText = new Label(myFlag ? "Unflag Message" : "Flag Message");
        flagText.getStyleClass().add("flag-option");

        HBox flagOption = new HBox(6, flagIcon, flagText);
        flagOption.setAlignment(Pos.CENTER_LEFT);
        flagOption.setCursor(Cursor.HAND);
        flagOption.setOnMouseClicked(e -> {
            toggleFlag(messageId);
            popup.hide();
        });


        container.getChildren().addAll(header, grid, separator, flagOption);
        popup.getContent().add(container);
        popup.show(bubble, screenX, screenY);
    }

    // NEW: shared by the reaction popup and the input-bar emoji picker
    private GridPane buildEmojiGrid(java.util.function.Consumer<String> onPick) {
        GridPane grid = new GridPane();
        grid.setHgap(6);
        grid.setVgap(6);
        int columns = 5;
        for (int i = 0; i < QUICK_EMOJIS.length; i++) {
            String emoji = QUICK_EMOJIS[i];
            Node emojiNode = EmojiImages.buildNode(emoji, 22);
            StackPane cell = new StackPane(emojiNode);
            cell.getStyleClass().add("emoji-option");
            cell.setOnMouseClicked(e -> onPick.accept(emoji));
            grid.add(cell, i % columns, i / columns);
        }
        return grid;
    }

    @FXML
    void handleEmojiButton(ActionEvent event) {
        Popup popup = new Popup();
        popup.setAutoHide(true);

        VBox container = new VBox(8);
        container.setPadding(new Insets(12));
        container.getStyleClass().add("emoji-popup");

        Label header = new Label("QUICK EMOJI");
        header.getStyleClass().add("emoji-popup-header");

        GridPane grid = buildEmojiGrid(emoji -> {
            insertEmojiIntoInput(emoji);
            popup.hide();
        });

        container.getChildren().addAll(header, grid);
        popup.getContent().add(container);

        var bounds = emojiButton.localToScreen(emojiButton.getBoundsInLocal());
        popup.show(emojiButton, bounds.getMinX(), bounds.getMinY() - 210); // shows above the input bar, not below
    }

    private void insertEmojiIntoInput(String emoji) {
        int caret = messageInput.getCaretPosition();
        String text = messageInput.getText();
        messageInput.setText(text.substring(0, caret) + emoji + text.substring(caret));
        messageInput.positionCaret(caret + emoji.length());
        messageInput.requestFocus();
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
                .thenAccept(response -> Platform.runLater(() -> fetchMessages(false)));

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
    void handleAttach(ActionEvent event) {
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Select a file to send");
        File file = fileChooser.showOpenDialog(attachButton.getScene().getWindow());
        if (file == null) return;

        uploadAttachment(file.toPath());
    }

    private void uploadAttachment(Path filePath) {
        if (topicId == null) return;

        try {
            MultipartBodyBuilder multipart = new MultipartBodyBuilder();
            multipart.addFile("attachment", filePath);

            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/topics/" + topicId + "/messages")
                .setHeader("Content-Type", "multipart/form-data; boundary=" + multipart.getBoundary()) // CHANGED — was .header(...)
                .POST(multipart.build())
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(response -> Platform.runLater(this::fetchMessages));

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


    private Node buildAttachmentNode(JsonNode msg, boolean isMe) {
        String url = resolveAttachmentUrl(msg.get("attachment_url").asText());
        String name = msg.has("attachment_name") && !msg.get("attachment_name").isNull()
            ? msg.get("attachment_name").asText() : "file";
        String mime = msg.has("attachment_mime") && !msg.get("attachment_mime").isNull()
            ? msg.get("attachment_mime").asText() : "";

        if (mime.startsWith("image/")) {
            ImageView preview = new ImageView(new Image(url, 220, 220, true, true, true));
            preview.setPreserveRatio(true);
            preview.setFitWidth(220);
            preview.getStyleClass().add("attachment-preview");
            preview.setCursor(Cursor.HAND);
            preview.setOnMouseClicked(e -> openAttachment(url));
            return preview;
        }

        Node icon = EmojiImages.buildNode("📄", 18);
        Label nameLabel = new Label(name);
        nameLabel.setWrapText(true);
        nameLabel.getStyleClass().add(isMe ? "attachment-name-me" : "attachment-name-them");

        HBox chip = new HBox(8, icon, nameLabel);
        chip.setAlignment(Pos.CENTER_LEFT);
        chip.getStyleClass().add("attachment-chip");
        chip.setCursor(Cursor.HAND);
        chip.setOnMouseClicked(e -> openAttachment(url));
        return chip;
    }

    private String resolveAttachmentUrl(String raw) {
        return raw.startsWith("http") ? raw : "http://127.0.0.1:8000" + raw;
    }

    // Opens images/PDFs/docs in whatever the user's OS has set as the default handler —
// same idea as clicking a download link in a browser
    private void openAttachment(String url) {
        try {
            java.awt.Desktop.getDesktop().browse(new java.net.URI(url));
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

