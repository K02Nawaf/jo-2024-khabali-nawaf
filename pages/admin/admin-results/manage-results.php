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
    <title>Liste des Resultats - Jeux Olympiques 2024</title>
</head>

<body class="adminBody">
    <header>
        <nav class="adminNav">
            <!-- Menu vers les pages sports, events, et results -->
            <ul class="menu">
                <li><a href="../admin.php">Accueil Administration</a></li>
                <li><a href="../admin-sports/manage-sports.php">Gestion Sports</a></li>
                <li><a href="../admin-places/manage-places.php">Gestion Lieux</a></li>
                <li><a href="../admin-events/manage-events.php">Gestion Calendrier</a></li>
                <li><a href="../admin-countries/manage-countries.php">Gestion Pays</a></li>
                <li><a href="../admin-gender/manage-gender.php">Gestion Genres</a></li>
                <li><a href="../admin-athletes/manage-athletes.php">Gestion Athlètes</a></li>
                <li><a class="current" href="./manage-results.php">Gestion Résultats</a></li>
                <li><a href="../admin-users/manage-users.php">Gestion Utilisateur</a></li>
                <li><a class="red" href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <figure>
            <img class="small" src="../../../img/cutLogo-jo-2024.png" alt="logo jeux olympiques 2024">
            <h1>Liste des Resultats</h1>
        </figure>

        <div class="table-container">
            <!-- Tableau des sports -->
            <?php
            require_once("../../../database/database.php");

            try {
                // Requête pour récupérer la liste des sports depuis la base de données
                $query = "SELECT * FROM PARTICIPER 
                INNER JOIN EPREUVE ON PARTICIPER.id_epreuve = EPREUVE.id_epreuve
                INNER JOIN ATHLETE ON PARTICIPER.id_athlete = ATHLETE.id_athlete
                ORDER BY PARTICIPER.id_epreuve";
                $statement = $connexion->prepare($query);
                $statement->execute();

                // Vérifier s'il y a des résultats
                if ($statement->rowCount() > 0) {
                    echo "<table>";
                    echo "<thead>
                <th class='color'>Athlete</th>
                <th class='color'>Epreuve</th>
                <th class='color'>Resultat</th>
                <th class='color'>Modifier</th>
                <th class='color'>supprimer</th>
                </thead>";

                    // Afficher les données dans un tableau
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nom_athlete']) . " " . htmlspecialchars($row['prenom_athlete']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nom_epreuve']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['resultat']) . "</td>";
                        echo "<td><button onclick='openModifyResultsForm(\"{$row['nom_athlete']} {$row['prenom_athlete']}\", \"{$row['resultat']}\")'>Modifier</button></td>";
                        echo "<td><button  class='delete' onclick='deleteResultsConfirmation(\"{$row['resultat']}\")'>Supprimer</button></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>Aucun Resultat trouvé.</p>";
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
            <button onclick="openAddResultsForm()">Ajouter un Resultat +</button>
            <!-- Autres boutons... -->
        </div>
    </main>
    <footer>
        <a href="">Plan de Site</a>
        <a href="">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
    <script>
        function openAddResultsForm() {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification
            // L'URL contien un paramètre "id"
            window.location.href = 'add-results.php';
        }

        function openModifyResultsForm(id_athlete, resultat) {
            // Now you can use id_athlete and resultat in your URL
            var encodedResultat = encodeURIComponent(resultat);
            window.location.href = 'modify-results.php?id_athlete=' + id_athlete + '&resultat=' + encodedResultat;
        }



        function deleteResultsConfirmation(resultat) {
            // Ajoutez ici le code pour afficher une fenêtre de confirmation pour supprimer un Résultat
            if (confirm("Êtes-vous sûr de vouloir supprimer ce résultat?")) {
                // Ajoutez ici le code pour la suppression du Résultat
                // alert(resultat);
                console.log('URL to be redirected:', 'delete-results.php?resultat=' + encodeURIComponent(resultat));
                window.location.href = 'delete-results.php?resultat=' + encodeURIComponent(resultat);
            }
        }
    </script>
</body>

</html>