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
    <title>Liste des Lieux - Jeux Olympiques 2024</title>
</head>

<body class="adminBody">
    <header>
        <nav class="adminNav">
            <!-- Menu vers les pages sports, events, et results -->
            <ul class="menu">
                <li><a href="../admin.php">Accueil Administration</a></li>
                <li><a href="../admin-sports/manage-sports.php">Gestion Sports</a></li>
                <li><a class="current" href="./manage-places.php">Gestion Lieux</a></li>
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
            <h1>Liste des Lieu</h1>
        </figure>
        <div class="table-container">
            <!-- Tableau des sports -->
            <?php
            require_once("../../../database/database.php");

            try {
                // Requête pour récupérer la liste des sports depuis la base de données
                $query = "SELECT * FROM LIEU ORDER BY cp_lieu";
                $statement = $connexion->prepare($query);
                $statement->execute();

                // Vérifier s'il y a des résultats
                if ($statement->rowCount() > 0) {
                    echo "<table><tr><th>Nom du lieu</th><th>Adresse du lieu</th><th>Code postale</th><th>Ville</th><th>Modifier</th><th>Supprimer</th></tr>";

                    // Afficher les données dans un tableau
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        // Assainir les données avant de les afficher
                        echo "<td>" . htmlspecialchars($row['nom_lieu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adresse_lieu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cp_lieu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ville_lieu']) . "</td>";
                        echo "<td><button onclick='openModifyLieuForm({$row['id_lieu']})'>Modifier</button></td>";
                        echo "<td><button  class='delete'  onclick='deleteLieuConfirmation({$row['id_lieu']})'>Supprimer</button></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>Aucun Lieu trouvé.</p>";
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
            <button onclick="openAddLieuForm()">Ajouter un Lieu +</button>
            <!-- Autres boutons... -->
        </div>
    </main>
    <footer>
        <a href="">Plan de Site</a>
        <a href="">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
    <script>
        function openAddLieuForm() {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification
            // L'URL contien un paramètre "id"
            window.location.href = 'add-lieu.php';
        }

        function openModifyLieuForm(id_lieu) {
            // Ajoutez ici le code pour afficher un formulaire stylisé pour modifier un lieu
            // alert(id_lieu);
            window.location.href = 'modify-lieu.php?id_lieu=' + id_lieu;
        }

        function deleteLieuConfirmation(id_lieu) {
            // Ajoutez ici le code pour afficher une fenêtre de confirmation pour supprimer un lieu
            if (confirm("Êtes-vous sûr de vouloir supprimer ce Lieu?")) {
                // Ajoutez ici le code pour la suppression du lieu
                // alert(id_lieu);
                window.location.href = 'delete-lieu.php?id_lieu=' + id_lieu;
            }
        }
    </script>
</body>

</html>