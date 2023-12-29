<?php
session_start();
require_once("../../../database/database.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

// Vérifiez si l'ID du Résultat est fourni dans l'URL
if (!isset($_GET['resultat'])) {
    $_SESSION['error'] = "ID du résultat manquant.";
    header("Location: manage-results.php");
    exit();
} else {
    $resultat = filter_input(INPUT_GET, 'resultat', FILTER_SANITIZE_STRING);

    try {
        // Préparez la requête SQL pour supprimer le résultat
        $sql = "DELETE FROM PARTICIPER WHERE resultat = :resultat";
        // Exécutez la requête SQL avec le paramètre
        $statement = $connexion->prepare($sql);
        $statement->bindParam(':resultat', $resultat, PDO::PARAM_STR);
        $statement->execute();
        // Redirigez vers la page précédente après la suppression
        header('Location: manage-results.php');
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

// Afficher les erreurs en PHP (fonctionne à condition d’avoir activé l’option en local)
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
