<?php
session_start();
if ($_SESSION['user_level'] != 2) {
    header("Location: login_form.php");
    exit();
}
// Affichage du tableau de bord pour les agents
echo "<h1>Bienvenue sur votre tableau de bord agent immobilier</h1>";
?>
