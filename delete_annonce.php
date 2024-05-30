<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 3) {
    header("Location: login_form.php");
    exit();
}

if (isset($_POST['id_propriete'])) {
    $id_propriete = $_POST['id_propriete'];

    $sql = "DELETE FROM Propriete WHERE Id_Propriete = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_propriete);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de l'annonce.";
    }
}
?>
