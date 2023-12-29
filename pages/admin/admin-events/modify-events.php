<?php
session_start();
require_once("../../../database/database.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

// Vérifiez si l'ID du lieu est fourni dans l'URL
if (!isset($_GET['id_epreuve'])) {
    $_SESSION['error'] = "ID du lieu manquant.";
    header("Location: manage-events.php");
    exit();
}

$id_epreuve = filter_input(INPUT_GET, 'id_epreuve', FILTER_VALIDATE_INT);

// Vérifiez si l'ID du lieu est un entier valide
if (!$id_epreuve && $id_epreuve !== 0) {
    $_SESSION['error'] = "ID du lieu invalide.";
    header("Location: manage-events.php");
    exit();
}

// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous d'obtenir des données sécurisées et filtrées
    $nomEpreuve = filter_input(INPUT_POST, 'nomEpreuve', FILTER_SANITIZE_STRING);
    $dateEpreuve = filter_input(INPUT_POST, 'dateEpreuve', FILTER_SANITIZE_STRING);
    $heureEpreuve = filter_input(INPUT_POST, 'heureEpreuve', FILTER_SANITIZE_STRING);
    $idLieu = filter_input(INPUT_POST, 'idLieu', FILTER_SANITIZE_NUMBER_INT);
    $idSport = filter_input(INPUT_POST, 'idSport', FILTER_SANITIZE_NUMBER_INT);

    // Vérifiez si le nom du lieu est vide
    if (empty($nomEpreuve) || empty($dateEpreuve) || empty($heureEpreuve) || empty($idLieu) || empty($idSport)) {
        $_SESSION['error'] = "Please fill all fields.";
        header("Location: modify-events.php=$id_epreuve");
        exit();
    }
    try {
        // Vérifiez si le lieu existe déjà
        $queryCheck = "SELECT id_epreuve FROM EPREUVE WHERE nom_epreuve = :nomEpreuve";
        $statementCheck = $connexion->prepare($queryCheck);
        $statementCheck->bindParam(":nomEpreuve", $nomEpreuve, PDO::PARAM_STR);
        $statementCheck->execute();

        if ($statementCheck->rowCount() > 0) {
            $_SESSION['error'] = "L'epreuve existe déjà.";
            header("Location: modify-events.php");
            exit();
        } else {
            // Requête pour mettre à jour le lieu
            $query = "UPDATE EPREUVE SET nom_epreuve = :nomEpreuve, date_epreuve = :dateEpreuve, heure_epreuve = :heureEpreuve, id_lieu = :idLieu, id_sport = :idSport WHERE id_epreuve = :idEpreuve";
            $statement = $connexion->prepare($query);
            $statement->bindParam(":nomEpreuve", $nomEpreuve, PDO::PARAM_STR);
            $statement->bindParam(":dateEpreuve", $dateEpreuve, PDO::PARAM_STR);
            $statement->bindParam(":heureEpreuve", $heureEpreuve, PDO::PARAM_STR);
            $statement->bindParam(":idLieu", $idLieu, PDO::PARAM_INT);
            $statement->bindParam(":idSport", $idSport, PDO::PARAM_INT);
            $statement->bindParam(":idEpreuve", $id_epreuve, PDO::PARAM_INT);
        }
        // Exécutez la requête
        if ($statement->execute()) {
            $_SESSION['success'] = "L'Epreuve a été modifié avec succès.";
            header("Location: manage-events.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de la modification du Epreuve.";
            header("Location: modify-events.php?id_epreuve=$id_epreuve");
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
        header("Location: modify-events.php?id_epreuve=$id_epreuve");
        exit();
    }
}

// Récupérez les informations du lieu pour affichage dans le formulaire
try {
    $query = "SELECT * FROM EPREUVE 
              INNER JOIN LIEU ON EPREUVE.id_lieu = LIEU.id_lieu 
              INNER JOIN SPORT ON EPREUVE.id_sport = SPORT.id_sport
              WHERE id_epreuve = :idEpreuve";

    $statement = $connexion->prepare($query);
    $statement->bindParam(":idEpreuve", $id_epreuve, PDO::PARAM_INT);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        $Epreuve = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        $_SESSION['error'] = "Epreuve non trouvé.";
        header("Location: manage-events.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
    header("Location: manage-events.php");
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
    <title>Modifier une Epreuve - Jeux Olympiques 2024</title>
    <style>
        /* Ajoutez votre style CSS ici */
    </style>
</head>

<body>
    <header>
        <nav class="adminNav">
            <!-- Menu vers les pages sports, events, et results -->
            <ul class="menu">
                <li><a href="../admin.php">Accueil Administration</a></li>
                <li><a href="../admin-sports/manage-sports.php">Gestion Sports</a></li>
                <li><a href="../admin-places/manage-places.php">Gestion Lieux</a></li>
                <li><a class="current" href="./manage-events.php">Gestion Calendrier</a></li>
                <li><a href="../admin-countries/manage-countries.php">Gestion Pays</a></li>
                <li><a href="../admin-gender/manage-gender.php">Gestion Genres</a></li>
                <li><a href="../admin-athletes/manage-athletes.php">Gestion Athlètes</a></li>
                <li><a href="../admin-results/manage-results.php">Gestion Résultats</a></li>

                <li><a href="../admin-users/manage-users.php">Gestion Utilisateur</a></li>
                <li><a class="red" href="../logout.php">Déconnexion</a></li>

            </ul>
        </nav>
    </header>
    <main>
        <figure>
            <img class="small" src="../../../img/cutLogo-jo-2024.png" alt="logo jeux olympiques 2024">
            <h1>Modifier une Epreuve</h1>
        </figure>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="modify-events.php?id_epreuve=<?php echo $id_epreuve; ?>" method="post"
            onsubmit="return confirm('Êtes-vous sûr de vouloir modifier ce lieu?')">
            <label for=" nomLieu">Nom du Epreuve :</label>
            <input type="text" placeholder="Exemple" name="nomEpreuve" id="nomEpreuve"
                value="<?php echo htmlspecialchars($Epreuve['nom_epreuve']); ?>" required>

            <label for="dateEpreuve">Date du Epreuve :</label>
            <input type="text" placeholder="AAAA-MM-JJ" name="dateEpreuve" id="dateEpreuve"
                value="<?php echo htmlspecialchars($Epreuve['date_epreuve']); ?>" required>

            <label for="heureEpreuve">Heure :</label>
            <input type="text" placeholder="00:00:00" name="heureEpreuve" id="heureEpreuve"
                value="<?php echo htmlspecialchars($Epreuve['heure_epreuve']); ?>" required>

            <label for="idLieu">Lieu :</label>
            <?php
            try {
                $statement = $connexion->query("SELECT * FROM lieu");
                if ($statement->rowCount() > 0) {
                    echo "<select name='idLieu' onfocus='this.size=2;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>";
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $selectedLieu = isset($_POST['idLieu']) ? $_POST['idLieu'] : $Epreuve['id_lieu'];
                        echo '<option value="' . htmlspecialchars($row["id_lieu"]) . '" ' . ($row["id_lieu"] == $selectedLieu ? 'selected' : '') . '>' . htmlspecialchars($row["nom_lieu"]) . '</option>';
                    }
                    echo "</select><br>";
                } else {
                    echo "No database found";
                }
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <label for="idSport">Sport :</label>
            <?php
            try {
                $statement = $connexion->query("SELECT * FROM Sport");
                if ($statement->rowCount() > 0) {
                    echo "<select name='idSport' onfocus='this.size=2;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>";
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $selectedSport = isset($_POST['idSport']) ? $_POST['idSport'] : $Epreuve['id_sport'];
                        echo '<option value="' . htmlspecialchars($row["id_sport"]) . '" ' . ($row["id_sport"] == $selectedSport ? 'selected' : '') . '>' . htmlspecialchars($row["nom_sport"]) . '</option>';
                    }
                    echo "</select><br>";
                } else {
                    echo "No database found";
                }
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>


            <input type="submit" value="Modifier l'Epreuve">
        </form>
        <p class="paragraph-link">
            <a class="link-home" href="manage-events.php">Retour à la gestion des Evenements</a>
        </p>
    </main>
    <footer>
        <a href="">Plan de Site</a>
        <a href="https://cdc-jo-nkh.netlify.app/" target="blank">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
</body>

</html>