<?php
session_start();
require_once("../../../database/database.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous d'obtenir des données sécurisées et filtrées
    $nomUser = filter_input(INPUT_POST, 'nomUser', FILTER_SANITIZE_STRING);
    $prenomUser = filter_input(INPUT_POST, 'prenomUser', FILTER_SANITIZE_STRING);
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Vérifiez si le nom du utilisateur est vide
    if (empty($nomUser) || empty($prenomUser) || empty($login) || empty($password)) {
        $_SESSION['error'] = "Tous les champs doivent être remplis.";
        header("Location: add-users.php");
        exit();
    }

    try {
        // Vérifiez si le utilisateur existe déjà
        $queryCheck = "SELECT * FROM utilisateur WHERE login = :login";
        $statementCheck = $connexion->prepare($queryCheck);
        $statementCheck->bindParam(":login", $login, PDO::PARAM_STR);
        $statementCheck->execute();

        if ($statementCheck->rowCount() > 0) {
            $_SESSION['error'] = "Le utilisateur existe déjà.";
            header("Location: add-users.php");
            exit();
        } else {
            
            // Hachage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Requête pour ajouter un utilisateur
            $query = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, login, password) 
            VALUES (:nomUser, :prenomUser, :login, :hashedPassword)";

            $statement = $connexion->prepare($query);
            $statement->bindParam(":nomUser", $nomUser, PDO::PARAM_STR);
            $statement->bindParam(":prenomUser", $prenomUser, PDO::PARAM_STR);
            $statement->bindParam(":login", $login, PDO::PARAM_STR);
            $statement->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
            
            // Exécutez la requête
            if ($statement->execute()) {
                header("Location: manage-users.php");
                $_SESSION['success'] = "Le utilisateur a été ajouté avec succès.";
                exit();
            } else {
                header("Location: add-users.php");
                $_SESSION['error'] = "Erreur lors de l'ajout du utilisateur.";
                exit();
            }
        }
    } catch (PDOException $e) {
        header("Location: add-users.php");
        $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
        exit();
    }
}
// Afficher les erreurs en PHP
// (fonctionne à condition d’avoir activé l’option en local)
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/normalize.css">
    <link rel="stylesheet" href="../../../css/styles-computer.css">
    <link rel="stylesheet" href="../../../css/styles-responsive.css">
    <link rel="shortcut icon" href="../../../img/favicon-jo-2024.ico" type="image/x-icon">
    <title>Ajouter un Utilisateur - Jeux Olympiques 2024</title>

</head>

<body>
    <header>
        <nav class="adminNav">
            <ul class="menu">
            <li><a href="../admin.php">Accueil Administration</a></li>
                <li><a href="./manage-sports.php">Gestion Sports</a></li>
                <li><a href="../admin-places/manage-places.php">Gestion Lieux</a></li>
                <li><a href="../admin-events/manage-events.php">Gestion Calendrier</a></li>
                <li><a href="../admin-countries/manage-countries.php">Gestion Pays</a></li>
                <li><a href="../admin-gender/manage-gender.php">Gestion Genres</a></li>
                <li><a href="../admin-athletes/manage-athletes.php">Gestion Athlètes</a></li>
                <li><a href="../admin-results/manage-results.php">Gestion Résultats</a></li>
                <li><a class="current" href="./manage-users.php">Gestion Utilisateur</a></li>
                <li><a class="red" href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <figure>
        <img class="small" src="../../../img/cutLogo-jo-2024.png" alt="logo jeux olympiques 2024">
        <h1>Ajouter un Utilisateur</h1>
        </figure>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="add-users.php" method="post"
            onsubmit="return confirm('Êtes-vous sûr de vouloir ajouter ce utilisateur?')">
            <label for="nomUser">Nom d'utilisateur :</label>
            <input type="text" name="nomUser" id="nomUser" required>
            <label for="prenomUser">Prenom d'utilisateur :</label>
            <input type="text" name="prenomUser" id="prenomUser" required>
            <label for="login">Login :</label>
            <input type="text" name="login" id="login" required>
            <label for="password">Password :</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Ajouter le utilisateur">
        </form>
        <p class="paragraph-link">
            <a class="link-home" href="manage-users.php">Retour</a>
        </p>
    </main>
    <footer>

        <a href="https://cdc-jo-nkh.netlify.app/" target="blank">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>

</body>

</html>