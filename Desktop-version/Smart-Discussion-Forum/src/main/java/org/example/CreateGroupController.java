package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.stage.Stage;

import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.Map;

public class CreateGroupController {

    @FXML private TextField nameField;
    @FXML private TextArea descriptionField;
    @FXML private ComboBox<String> visibilityDropdown;
    @FXML private Label statusLabel;

    private final ObjectMapper mapper = new ObjectMapper();
    private Runnable onGroupCreated; // callback to refresh the groups list after success

    @FXML
    public void initialize() {
        visibilityDropdown.setItems(FXCollections.observableArrayList("public", "private"));
        visibilityDropdown.setValue("public");
    }

    public void setOnGroupCreated(Runnable callback) {
        this.onGroupCreated = callback;
    }

    @FXML
    void handleCreate(ActionEvent event) {
        String name = nameField.getText().trim();
        String description = descriptionField.getText().trim();
        String visibility = visibilityDropdown.getValue();

        if (name.isEmpty()) {
            statusLabel.setText("Group name is required.");
            return;
        }

        try {
            Map<String, String> payload = Map.of(
                "name", name,
                "description", description,
                "visibility", visibility
            );
            String jsonPayload = mapper.writeValueAsString(payload);

            HttpRequest request = Session.authorizedRequestBuilder("http://127.0.0.1:8000/api/desktop/groups")
                .POST(HttpRequest.BodyPublishers.ofString(jsonPayload))
                .build();

            java.net.http.HttpClient.newHttpClient()
                .sendAsync(request, HttpResponse.BodyHandlers.ofString())
                .thenAccept(this::handleResponse);

        } catch (Exception e) {
            statusLabel.setText("Something went wrong. Please try again.");
        }
    }

    private void handleResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            if (response.statusCode() == 201) {
                if (onGroupCreated != null) onGroupCreated.run();
                closeDialog();
            } else {
                statusLabel.setText("Could not create group. Please check your inputs.");
            }
        });
    }

    @FXML
    void handleCancel(ActionEvent event) {
        closeDialog();
    }

    private void closeDialog() {
        Stage stage = (Stage) nameField.getScene().getWindow();
        stage.close();
    }
}
