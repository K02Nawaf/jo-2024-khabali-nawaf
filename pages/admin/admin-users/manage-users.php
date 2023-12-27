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
    <title>Liste des Sports - Jeux Olympiques 2024</title>
</head>

<body class="adminBody">
    <header>
        <nav class="adminNav">
            <!-- Menu vers les pages sports, events, et results -->
            <ul class="menu">
            <li><a href="../admin.php">Accueil Administration</a></li>
                <li><a class="current" href="./manage-sports.php">Gestion Sports</a></li>
                <li><a href="../admin-places/manage-places.php">Gestion Lieux</a></li>
                <li><a href="../admin-events/manage-events.php">Gestion Calendrier</a></li>
                <li><a href="../admin-countries/manage-countries.php">Gestion Pays</a></li>
                <li><a href="../admin-gender/manage-gender.php">Gestion Genres</a></li>
                <li><a href="../admin-athletes/manage-athletes.php">Gestion Athlètes</a></li>
                <li><a href="../admin-results/manage-results.php">Gestion Résultats</a></li>
                <li><a class="red" href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <figure>
            <img class="small" src="../../../img/cutLogo-jo-2024.png" alt="logo jeux olympiques 2024">
            <h1>Liste des Sports</h1>
        </figure>
        <div class="table-container smallTable">
            <!-- Tableau des sports -->
            <?php
            require_once("../../../database/database.php");

            try {
                // Requête pour récupérer la liste des sports depuis la base de données
                $query = "SELECT * FROM SPORT ORDER BY nom_sport";
                $statement = $connexion->prepare($query);
                $statement->execute();

                // Vérifier s'il y a des résultats
                if ($statement->rowCount() > 0) {
                    echo "<table><tr><th>Sport</th><th>Modifier</th><th>Supprimer</th></tr>";

                    // Afficher les données dans un tableau
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        // Assainir les données avant de les afficher
                        echo "<td>" . htmlspecialchars($row['nom_sport']) . "</td>";
                        echo "<td><button onclick='openModifySportForm({$row['id_sport']})'>Modifier</button></td>";
                        echo "<td><button  class='delete' onclick='deleteSportConfirmation({$row['id_sport']})'>Supprimer</button></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>Aucun sport trouvé.</p>";
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
            <button onclick="openAddSportForm()">Ajouter un Sport +</button>
            <!-- Autres boutons... -->
        </div>
    </main>
    <footer>
        <a href="">Plan de Site</a>
        <a href="">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
    <script>
        function openAddSportForm() {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification
            // L'URL contien un paramètre "id"
            window.location.href = 'add-sport.php';
        }

        function openModifySportForm(id_sport) {
            // Ajoutez ici le code pour afficher un formulaire stylisé pour modifier un sport
            // alert(id_sport);
            window.location.href = 'modify-sport.php?id_sport=' + id_sport;
        }

        function deleteSportConfirmation(id_sport) {
            // Ajoutez ici le code pour afficher une fenêtre de confirmation pour supprimer un sport
            if (confirm("Êtes-vous sûr de vouloir supprimer ce sport?")) {
                // Ajoutez ici le code pour la suppression du sport
                // alert(id_sport);
                window.location.href = 'delete-sport.php?id_sport=' + id_sport;
            }
        }
    </script>
</body>

</html>