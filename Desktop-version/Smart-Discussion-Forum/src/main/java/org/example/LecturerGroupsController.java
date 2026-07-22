package org.example;

import com.fasterxml.jackson.databind.ObjectMapper;
import javafx.application.Platform;
import javafx.beans.property.SimpleStringProperty;
import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;

import java.net.http.HttpClient;
import java.net.http.HttpResponse;
import java.util.List;

public class LecturerGroupsController {

    @FXML private TableView<GroupRow> groupsTable;
    @FXML private TableColumn<GroupRow, String> nameColumn;
    @FXML private TableColumn<GroupRow, String> creatorColumn;
    @FXML private TableColumn<GroupRow, String> membersColumn;
    @FXML private Label groupCountLabel;

    private final ObjectMapper mapper = new ObjectMapper();
    private static final String BASE_URL = "http://127.0.0.1:8000/api";

    @FXML
    public void initialize() {
        nameColumn.setCellValueFactory(new PropertyValueFactory<>("name"));
        creatorColumn.setCellValueFactory(new PropertyValueFactory<>("creatorName"));
        membersColumn.setCellValueFactory(new PropertyValueFactory<>("membersLabel"));

        loadGroups();
    }

    private void loadGroups() {
        HttpClient client = HttpClient.newHttpClient();
        var request = Session.authorizedRequestBuilder(BASE_URL + "/desktop/groups").GET().build();

        client.sendAsync(request, HttpResponse.BodyHandlers.ofString()).thenAccept(response ->
                Platform.runLater(() -> {
                    try {
                        if (response.statusCode() == 200) {
                            List<Group> groups = List.of(mapper.readValue(response.body(), Group[].class));
                            renderTable(groups);
                        } else {
                            System.err.println("Failed to load groups: " + response.statusCode());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                })
        );
    }

    private void renderTable(List<Group> groups) {
        groupCountLabel.setText(groups.size() + " groups");

        List<GroupRow> rows = groups.stream().map(g -> new GroupRow(
                g.getName(),
                g.getCreator() != null ? g.getCreator().getName() : "Unknown",
                (g.getMembersCount() == null ? 0 : g.getMembersCount())
                        + (g.getMembersCount() != null && g.getMembersCount() == 1 ? " member" : " members")
        )).toList();

        groupsTable.getItems().setAll(rows);
    }

    // Simple row model for the TableView
    public static class GroupRow {
        private final SimpleStringProperty name;
        private final SimpleStringProperty creatorName;
        private final SimpleStringProperty membersLabel;

        public GroupRow(String name, String creatorName, String membersLabel) {
            this.name = new SimpleStringProperty(name);
            this.creatorName = new SimpleStringProperty(creatorName);
            this.membersLabel = new SimpleStringProperty(membersLabel);
        }

        public String getName() { return name.get(); }
        public String getCreatorName() { return creatorName.get(); }
        public String getMembersLabel() { return membersLabel.get(); }
    }
}