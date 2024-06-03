<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 3) {
    header("Location: login_form.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $bureau = $_POST['bureau'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $specialite = $_POST['specialite'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Insérer le CV vide d'abord
    $sql_cv = "INSERT INTO CV (Formation, Experiences, Competence, Centre_d_interet) VALUES ('', '', '', '')";
    if ($conn->query($sql_cv) === TRUE) {
        $id_cv = $conn->insert_id;

        // Insérer l'agent avec l'ID du CV
        $sql_agent = "INSERT INTO Agent (Nom, Prenom, Bureau, No_Telephone, Email, Specialite, Id_CV, Mot_de_passe) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_agent);
        $stmt->bind_param("ssssssis", $nom, $prenom, $bureau, $telephone, $email, $specialite, $id_cv, $mot_de_passe);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Erreur lors de l'ajout de l'agent.";
        }
    } else {
        echo "Erreur lors de la création du CV.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Ajouter un agent</title>
    <h1 style="display: flex; align-items: center;">
            <img src="Image\Logo.jpg" style="height: 10%; width: 10%; margin-right: 550px;" alt="Propriété 1"> Omnes Emmobilier
        </h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="browse.php">Tout parcourir</a>
            <a href="search.php">Rechercher</a>
            <a href="rendez_vous.php">Rendez-vous</a>
            <a href="login.php">Votre compte</a> 
        </nav>
</head>
<body>
    <div class="container">
        <h1>Ajouter un agent</h1>
        <div class="form-section">
            <form method="POST" action="add_agent.php">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="text" name="bureau" placeholder="Bureau" required>
                <input type="text" name="telephone" placeholder="Téléphone" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="specialite" placeholder="Spécialité" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                <button type="submit">Ajouter</button>
            </form>
        </div>
        <form action="admin_dashboard.php" method="get">
            <button type="submit" class="back-button">Retour</button>
        </form>
    </div>
</body>
</html>