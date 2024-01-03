<?php
session_start();
require_once("../../../database/database.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

// Vérifiez si l'ID du résultat est fourni dans l'URL
if (!isset($_GET['resultat'])) {
    $_SESSION['error'] = "ID du résultat manquant.";
    header("Location: manage-results.php");
    exit();
}

$resultat = filter_input(INPUT_GET, 'resultat', FILTER_SANITIZE_STRING);

// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous d'obtenir des données sécurisées et filtrées
    $idAthlete = filter_input(INPUT_POST, 'idAthlete', FILTER_SANITIZE_NUMBER_INT);
    $idEpreuve = filter_input(INPUT_POST, 'idEpreuve', FILTER_SANITIZE_NUMBER_INT);
    $resultatValue = filter_input(INPUT_POST, 'resultatValue', FILTER_SANITIZE_STRING);

    // Vérifiez si les champs du résultat sont vides
    if (empty($idAthlete) || empty($idEpreuve) || empty($resultatValue)) {
        $_SESSION['error'] = "Please fill all fields.";
        header("Location: modify-results.php?resultat=$resultat");
        exit();
    }

    try {
        // Requête pour mettre à jour le résultat
        $query = "UPDATE PARTICIPER SET id_athlete = :idAthlete, id_epreuve = :idEpreuve, resultat = :resultatValue WHERE resultat = :resultat";
        $statement = $connexion->prepare($query);
        $statement->bindParam(":idAthlete", $idAthlete, PDO::PARAM_INT);
        $statement->bindParam(":idEpreuve", $idEpreuve, PDO::PARAM_INT);
        $statement->bindParam(":resultatValue", $resultatValue, PDO::PARAM_STR);
        $statement->bindParam(":resultat", $resultat, PDO::PARAM_STR);

        // Exécutez la requête
        if ($statement->execute()) {
            $_SESSION['success'] = "Le résultat a été modifié avec succès.";
            header("Location: manage-results.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de la modification du résultat.";
            header("Location: modify-results.php?resultat=$resultat");
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
        header("Location: modify-results.php?resultat=$resultat");
        exit();
    }
}

// Récupérez les informations du résultat pour affichage dans le formulaire
try {
    $query = "SELECT * FROM PARTICIPER 
              WHERE resultat = :resultat";

    $statement = $connexion->prepare($query);
    $statement->bindParam(":resultat", $resultat, PDO::PARAM_STR);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        $resultatInfo = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        $_SESSION['error'] = "Résultat non trouvé.";
        header("Location: manage-results.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
    header("Location: manage-results.php");
    exit();
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
    <title>Modifier un Resultats - Jeux Olympiques 2024</title></head>

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
            <h1>Modifier un Résultat</h1>
        </figure>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="modify-results.php?resultat=<?php echo $resultat; ?>" method="post"
            onsubmit="return confirm('Êtes-vous sûr de vouloir modifier ce résultat?')">
            <label for="idAthlete">Athlete :</label>
            <!-- Vous pouvez remplacer cette section par votre propre logique pour récupérer et afficher la liste des athlètes -->
            <?php
            try {
                $athleteQuery = "SELECT * FROM ATHLETE";
                $athleteStatement = $connexion->query($athleteQuery);
                if ($athleteStatement->rowCount() > 0) {
                    echo "<select name='idAthlete'  onfocus='this.size=2;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>";
                    while ($athleteRow = $athleteStatement->fetch(PDO::FETCH_ASSOC)) {
                        $selectedAthlete = isset($resultatInfo['id_athlete']) ? $resultatInfo['id_athlete'] : '';
                        echo '<option value="' . htmlspecialchars($athleteRow["id_athlete"]) . '" ' . ($athleteRow["id_athlete"] == $selectedAthlete ? 'selected' : '') . '>' . htmlspecialchars($athleteRow["nom_athlete"] . ' ' . $athleteRow["prenom_athlete"]) . '</option>';
                    }
                    echo "</select><br>";
                } else {
                    echo "No athletes found";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <label for="idEpreuve">Epreuve :</label>
            <!-- Vous pouvez remplacer cette section par votre propre logique pour récupérer et afficher la liste des épreuves -->
            <?php
            try {
                $epreuveQuery = "SELECT * FROM EPREUVE";
                $epreuveStatement = $connexion->query($epreuveQuery);
                if ($epreuveStatement->rowCount() > 0) {
                    echo "<select name='idEpreuve'  onfocus='this.size=2;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>";
                    while ($epreuveRow = $epreuveStatement->fetch(PDO::FETCH_ASSOC)) {
                        $selectedEpreuve = isset($resultatInfo['id_epreuve']) ? $resultatInfo['id_epreuve'] : '';
                        echo '<option value="' . htmlspecialchars($epreuveRow["id_epreuve"]) . '" ' . ($epreuveRow["id_epreuve"] == $selectedEpreuve ? 'selected' : '') . '>' . htmlspecialchars($epreuveRow["nom_epreuve"]) . '</option>';
                    }
                    echo "</select><br>";
                } else {
                    echo "No events found";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <label for="resultatValue">Résultat :</label>
            <input type="text" name="resultatValue" id="resultatValue"
                value="<?php echo htmlspecialchars($resultatInfo['resultat']); ?>" required>
            <input type="submit" value="Modifier le Résultat">
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
