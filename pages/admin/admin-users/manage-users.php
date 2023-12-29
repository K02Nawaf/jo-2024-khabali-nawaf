<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

$login = $_SESSION['login'];
$nom_utilisateur = $_SESSION['prenom_utilisateur'];
$prenom_utilisateur = $_SESSION['nom_utilisateur'];
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
    <title>Liste des Utilisateur - Jeux Olympiques 2024</title>
</head>

<body class="adminBody">
    <header>
        <nav class="adminNav">
            <!-- Menu vers les pages sports, events, et results -->
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
            <h1>Liste des Utilisateur</h1>
        </figure>
        <div class="table-container smallTable">
            <?php
            require_once("../../../database/database.php");

            try {
                // Requête pour récupérer la liste des utilisateurs depuis la base de données
                if ($login === "admin") {
                    // If the user is an admin, retrieve all users
                    $query = "SELECT * FROM UTILISATEUR ORDER BY id_utilisateur";
                } else {
                    // If the user is not an admin, retrieve only their own account
                    $query = "SELECT * FROM UTILISATEUR WHERE login = :login";
                }

                $statement = $connexion->prepare($query);

                // Bind the login parameter if it's a non-admin user
                if ($login !== "admin") {
                    $statement->bindParam(":login", $login, PDO::PARAM_STR);
                }

                $statement->execute();

                // Vérifier s'il y a des résultats
                if ($statement->rowCount() > 0) {
                    echo "<table><tr><th>Nom Utilisateur</th><th>Prenom Utilisateur</th><th>Login</th><th>Password</th><th>Modifier</th><th>Supprimer</th></tr>";

                    // Afficher les données dans un tableau
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        // Assainir les données avant de les afficher
                        echo "<td>" . htmlspecialchars($row['nom_utilisateur']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prenom_utilisateur']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['login']) . "</td>";
                        echo "<td>*****</td>";
                        echo "<td><button onclick='openModifyUserForm({$row['id_utilisateur']})'>Modifier</button></td>";
                        echo "<td><button  class='delete' onclick='deleteUserConfirmation({$row['id_utilisateur']})'>Supprimer</button></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>Aucun utilisateur trouvé.</p>";
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            // Afficher les erreurs en PHP
            // (fonctionne à condition d’avoir activé l’option en local)
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
            ?>
        </div>
        <div class="action-buttons">
            <?php
            // Show the "Ajouter un utilisateur" button only for Admin
            if ($login === "admin") {
                echo "<button onclick=\"openAddUserForm()\">Ajouter un utilisateur +</button>";
            }
            ?>
        </div>
    </main>
    <footer>
        <a href="">Plan de Site</a>
        <a href="https://cdc-jo-nkh.netlify.app/" target="blank">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
    <script>
        function openAddUserForm() {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification
            // L'URL contien un paramètre "id"
            window.location.href = 'add-users.php';
        }

        function openModifyUserForm(id_utilisateur) {
            // Ajoutez ici le code pour afficher un formulaire stylisé pour modifier un users
            // alert(id_utilisateur);
            window.location.href = 'modify-users.php?id_utilisateur=' + id_utilisateur;
        }

        function deleteUserConfirmation(id_utilisateur) {
            // Ajoutez ici le code pour afficher une fenêtre de confirmation pour supprimer un users
            if (confirm("Êtes-vous sûr de vouloir supprimer ce user?")) {
                // Ajoutez ici le code pour la suppression du users
                // alert(id_utilisateur);
                window.location.href = 'delete-users.php?id_utilisateur=' + id_utilisateur;
            }
        }
    </script>
</body>

</html>