package com.library.pages.Adherants;

import javafx.scene.Parent;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;

public class AdherantPage {
    public Parent getContent() {
        VBox layout = new VBox(10);
        layout.getChildren().add(new Label("Adherant Page"));
        // Add more UI components as needed
        return layout;
    }
}
