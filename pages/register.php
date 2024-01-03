<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles-computer.css">
    <link rel="stylesheet" href="../css/styles-responsive.css">
    <link rel="shortcut icon" href="../img/favicon-jo-2024.ico" type="image/x-icon">
    <title>Inscription - Jeux Olympiques 2024</title>
    <style>
        /* Add your CSS style here */
    </style>
</head>

<body>
    <header>
        <nav>
            <!-- Menu to pages sports, events, and results -->
            <ul class="menu">
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="sports.php">Sports</a></li>
                <li><a href="events.php">Calendrier des évènements</a></li>
                <li><a href="results.php">Résultats</a></li>
                <li><a class="current" href="">Inscription</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <figure>
            <img class="small" src="../img/cutLogo-jo-2024.png" alt="logo jeux olympiques 2024">
            <h1>Inscription</h1>
        </figure>
        <form action="../database/add_account.php" method="post">
            <label for="login">Login :</label>
            <input type="text" name="login" id="login" required><br><br>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required><br><br>
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required><br><br>
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required><br><br>
            <input type="submit" value="S'inscrire">
        </form>
        <?php
        // Display an error message if there is an error during the registration attempt
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        // Display PHP errors
        // (works provided that the option is enabled locally)
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        ?>
    </main>
    <footer>

        <a href="https://cdc-jo-nkh.netlify.app/" target="blank">Cahier de charge</a>
        <a href="https://nawafkh.webflow.io/" target="blank">Portfolio</a>
    </footer>
</body>

</html>
