<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 3) {
    header("Location: login_form.php");
    exit();
}

if (!isset($_GET['id_cv'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id_cv = $_GET['id_cv'];

// Récupérer les informations du CV et de l'agent
$sql = "SELECT CV.*, Agent.Nom AS AgentNom, Agent.Prenom AS AgentPrenom, Agent.Bureau, Agent.No_Telephone, Agent.Email, Agent.Specialite 
        FROM CV 
        JOIN Agent ON CV.Id_CV = Agent.Id_CV 
        WHERE CV.Id_CV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cv);
$stmt->execute();
$result = $stmt->get_result();
$cv = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CV de l'Agent</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>CV de <?php echo $cv['AgentPrenom'] . ' ' . $cv['AgentNom']; ?></h1>

        <div class="cv-section">
            <h2>Informations Personnelles</h2>
            <p><strong>Nom:</strong> <?php echo $cv['AgentNom']; ?></p>
            <p><strong>Prénom:</strong> <?php echo $cv['AgentPrenom']; ?></p>
            <p><strong>Bureau:</strong> <?php echo $cv['Bureau']; ?></p>
            <p><strong>Téléphone:</strong> <?php echo $cv['No_Telephone']; ?></p>
            <p><strong>Email:</strong> <?php echo $cv['Email']; ?></p>
            <p><strong>Spécialité:</strong> <?php echo $cv['Specialite']; ?></p>
        </div>

        <div class="cv-section">
            <h2>Formation</h2>
            <p><?php echo $cv['Formation']; ?></p>
        </div>

        <div class="cv-section">
            <h2>Expériences</h2>
            <p><?php echo $cv['Experiences']; ?></p>
        </div>

        <div class="cv-section">
            <h2>Compétences</h2>
            <p><?php echo $cv['Competence']; ?></p>
        </div>

        <div class="cv-section">
            <h2>Centre d'intérêt</h2>
            <p><?php echo $cv['Centre_d_interet']; ?></p>
        </div>

        <form action="admin_dashboard.php" method="get">
            <button type="submit" class="back-button">Retour</button>
        </form>
    </div>
</body>
</html>
