package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.control.PasswordField;
import javafx.scene.control.TextField;
import javafx.scene.input.MouseEvent;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class LoginController {

    @FXML private TextField emailField;
    @FXML private PasswordField passwordField;
    @FXML private Label statusLabel;

    private final ObjectMapper mapper = new ObjectMapper();

    @FXML
    void handleLoginSubmit(ActionEvent event) {
        String email = emailField.getText().trim();
        String password = passwordField.getText();

        if (email.isEmpty() || password.isEmpty()) {
            statusLabel.setStyle("-fx-text-fill: #ff3333;");
            statusLabel.setText("Please enter both email and password.");
            return;
        }

        statusLabel.setStyle("-fx-text-fill: #4f46e5;");
        statusLabel.setText("Signing in...");

        try {
            String jsonPayload = mapper.writeValueAsString(new LoginPayload(email, password));

            HttpClient client = HttpClient.newHttpClient();
            HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create("http://127.0.0.1:8000/api/desktop/login"))
                .header("Content-Type", "application/json")
                .header("Accept", "application/json")
                .POST(HttpRequest.BodyPublishers.ofString(jsonPayload))
                .build();

            client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleResponse);

        } catch (Exception e) {
            Platform.runLater(() -> {
                statusLabel.setStyle("-fx-text-fill: #ff3333;");
                statusLabel.setText("Something went wrong. Please try again.");
            });
        }
    }

    private void handleResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                JsonNode body = mapper.readTree(response.body());

                if (response.statusCode() == 200) {
                    String token = body.get("token").asText();
                    JsonNode user = body.get("user");

                    Session.set(
                        token,
                        user.get("name").asText(),
                        user.get("email").asText(),
                        user.get("role").asText()
                    );

                    statusLabel.setStyle("-fx-text-fill: #10b981;");
                    statusLabel.setText("Signed in! Loading dashboard...");

                    if ("lecturer".equals(user.get("role").asText())) {
                        App.switchScene("lecturer_dashboard_view.fxml", 1100, 750);
                    } else {
                        App.switchScene("student_dashboard_view.fxml", 1100, 750);
                    }

                } else {
                    String message = body.has("message") ? body.get("message").asText() : "Login failed.";
                    statusLabel.setStyle("-fx-text-fill: #ff3333;");
                    statusLabel.setText(message);
                }
            } catch (Exception e) {
                statusLabel.setStyle("-fx-text-fill: #ff3333;");
                statusLabel.setText("Could not process server response.");
            }
        });
    }

    @FXML
    void goToRegister(MouseEvent event) {
        App.switchScene("register_view.fxml", 900, 700);
    }

    // Small helper class just to serialize the login request body cleanly
    private static class LoginPayload {
        public String email;
        public String password;

        LoginPayload(String email, String password) {
            this.email = email;
            this.password = password;
        }
    }
}
