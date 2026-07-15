package org.example;

import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.input.KeyEvent;
import javafx.scene.layout.VBox;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class RegisterController {

    @FXML private TextField nameField;
    @FXML private TextField emailField;
    @FXML private PasswordField passwordField;
    @FXML private PasswordField confirmPasswordField;
    @FXML private CheckBox termsCheckBox;
    @FXML private Label statusLabel;

    // Dynamic Role Containers
    @FXML private VBox studentFieldsContainer;
    @FXML private ComboBox<String> studentCategoryDropdown;

    @FXML private VBox lecturerFieldsContainer;
    @FXML private ComboBox<String> degreeTypeDropdown;
    @FXML private TextField deskContactField;

    // Mirrors the toggleRoleFields JavaScript function
    @FXML
    void handleEmailKeyReleased(KeyEvent event) {
        String email = emailField.getText().toLowerCase().trim();

        boolean isStudent = email.contains("@students.");
        boolean isLecturer = email.contains("@lecturers.");

        // Student Visibility Block
        studentFieldsContainer.setVisible(isStudent);
        studentFieldsContainer.setManaged(isStudent);
        if (!isStudent) studentCategoryDropdown.setValue(null);

        // Lecturer Visibility Block
        lecturerFieldsContainer.setVisible(isLecturer);
        lecturerFieldsContainer.setManaged(isLecturer);
        if (!isLecturer) {
            degreeTypeDropdown.setValue(null);
            deskContactField.clear();
        }
    }

    // Handles the network submit request to Laravel API
    @FXML
    void handleRegistrationSubmit(ActionEvent event) {
        if (!termsCheckBox.isSelected()) {
            statusLabel.setStyle("-fx-text-fill: #ff3333;");
            statusLabel.setText("You must agree to the forum rules.");
            return;
        }
        if (!passwordField.getText().equals(confirmPasswordField.getText())) {
            statusLabel.setStyle("-fx-text-fill: #ff3333;");
            statusLabel.setText("Passwords do not match.");
            return;
        }

        String category = studentCategoryDropdown.getValue() != null ? studentCategoryDropdown.getValue() : "";
        String degreeType = degreeTypeDropdown.getValue() != null ? degreeTypeDropdown.getValue() : "";
        String deskContact = deskContactField.getText() != null ? deskContactField.getText() : "";

        //  CORRECTED CODE (Keys updated to match Laravel validator):
        String jsonPayload = String.format(
            "{\"name\":\"%s\",\"email\":\"%s\",\"password\":\"%s\",\"password_confirmation\":\"%s\",\"academic_category\":\"%s\",\"degree_program\":\"%s\",\"desk_contact_number\":\"%s\"}",
            nameField.getText(),
            emailField.getText(),
            passwordField.getText(),
            confirmPasswordField.getText(),
            category,
            degreeType,
            deskContact
        );



        HttpClient client = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
            .uri(URI.create("http://127.0.0.1:8000/api/desktop/register"))
            .header("Content-Type", "application/json")
            .header("Accept", "application/json")
            .POST(HttpRequest.BodyPublishers.ofString(jsonPayload))
            .build();

        statusLabel.setStyle("-fx-text-fill: #4f46e5;");
        statusLabel.setText("Processing registration...");

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
            .thenAccept(response -> {
                Platform.runLater(() -> {
                    if (response.statusCode() == 201) {
                        statusLabel.setStyle("-fx-text-fill: #10b981;"); // emerald green
                        statusLabel.setText("Registered! Forwarding...");
                        System.out.println("Registration Successful: " + response.body());
                    } else {
                        System.out.println("Server Error: " + response.body());
                        statusLabel.setStyle("-fx-text-fill: #ff3333;");
                        statusLabel.setText("Registration failed. Please check inputs.");
                    }
                });
            });
    }
}
