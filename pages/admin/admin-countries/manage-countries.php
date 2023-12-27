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
    <title>Liste des Pays - Jeux Olympiques 2024</title>
</head>

<body class="adminBody">
    <header>
        <nav class="adminNav">
            <!-- Menu vers les pages pays, events, et results -->
            <ul class="menu">
                <li><a href="../admin.php">Accueil Administration</a></li>
                <li><a href="../admin-sports/manage-sports.php">Gestion Sports</a></li>
                <li><a href="../admin-places/manage-places.php">Gestion Lieux</a></li>
                <li><a href="../admin-events/manage-events.php">Gestion Calendrier</a></li>
                <li><a class="current" href="./manage-countries.php">Gestion Pays</a></li>
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
            <h1>Liste des Pays</h1>
        </figure>
        <div class="table-container smallTable">
            <!-- Tableau des pays -->
            <?php
            require_once("../../../database/database.php");

            try {
                // Requête pour récupérer la liste des pays depuis la base de données
                $query = "SELECT * FROM PAYS ORDER BY nom_pays";
                $statement = $connexion->prepare($query);
                $statement->execute();

                // Vérifier s'il y a des résultats
                if ($statement->rowCount() > 0) {
                    echo "<table><tr><th>Pays</th><th>Modifier</th><th>Supprimer</th></tr>";

                    // Afficher les données dans un tableau
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        // Assainir les données avant de les afficher
                        echo "<td>" . htmlspecialchars($row['nom_pays']) . "</td>";
                        echo "<td><button onclick='openModifyCountriesForm({$row['id_pays']})'>Modifier</button></td>";
                        echo "<td><button  class='delete'  onclick='deleteCountriesConfirmation({$row['id_pays']})'>Supprimer</button></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>Aucun pays trouvé.</p>";
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
            <button onclick="openAddCountriesForm()">Ajouter un Pays +</button>
            <!-- Autres boutons... -->
        </div>
    </main>
    <footer>
        <a href="">Plan de Site</a>
        <a href="">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
    <script>
        function openAddCountriesForm() {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification
            // L'URL contien un paramètre "id"
            window.location.href = 'add-countries.php';
        }

        function openModifyCountriesForm(id_pays) {
            // Ajoutez ici le code pour afficher un formulaire stylisé pour modifier un pays
            // alert(id_pays);
            window.location.href = 'modify-countries.php?id_pays=' + id_pays;
        }

        function deleteCountriesConfirmation(id_pays) {
            // Ajoutez ici le code pour afficher une fenêtre de confirmation pour supprimer un pays
            if (confirm("Êtes-vous sûr de vouloir supprimer ce pays?")) {
                // Ajoutez ici le code pour la suppression du pays
                // alert(id_pays);
                window.location.href = 'delete-countries.php?id_pays=' + id_pays;
            }
        }
    </script>
</body>

</html>