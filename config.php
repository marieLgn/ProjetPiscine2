<?php
$servername = "localhost"; // Assurez-vous que le port est correct
$username = "root";
$password = ""; // Mettez le mot de passe si nécessaire
$dbname = "emobillier2"; // Utilisez le nom correct de la base de données

// Créez la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
