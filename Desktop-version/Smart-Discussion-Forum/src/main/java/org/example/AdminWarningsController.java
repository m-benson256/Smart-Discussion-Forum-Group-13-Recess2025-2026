package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;

import java.net.URL;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.ResourceBundle;

public class AdminWarningsController implements Initializable {

    private static final String WARNINGS_URL = "http://127.0.0.1:8000/api/desktop/admin/warnings";

    @FXML private TableView<WarningRow> warningsTable;
    @FXML private TableColumn<WarningRow, String> userNameColumn;
    @FXML private TableColumn<WarningRow, String> userEmailColumn;
    @FXML private TableColumn<WarningRow, Number> warningNumberColumn;
    @FXML private TableColumn<WarningRow, String> reasonColumn;
    @FXML private TableColumn<WarningRow, String> issuedAtColumn;
    @FXML private TableColumn<WarningRow, String> statusColumn;

    private final ObjectMapper mapper = new ObjectMapper();
    private final HttpClient client = HttpClient.newHttpClient();

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        userNameColumn.setCellValueFactory(new PropertyValueFactory<>("userName"));
        userEmailColumn.setCellValueFactory(new PropertyValueFactory<>("userEmail"));
        warningNumberColumn.setCellValueFactory(new PropertyValueFactory<>("warningNumber"));
        reasonColumn.setCellValueFactory(new PropertyValueFactory<>("reason"));
        issuedAtColumn.setCellValueFactory(new PropertyValueFactory<>("issuedAt"));
        statusColumn.setCellValueFactory(new PropertyValueFactory<>("status"));

        loadWarnings();
    }

    private void loadWarnings() {
        HttpRequest request = Session.authorizedRequestBuilder(WARNINGS_URL)
            .GET()
            .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
            .thenAccept(this::handleWarningsResponse)
            .exceptionally(ex -> {
                Platform.runLater(() -> System.out.println("Failed to load warnings: " + ex.getMessage()));
                return null;
            });
    }

    private void handleWarningsResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                if (response.statusCode() != 200) {
                    System.out.println("Warnings fetch failed: " + response.statusCode());
                    return;
                }

                JsonNode warnings = mapper.readTree(response.body());
                ObservableList<WarningRow> rows = FXCollections.observableArrayList();

                for (JsonNode w : warnings) {
                    String userName = w.has("user") && !w.get("user").isNull()
                        ? w.get("user").get("name").asText() : "Unknown";
                    String userEmail = w.has("user") && !w.get("user").isNull()
                        ? w.get("user").get("email").asText() : "";
                    int warningNumber = w.get("warning_number").asInt();
                    String reason = w.has("reason") && !w.get("reason").isNull()
                        ? w.get("reason").asText() : "";
                    String issuedAt = w.has("issued_at") && !w.get("issued_at").isNull()
                        ? w.get("issued_at").asText() : "";
                    String status = w.get("status").asText();

                    rows.add(new WarningRow(userName, userEmail, warningNumber, reason, issuedAt, status));
                }

                warningsTable.setItems(rows);

            } catch (Exception e) {
                System.out.println("Error parsing warnings: " + e.getMessage());
            }
        });
    }

    public static class WarningRow {
        private final String userName;
        private final String userEmail;
        private final int warningNumber;
        private final String reason;
        private final String issuedAt;
        private final String status;

        public WarningRow(String userName, String userEmail, int warningNumber, String reason, String issuedAt, String status) {
            this.userName = userName;
            this.userEmail = userEmail;
            this.warningNumber = warningNumber;
            this.reason = reason;
            this.issuedAt = issuedAt;
            this.status = status;
        }

        public String getUserName() { return userName; }
        public String getUserEmail() { return userEmail; }
        public int getWarningNumber() { return warningNumber; }
        public String getReason() { return reason; }
        public String getIssuedAt() { return issuedAt; }
        public String getStatus() { return status; }
    }
}
