package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.StackPane;
import javafx.scene.layout.VBox;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.OffsetDateTime;
import java.time.format.DateTimeFormatter;

public class AnnouncementsController {

    @FXML private VBox announcementsContainer;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        fetchAnnouncements();
    }

    private void fetchAnnouncements() {
        try {
            HttpRequest request = Session.authorizedRequestBuilder(
                    "http://127.0.0.1:8000/api/desktop/announcements")
                .GET()
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::renderAnnouncements);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void renderAnnouncements(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode announcements = mapper.readTree(response.body());
                announcementsContainer.getChildren().clear();

                boolean any = false;
                for (JsonNode a : announcements) {
                    any = true;
                    announcementsContainer.getChildren().add(buildAnnouncementCard(a));
                }

                if (!any) {
                    Label empty = new Label("No announcements yet.");
                    empty.getStyleClass().add("muted-label");
                    VBox emptyBox = new VBox(empty);
                    emptyBox.getStyleClass().add("empty-state");
                    announcementsContainer.getChildren().add(emptyBox);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    private VBox buildAnnouncementCard(JsonNode a) {
        String lecturerName = (a.has("user") && !a.get("user").isNull() && a.get("user").has("name"))
            ? a.get("user").get("name").asText() : "Unknown Lecturer";
        String content = a.has("content") && !a.get("content").isNull() ? a.get("content").asText() : "";
        String time = formatTime(a.has("created_at") ? a.get("created_at").asText() : null);

        Label initial = new Label(lecturerName.isEmpty() ? "U" : lecturerName.substring(0, 1).toUpperCase());
        StackPane avatar = new StackPane(initial);
        avatar.getStyleClass().add("avatar-circle-large");

        Label nameLabel = new Label(lecturerName);
        nameLabel.getStyleClass().add("card-title");
        Label timeLabel = new Label(time);
        timeLabel.getStyleClass().add("muted-label");
        VBox nameTime = new VBox(2, nameLabel, timeLabel);

        HBox header = new HBox(10, avatar, nameTime);

        Label contentLabel = new Label(content);
        contentLabel.setWrapText(true);
        contentLabel.setStyle("-fx-text-fill: #334155; -fx-font-size: 13px;");

        VBox card = new VBox(10, header, contentLabel);

        if (a.has("quiz") && !a.get("quiz").isNull()) {
            String quizTitle = a.get("quiz").get("title").asText();
            Label quizLabel = new Label("Related quiz: " + quizTitle);
            quizLabel.setStyle("-fx-text-fill: #2563eb; -fx-font-size: 11px; -fx-font-weight: bold;");
            card.getChildren().add(quizLabel);
        }

        card.getStyleClass().add("card");
        card.setPadding(new Insets(18));

        return card;
    }

    private String formatTime(String isoTimestamp) {
        if (isoTimestamp == null) return "";
        try {
            OffsetDateTime dt = OffsetDateTime.parse(isoTimestamp);
            return dt.atZoneSameInstant(java.time.ZoneId.systemDefault())
                .format(DateTimeFormatter.ofPattern("MMM d, yyyy • HH:mm"));
        } catch (Exception e) {
            return isoTimestamp;
        }
    }
}
