<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles-computer.css">
    <link rel="stylesheet" href="../css/styles-responsive.css">
    <link rel="shortcut icon" href="../img/favicon-jo-2024.ico" type="image/x-icon">
    <title>Liste des epreuve - Jeux Olympiques 2024</title>
</head>

<body>
    <header>
        <nav>
            <!-- Menu vers les pages sports, events, et results -->
            <ul class="menu">
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="sports.php">Sports</a></li>
                <li><a class="current" href="events.php">Calendrier des évènements</a></li>
                <li><a href="results.php">Résultats</a></li>
                <li><a href="login.php">Accès administrateur</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <figure>
            <img class="small" src="../img/cutLogo-jo-2024.png" alt="logo jeux olympiques 2024">
            <h1>Liste des evenement</h1>
        </figure>
        <div class="table-container">
            <?php
            require_once("../database/database.php");

            try {
                // Requête pour récupérer la liste des sports depuis la base de données
                $query = "SELECT * FROM EPREUVE 
                INNER JOIN LIEU ON EPREUVE.id_lieu = LIEU.id_lieu
                INNER JOIN SPORT ON EPREUVE.id_sport = SPORT.id_sport
                ORDER BY date_epreuve";
                $statement = $connexion->prepare($query);
                $statement->execute();

                // Vérifier s'il y a des résultats
                if ($statement->rowCount() > 0) {
                    echo "<table>";
                    echo "<thead>
                <th class='color'>Epreuve</th>
                <th class='color'>Sport</th>
                <th class='color'>Date</th>
                <th class='color'>Heure</th>
                <th class='color'>Nom du Lieu</th>
                <th class='color'>Adresse du Lieu</th>
                </thead>";

                    // Afficher les données dans un tableau
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nom_epreuve']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nom_sport']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date_epreuve']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['heure_epreuve']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nom_lieu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adresse_lieu']) . "</td>";

                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>Aucun Epreuve trouvé.</p>";
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

    </main>
    <footer>
        <a href="">Plan de Site</a>
        <a href="">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
</body>

</html>