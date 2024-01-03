<?php
session_start();
// Include database connection
require_once("../../../database/database.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the form
    $idAthlete = filter_input(INPUT_POST, 'idAthlete', FILTER_SANITIZE_NUMBER_INT);
    $idEpreuve = filter_input(INPUT_POST, 'idEpreuve', FILTER_SANITIZE_NUMBER_INT);
    $resultat = filter_input(INPUT_POST, 'resultat', FILTER_SANITIZE_STRING);

    try {
        // Check if the combination of athlete and epreuve already exists
        $queryCheck = "SELECT * FROM PARTICIPER WHERE id_athlete = :idAthlete AND id_epreuve = :idEpreuve";
        $statementCheck = $connexion->prepare($queryCheck);
        $statementCheck->bindParam(":idAthlete", $idAthlete, PDO::PARAM_INT);
        $statementCheck->bindParam(":idEpreuve", $idEpreuve, PDO::PARAM_INT);
        $statementCheck->execute();

        if ($statementCheck->rowCount() > 0) {
            // Entry already exists, handle accordingly
            // You might want to update the result or show an error message
            echo "Result for this athlete and epreuve already exists.";
        } else {
            // Insert the new result
            $queryInsert = "INSERT INTO PARTICIPER (id_athlete, id_epreuve, resultat) VALUES (:idAthlete, :idEpreuve, :resultat)";
            $statementInsert = $connexion->prepare($queryInsert);
            $statementInsert->bindParam(":idAthlete", $idAthlete, PDO::PARAM_INT);
            $statementInsert->bindParam(":idEpreuve", $idEpreuve, PDO::PARAM_INT);
            $statementInsert->bindParam(":resultat", $resultat, PDO::PARAM_STR);

            if ($statementInsert->execute()) {
                // Success message or redirection
                $_SESSION['success'] = "Le Resultat a été ajouté avec succès.";
                header("Location: manage-results.php");
            } else {
                // Handle error
                echo "Error adding result.";
            }
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
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
    <title>Ajouter un Resultat - Jeux Olympiques 2024</title>

</head>

<body>
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
            <h1>Ajouter un Resultat</h1>
        </figure>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="add-results.php" method="post"
            onsubmit="return confirm('Êtes-vous sûr de vouloir ajouter ce résultat?')">
            <!-- Select list for Athlete -->
            <label for="idAthlete">Athlete :</label>
            <?php
            try {
                $statement = $connexion->query("SELECT * FROM ATHLETE");
                if ($statement->rowCount() > 0) {
                    echo "<select name='idAthlete' required  onfocus='this.size=2;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>";
                    echo '<option value="">--Choose--</option>';
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row["id_athlete"] . "'>" . $row["nom_athlete"] . " " . $row["prenom_athlete"] . "</option>";
                    }
                    echo "</select><br>";
                } else {
                    echo "No database found";
                }
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <!-- Select list for Epreuve -->
            <label for="idEpreuve">Epreuve :</label>
            <?php
            try {
                $statement = $connexion->query("SELECT * FROM EPREUVE");
                if ($statement->rowCount() > 0) {
                    echo "<select name='idEpreuve' required  onfocus='this.size=2;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>";
                    echo '<option value="">--Choose--</option>';
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row["id_epreuve"] . "'>" . $row["nom_epreuve"] . "</option>";
                    }
                    echo "</select><br>";
                } else {
                    echo "No database found";
                }
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <!-- Input for Resultat -->
            <label for="resultat">Résultat :</label>
            <input type="text" name="resultat" required>

            <!-- Submit button -->
            <input type="submit" value="Ajouter le Résultat">
        </form>

        <p class="paragraph-link">
            <a class="link-home" href="manage-results.php">Retour</a>
        </p>
    </main>
    <footer>

        <a href="https://cdc-jo-nkh.netlify.app/" target="blank">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>

</body>

</html>