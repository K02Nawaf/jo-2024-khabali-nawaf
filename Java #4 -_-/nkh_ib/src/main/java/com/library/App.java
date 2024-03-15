package com.library;
import com.library.pages.Adherants.AdherantPage;
import com.library.pages.Auteur.AuteurPage;
import com.library.pages.Emprunt.EmpruntPage;
import com.library.pages.Livre.BooksPage;

import javafx.application.Application;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;

public class App extends Application {
    private Stage primaryStage;
    private Scene homeScene;
    private Scene booksScene;
    private Scene adherantScene;
    private Scene empruntScene;
    private Scene auteurScene;

    @Override
    public void start(Stage primaryStage) {
        this.primaryStage = primaryStage;

        // Create buttons for navigation
        Button booksButton = new Button("Livre");
        Button adherantButton = new Button("Adherant");
        Button empruntButton = new Button("Emprunt");
        Button auteurButton = new Button("Auteur");

        // Event handlers for button clicks
        booksButton.setOnAction(event -> primaryStage.setScene(booksScene));
        adherantButton.setOnAction(event -> primaryStage.setScene(adherantScene));
        empruntButton.setOnAction(event -> primaryStage.setScene(empruntScene));
        auteurButton.setOnAction(event -> primaryStage.setScene(auteurScene));

        // Add buttons to a vertical layout
        VBox root = new VBox(10);
        root.getChildren().addAll(booksButton, adherantButton, empruntButton, auteurButton);

        // Set up the home scene
        homeScene = new Scene(root, 300, 200);

        // Set up scenes for other pages
        booksScene = new Scene(new BooksPage().getContent(), 600, 400);
        adherantScene = new Scene(new AdherantPage().getContent(), 600, 400);
        empruntScene = new Scene(new EmpruntPage().getContent(), 600, 400);
        auteurScene = new Scene((Parent) new AuteurPage().getContent(), 600, 400);

        // Set up the primary stage
        primaryStage.setScene(homeScene);
        primaryStage.setTitle("Library Management");
        primaryStage.show();
    }

    public static void main(String[] args) {
        launch(args);
    }
}
