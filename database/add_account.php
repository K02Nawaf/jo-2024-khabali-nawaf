<?php
session_start();

require_once("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO UTILISATEUR (login, password, nom_utilisateur, prenom_utilisateur) 
              VALUES (:login, :password, :nom, :prenom)";
    $stmt = $connexion->prepare($query);
    $stmt->bindParam(":login", $login, PDO::PARAM_STR);
    $stmt->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
    $stmt->bindParam(":prenom", $prenom, PDO::PARAM_STR);
    echo "Hashed Password: " . $hashedPassword;
    if ($stmt->execute()) {
        $_SESSION["success"] = "Compte créé avec succès. Connectez-vous avec vos identifiants.";
        header("location: ../pages/login.php");
        exit();
    } else {
        $_SESSION["error"] = "Erreur lors de la création du compte.";
        header("location: ../pages/register.php");
        exit();
    }
}

unset($connexion);

header("location: ../pages/register.php");
exit();
?>
