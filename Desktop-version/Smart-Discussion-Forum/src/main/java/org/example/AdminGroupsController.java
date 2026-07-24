
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

public class AdminGroupsController implements Initializable {

    private static final String GROUPS_URL = "http://127.0.0.1:8000/api/desktop/admin/groups";

    @FXML private TableView<GroupRow> groupsTable;
    @FXML private TableColumn<GroupRow, String> nameColumn;
    @FXML private TableColumn<GroupRow, String> descriptionColumn;
    @FXML private TableColumn<GroupRow, String> visibilityColumn;
    @FXML private TableColumn<GroupRow, String> creatorColumn;
    @FXML private TableColumn<GroupRow, Number> memberCountColumn;

    private final ObjectMapper mapper = new ObjectMapper();
    private final HttpClient client = HttpClient.newHttpClient();

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        nameColumn.setCellValueFactory(new PropertyValueFactory<>("name"));
        descriptionColumn.setCellValueFactory(new PropertyValueFactory<>("description"));
        visibilityColumn.setCellValueFactory(new PropertyValueFactory<>("visibility"));
        creatorColumn.setCellValueFactory(new PropertyValueFactory<>("creatorName"));
        memberCountColumn.setCellValueFactory(new PropertyValueFactory<>("memberCount"));

        loadGroups();
    }

    private void loadGroups() {
        HttpRequest request = Session.authorizedRequestBuilder(GROUPS_URL)
            .GET()
            .build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString())
            .thenAccept(this::handleGroupsResponse)
            .exceptionally(ex -> {
                Platform.runLater(() -> System.out.println("Failed to load groups: " + ex.getMessage()));
                return null;
            });
    }

    private void handleGroupsResponse(HttpResponse<String> response) {
        Platform.runLater(() -> {
            try {
                if (response.statusCode() != 200) {
                    System.out.println("Groups fetch failed: " + response.statusCode());
                    return;
                }

                JsonNode groups = mapper.readTree(response.body());
                ObservableList<GroupRow> rows = FXCollections.observableArrayList();

                for (JsonNode g : groups) {
                    String name = g.get("name").asText();
                    String description = g.has("description") && !g.get("description").isNull()
                        ? g.get("description").asText() : "";
                    String visibility = g.get("visibility").asText();
                    String creatorName = g.has("creator") && !g.get("creator").isNull()
                        ? g.get("creator").get("name").asText() : "Unknown";
                    int memberCount = g.get("members_count").asInt();

                    rows.add(new GroupRow(name, description, visibility, creatorName, memberCount));
                }

                groupsTable.setItems(rows);

            } catch (Exception e) {
                System.out.println("Error parsing groups: " + e.getMessage());
            }
        });
    }

    public static class GroupRow {
        private final String name;
        private final String description;
        private final String visibility;
        private final String creatorName;
        private final int memberCount;

        public GroupRow(String name, String description, String visibility, String creatorName, int memberCount) {
            this.name = name;
            this.description = description;
            this.visibility = visibility;
            this.creatorName = creatorName;
            this.memberCount = memberCount;
        }

        public String getName() { return name; }
        public String getDescription() { return description; }
        public String getVisibility() { return visibility; }
        public String getCreatorName() { return creatorName; }
        public int getMemberCount() { return memberCount; }
    }
}
