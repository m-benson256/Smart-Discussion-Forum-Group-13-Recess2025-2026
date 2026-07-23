package org.example;

import java.net.http.HttpClient;
import java.net.http.HttpResponse;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;

public class LecturerAnnouncementsController {

    @FXML private VBox announcementListContainer;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    public void initialize() {
        loadAnnouncements();
    }

    private void loadAnnouncements() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(
                "http://127.0.0.1:8000/api/desktop/announcements")
                .GET()
                .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            List<Announcement> announcements =
                                    List.of(mapper.readValue(response.body(), Announcement[].class));
                            renderList(announcements);
                        } else {
                            System.err.println("Failed to load announcements: " + response.statusCode());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                })
        );
    }

    private void renderList(List<Announcement> announcements) {
        announcementListContainer.getChildren().clear();

        if (announcements.isEmpty()) {
            Label empty = new Label("No announcements yet.");
            empty.getStyleClass().add("muted-label");
            announcementListContainer.getChildren().add(empty);
            return;
        }

        for (Announcement a : announcements) {
            announcementListContainer.getChildren().add(buildCard(a));
        }
    }

    private VBox buildCard(Announcement a) {
        VBox card = new VBox(6);
        card.getStyleClass().add("card");
        card.setPadding(new Insets(16));

        String quizTitle = a.getQuiz() != null ? a.getQuiz().getTitle() : "Unknown Quiz";
        Label title = new Label(quizTitle);
        title.getStyleClass().add("card-title");

        Label content = new Label(a.getContent());
        content.setWrapText(true);

        Label meta = new Label(a.getCreatedAt() != null ? a.getCreatedAt() : "");
        meta.getStyleClass().add("muted-label");

        card.getChildren().addAll(title, content, meta);
        return card;
    }
}