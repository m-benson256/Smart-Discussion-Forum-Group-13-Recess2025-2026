package org.example;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class App extends Application {

    private static Stage primaryStage;

    @Override
    public void start(Stage stage) throws Exception {
        primaryStage = stage;
        primaryStage.setTitle("Smart Discussion Forum");
        switchScene("register_view.fxml", 900, 700);
        primaryStage.show();
    }

    public static void switchScene(String fxmlFile, double width, double height) {
        try {
            FXMLLoader loader = new FXMLLoader(App.class.getResource(fxmlFile));
            Parent root = loader.load();
            Scene scene = new Scene(root, width, height);
            primaryStage.setScene(scene);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void main(String[] args) {
        launch(args);
    }
}
