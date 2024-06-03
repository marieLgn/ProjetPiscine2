<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_level = $_SESSION['user_level'];

switch ($user_level) {
    case 1:
        header("Location: client_dashboard.php");
        break;
    case 2:
        header("Location: agent_dashboard.php");
        break;
    case 3:
        header("Location: admin_dashboard.php");
        break;
    default:
        header("Location: login.php");
        break;
}
exit();
?>
