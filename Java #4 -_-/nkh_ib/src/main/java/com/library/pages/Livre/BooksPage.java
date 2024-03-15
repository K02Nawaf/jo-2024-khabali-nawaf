package com.library.pages.Livre;

import javafx.scene.Parent;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;

public class BooksPage {
    public Parent getContent() {
        VBox layout = new VBox(10);
        layout.getChildren().add(new Label("Books Page"));
        // Add more UI components as needed
        return layout;
    }
}
