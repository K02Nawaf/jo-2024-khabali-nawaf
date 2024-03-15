package com.library.pages.Auteur;

public class Auteur {
    private int autNum;
    private String nom;
    private String prenom;
    private String dateNaissance;
    private String description;

    public Auteur(int autNum, String nom, String prenom, String dateNaissance, String description) {
        this.autNum = autNum;
        this.nom = nom;
        this.prenom = prenom;
        this.dateNaissance = dateNaissance;
        this.description = description;
    }

    public int getAutNum() {
        return autNum;
    }

    public String getNom() {
        return nom;
    }

    public String getPrenom() {
        return prenom;
    }

    public String getDateNaissance() {
        return dateNaissance;
    }

    public String getDescription() {
        return description;
    }
}

