<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_level = $_SESSION['user_level'];

if ($user_level == 1) {
    header("Location: client_dashboard.php");
} elseif ($user_level == 2) {
    header("Location: agent_dashboard.php");
} elseif ($user_level == 3) {
    header("Location: admin_dashboard.php");
} else {
    echo "Accès non autorisé!";
}
?>
