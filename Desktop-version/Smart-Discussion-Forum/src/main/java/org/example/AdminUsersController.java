package org.example;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TableCell;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.util.Callback;

import java.net.URL;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.ArrayList;
import java.util.List;
import java.util.ResourceBundle;

public class AdminUsersController implements Initializable {

    private static final String USERS_URL = "http://127.0.0.1:8000/api/desktop/admin/users";
    private static final String BLACKLIST_URL_FMT = "http://127.0.0.1:8000/api/desktop/admin/users/%d/blacklist";
    private static final String UNBLACKLIST_URL_FMT = "http://127.0.0.1:8000/api/desktop/admin/users/%d/unblacklist";

    @FXML private Label statusLabel;
    @FXML private TableView<AdminUserRow> usersTable;
    @FXML private TableColumn<AdminUserRow, String> nameCol;
    @FXML private TableColumn<AdminUserRow, String> emailCol;
    @FXML private TableColumn<AdminUserRow, String> roleCol;
    @FXML private TableColumn<AdminUserRow, Long> groupCol;
    @FXML private TableColumn<AdminUserRow, String> statusCol;
    @FXML private TableColumn<AdminUserRow, String> untilCol;
    @FXML private TableColumn<AdminUserRow, Void> actionCol;

    private final ObjectMapper mapper = new ObjectMapper();
    private final HttpClient client = HttpClient.newHttpClient();

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        nameCol.setCellValueFactory(new PropertyValueFactory<>("name"));
        emailCol.setCellValueFactory(new PropertyValueFactory<>("email"));
        roleCol.setCellValueFactory(new PropertyValueFactory<>("role"));
        groupCol.setCellValueFactory(new PropertyValueFactory<>("groupId"));
        statusCol.setCellValueFactory(new PropertyValueFactory<>("status"));
        untilCol.setCellValueFactory(new PropertyValueFactory<>("blacklistedUntil"));

        setupActionColumn();
        loadUsers();
    }

    private void loadUsers() {
        statusLabel.setText("Loading users...");

        HttpRequest request = Session.authorizedRequestBuilder(USERS_URL)
            .GET()
            .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
            .thenAccept(this::handleUsersResponse)
            .exceptionally(ex -> {
                Platform.runLater(() -> statusLabel.setText("Failed to load users: " + ex.getMessage()));
                return null;
            });
    }

    private void handleUsersResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                if (response.statusCode() != 200) {
                    statusLabel.setText("Server returned " + response.statusCode());
                    return;
                }

                JsonNode users = mapper.readTree(response.body());
                List<AdminUserRow> rows = new ArrayList<>();

                for (JsonNode user : users) {
                    long userId = user.get("id").asLong();
                    String name = user.get("name").asText();
                    String email = user.get("email").asText();
                    String role = user.get("role").asText();

                    JsonNode memberships = user.get("group_memberships");

                    if (memberships == null || memberships.isEmpty()) {
                        rows.add(new AdminUserRow(userId, name, email, role, null, null, "n/a", null));
                    } else {
                        for (JsonNode membership : memberships) {
                            long groupMemberId = membership.get("id").asLong();
                            long groupId = membership.get("group_id").asLong();
                            String status = membership.get("status").asText();
                            String blacklistedUntil = membership.hasNonNull("blacklisted_until")
                                ? membership.get("blacklisted_until").asText()
                                : null;

                            rows.add(new AdminUserRow(userId, name, email, role,
                                groupMemberId, groupId, status, blacklistedUntil));
                        }
                    }
                }

                ObservableList<AdminUserRow> data = FXCollections.observableArrayList(rows);
                usersTable.setItems(data);
                statusLabel.setText(rows.size() + " row(s) loaded.");

            } catch (Exception e) {
                statusLabel.setText("Could not parse server response.");
            }
        });
    }

    private void setupActionColumn() {
        actionCol.setCellFactory(new Callback<>() {
            @Override
            public TableCell<AdminUserRow, Void> call(TableColumn<AdminUserRow, Void> col) {
                return new TableCell<>() {
                    private final Button actionButton = new Button();

                    {
                        actionButton.setOnAction(e -> {
                            AdminUserRow row = getTableView().getItems().get(getIndex());
                            handleActionClick(row);
                        });
                    }

                    @Override
                    protected void updateItem(Void item, boolean empty) {
                        super.updateItem(item, empty);

                        if (empty) {
                            setGraphic(null);
                            return;
                        }

                        AdminUserRow row = getTableView().getItems().get(getIndex());

                        if (row.getGroupMemberId() == null) {
                            setGraphic(null);
                            return;
                        }

                        if ("blacklisted".equals(row.getStatus())) {
                            actionButton.setText("Unblacklist");
                        } else {
                            actionButton.setText("Blacklist");
                        }

                        setGraphic(actionButton);
                    }
                };
            }
        });
    }

    private void handleActionClick(AdminUserRow row) {
        boolean isCurrentlyBlacklisted = "blacklisted".equals(row.getStatus());
        String url = String.format(
            isCurrentlyBlacklisted ? UNBLACKLIST_URL_FMT : BLACKLIST_URL_FMT,
            row.getGroupMemberId()
        );

        statusLabel.setText((isCurrentlyBlacklisted ? "Unblacklisting " : "Blacklisting ") + row.getName() + "...");

        HttpRequest request = Session.authorizedRequestBuilder(url)
            .POST(HttpRequest.BodyPublishers.noBody())
            .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
            .thenAccept(response -> Platform.runLater(() -> {
                if (response.statusCode() == 200) {
                    statusLabel.setText("Done — refreshing list...");
                    loadUsers();
                } else {
                    statusLabel.setText("Action failed (" + response.statusCode() + ")");
                }
            }))
            .exceptionally(ex -> {
                Platform.runLater(() -> statusLabel.setText("Action failed: " + ex.getMessage()));
                return null;
            });
    }
}
