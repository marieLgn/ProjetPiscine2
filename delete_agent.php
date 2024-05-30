<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 3) {
    header("Location: login_form.php");
    exit();
}

if (isset($_POST['id_agent'])) {
    $id_agent = $_POST['id_agent'];

    // Supprimer l'agent
    $sql = "DELETE FROM Agent WHERE Id_Agent = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_agent);

    // Supprimer le CV associé
    if ($stmt->execute()) {
        $sql_cv = "DELETE FROM CV WHERE Id_CV = (SELECT Id_CV FROM Agent WHERE Id_Agent = ?)";
        $stmt_cv = $conn->prepare($sql_cv);
        $stmt_cv->bind_param("i", $id_agent);
        $stmt_cv->execute();
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de l'agent.";
    }
}
?>
