<?php
session_start();
if ($_SESSION['user_level'] != 3) {
    header("Location: login_form.php");
    exit();
}
// Affichage du tableau de bord pour les administrateurs
echo "<h1>Bienvenue sur votre tableau de bord administrateur</h1>";
?>
