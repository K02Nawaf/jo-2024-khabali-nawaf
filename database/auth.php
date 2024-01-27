<?php
ini_set('session.cookie_lifetime', 0);  // Set session cookie to expire when the browser is closed
session_start(); // Start the PHP session to store session variables.

require_once("database.php"); // Include the database connection file.

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the request method is POST (form submitted).
    
    $loginRetrieve = $_POST["login"]; // Retrieve the value of the "login" field from the form.
    $login = htmlspecialchars($loginRetrieve); // Sanitize the login input
    $password = $_POST["password"]; // Retrieve the value of the "password" field from the form.
    
    // Prepare the SQL query to retrieve user information with the specified login.
    $query = "SELECT id_utilisateur, nom_utilisateur, prenom_utilisateur, login, password FROM UTILISATEUR WHERE login = :login";
    $stmt = $connexion->prepare($query); // Prepare the query with PDO.
    $stmt->bindParam(":login", $login, PDO::PARAM_STR); // Bind the :login variable to the sanitized login, preventing SQL injections.
    
    if ($stmt->execute()) { // Execute the prepared query.
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the first result row from the query.

        if ($row && password_verify($password, $row["password"])) {
            // If a row is retrieved and the password matches the one stored in the database.
            $_SESSION["id_utilisateur"] = $row["id_utilisateur"]; // Store the user ID in the session.
            $_SESSION["nom_utilisateur"] = $row["nom_utilisateur"]; // Store the user's name in the session.
            $_SESSION["prenom_utilisateur"] = $row["prenom_utilisateur"]; // Store the user's surname in the session.
            $_SESSION["login"] = $row["login"]; // Store the user's login in the session.

            header("location: ../pages/admin/admin.php"); // Redirect to the administration page.
            exit(); // Terminate the script.
        } else {
            $_SESSION['error'] = "Login or password incorrect.";
            header("location: ../pages/login.php");
        }
    } else {
        $_SESSION['error'] = "Error executing the query."; // Set an error message in the session.
        header("location: ../pages/login.php"); // Redirect to the login page with an error message.
    }

    unset($stmt); // Free the resources associated with the prepared query.
}

unset($connexion); // Close the database connection.

header("location: ../pages/login.php"); // Redirect to the default login page.
error_reporting(E_ALL); // Display PHP errors.
ini_set("display_errors", 1); // Set PHP to display errors.
exit(); // Terminate the script.
?>
