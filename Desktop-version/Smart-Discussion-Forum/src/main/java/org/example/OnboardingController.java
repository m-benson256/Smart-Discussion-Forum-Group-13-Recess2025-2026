package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.CheckBox;
import javafx.scene.control.Label;

import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.ArrayList;
import java.util.List;

public class OnboardingController {

    @FXML private CheckBox mlCheck, webCheck, dbCheck, mobileCheck, securityCheck,
        designCheck, cloudCheck, dataCheck, aiCheck;
    @FXML private Label statusLabel;

    private final ObjectMapper mapper = new ObjectMapper();
    // Reuse one single client instance for performance and stability
    private final HttpClient httpClient = HttpClient.newHttpClient();

    @FXML
    void handleContinue(ActionEvent event) {
        List<CheckBox> allChecks = List.of(
            mlCheck, webCheck, dbCheck, mobileCheck, securityCheck,
            designCheck, cloudCheck, dataCheck, aiCheck
        );

        List<String> selected = new ArrayList<>();
        for (CheckBox cb : allChecks) {
            if (cb != null && cb.isSelected()) {
                // Fix: Safely fall back to the text label of the checkbox if user data is missing
                String value = (cb.getUserData() != null) ? (String) cb.getUserData() : cb.getText();
                selected.add(value);
            }
        }

        statusLabel.setStyle("-fx-text-fill: #4f46e5;");
        statusLabel.setText("Saving your preferences...");

        try {
            OnboardingPayload payload = new OnboardingPayload(selected);
            String jsonPayload = mapper.writeValueAsString(payload);

            HttpRequest request = Session.authorizedRequestBuilder("http://127.0.0.1:8000/api/desktop/onboarding")
                .POST(HttpRequest.BodyPublishers.ofString(jsonPayload))
                .build();

            // Fix: Added error handling to catch network failures cleanly
            httpClient.sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleResponse)
                .exceptionally(ex -> {
                    Platform.runLater(() -> {
                        ex.printStackTrace(); // View the exact system breakdown in your console log
                        statusLabel.setStyle("-fx-text-fill: #ff3333;");
                        statusLabel.setText("Network error. Is your Laravel server running?");
                    });
                    return null;
                });

        } catch (Exception e) {
            statusLabel.setStyle("-fx-text-fill: #ff3333;");
            statusLabel.setText("Something went wrong. Please try again.");
        }
    }

    private void handleResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                System.out.println("Server Response: " + response.body()); // Debug trace log
                JsonNode body = mapper.readTree(response.body());

                if (response.statusCode() == 200 || response.statusCode() == 201) {
                    String destination = body.has("next_destination") ? body.get("next_destination").asText() : "";

                    if ("lecturer_dashboard".equals(destination)) {
                        App.switchScene("lecturer_dashboard_view.fxml", 1100, 750);
                    } else if ("student_dashboard".equals(destination)) {
                        App.switchScene("student_dashboard_view.fxml", 1100, 750);
                    } else {
                        // Fix: If registration finishes onboarding, explicitly route them back out to login view
                        App.switchScene("login_view.fxml", 800, 600);
                    }
                } else {
                    statusLabel.setStyle("-fx-text-fill: #ff3333;");
                    statusLabel.setText("Could not save preferences. Status: " + response.statusCode());
                }
            } catch (Exception e) {
                statusLabel.setStyle("-fx-text-fill: #ff3333;");
                statusLabel.setText("Could not process server response.");
            }
        });
    }

    private static class OnboardingPayload {
        public List<String> interests;
        OnboardingPayload(List<String> interests) { this.interests = interests; }
    }
}
