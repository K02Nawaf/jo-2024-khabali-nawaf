package com.library.pages.Auteur;
import javafx.beans.property.SimpleIntegerProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.scene.Node;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.layout.VBox;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import com.library.DBConnection;

public class AuteurPage {
    public Node getContent() {
        VBox layout = new VBox(10);

        // Create TableView
        TableView<Auteur> tableView = new TableView<>();
        TableColumn<Auteur, Integer> autNumColumn = new TableColumn<>("Aut_num");
        autNumColumn.setCellValueFactory(cellData -> new SimpleIntegerProperty(cellData.getValue().getAutNum()).asObject());
        TableColumn<Auteur, String> nomColumn = new TableColumn<>("Nom");
        nomColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNom()));
        TableColumn<Auteur, String> prenomColumn = new TableColumn<>("Prenom");
        prenomColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getPrenom()));
        TableColumn<Auteur, String> dateNaissanceColumn = new TableColumn<>("Date de naissance");
        dateNaissanceColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getDateNaissance()));
        TableColumn<Auteur, String> descriptionColumn = new TableColumn<>("Description");
        descriptionColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getDescription()));

        // Add columns to the TableView
        tableView.getColumns().addAll(autNumColumn, nomColumn, prenomColumn, dateNaissanceColumn, descriptionColumn);

        // Fetch data from the database and populate the TableView
        try {
            Connection connection = DBConnection.getConnection();
            Statement statement = connection.createStatement();
            ResultSet resultSet = statement.executeQuery("SELECT * FROM AUTEUR");

            ObservableList<Auteur> auteurList = FXCollections.observableArrayList();
            while (resultSet.next()) {
                int autNum = resultSet.getInt("Aut_num");
                String nom = resultSet.getString("nom");
                String prenom = resultSet.getString("prenom");
                String dateNaissance = resultSet.getString("date_naissance");
                String description = resultSet.getString("description");

                Auteur auteur = new Auteur(autNum, nom, prenom, dateNaissance, description);
                auteurList.add(auteur);
            }
            tableView.setItems(auteurList);

            resultSet.close();
            statement.close();
            connection.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }

        layout.getChildren().add(tableView);
        return layout;
    }
}
