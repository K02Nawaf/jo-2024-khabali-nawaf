package com.library.pages.Emprunt;

import javafx.scene.Parent;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;

public class EmpruntPage {
    public Parent getContent() {
        VBox layout = new VBox(10);
        layout.getChildren().add(new Label("Emprunt Page"));
        // Add more UI components as needed
        return layout;
    }
}
