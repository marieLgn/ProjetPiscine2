<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 2) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: agent_dashboard.php");
    exit();
}

$client_id = $_GET['id'];

// Récupérer les informations du client
$sql = "SELECT * FROM Client WHERE Id_Client = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossier Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        .profile-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .profile-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .profile-section input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .back-button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dossier de <?php echo $client['Prenom'] . ' ' . $client['Nom']; ?></h1>

        <div class="profile-section">
            <h2>Informations personnelles</h2>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo $client['Nom']; ?>" disabled>
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $client['Prenom']; ?>" disabled>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $client['Email']; ?>" disabled>
            <label for="telephone">Téléphone:</label>
            <input type="text" id="telephone" name="telephone" value="<?php echo $client['No_Telephone']; ?>" disabled>
            <label for="code_postal">Code postal:</label>
            <input type="text" id="code_postal" name="code_postal" value="<?php echo $client['Code_postal']; ?>" disabled>
            <label for="pays">Pays:</label>
            <input type="text" id="pays" name="pays" value="<?php echo $client['Pays']; ?>" disabled>
            <label for="adresse_ligne_1">Adresse ligne 1:</label>
            <input type="text" id="adresse_ligne_1" name="adresse_ligne_1" value="<?php echo $client['Adresse_ligne_1']; ?>" disabled>
            <label for="adresse_ligne_2">Adresse ligne 2:</label>
            <input type="text" id="adresse_ligne_2" name="adresse_ligne_2" value="<?php echo $client['Adresse_ligne_2']; ?>" disabled>
            <label for="ville">Ville:</label>
            <input type="text" id="ville" name="ville" value="<?php echo $client['Ville']; ?>" disabled>
        </div>

        <form action="agent_dashboard.php" method="get">
            <button type="submit" class="back-button">Retour</button>
        </form>
    </div>
</body>
</html>
