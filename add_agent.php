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
    <title>Ajouter un agent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        .form-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-section input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-section button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-section button:hover {
            background-color: #0056b3;
        }

        .back-button {
            background-color: #d9534f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #c9302c;
        }
    </style>
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
