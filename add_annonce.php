<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 3) {
    header("Location: login_form.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $type_propriete = $_POST['type_propriete'];
    $description = $_POST['description'];
    $nombre_piece = $_POST['nombre_piece'];
    $dimensions = $_POST['dimensions'];
    $etage = $_POST['etage'];
    $statut = $_POST['statut'];
    $prix = $_POST['prix'];

    $sql = "INSERT INTO Propriete (Adresse, Ville, Code_postal, Type_propriete, Description, Nombre_piece, Dimensions, Etage, Statut, Prix) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $adresse, $ville, $code_postal, $type_propriete, $description, $nombre_piece, $dimensions, $etage, $statut, $prix);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout de l'annonce.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une annonce</title>
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

        .form-section input, .form-section textarea {
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
        <h1>Ajouter une annonce</h1>
        <div class="form-section">
            <form method="POST" action="add_annonce.php">
                <input type="text" name="adresse" placeholder="Adresse" required>
                <input type="text" name="ville" placeholder="Ville" required>
                <input type="text" name="code_postal" placeholder="Code postal" required>
                <input type="text" name="type_propriete" placeholder="Type de propriété" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="text" name="nombre_piece" placeholder="Nombre de pièces" required>
                <input type="text" name="dimensions" placeholder="Dimensions" required>
                <input type="text" name="etage" placeholder="Étage" required>
                <input type="text" name="statut" placeholder="Statut" required>
                <input type="text" name="prix" placeholder="Prix" required>
                <button type="submit">Ajouter</button>
            </form>
        </div>
        <form action="admin_dashboard.php" method="get">
            <button type="submit" class="back-button">Retour</button>
        </form>
    </div>
</body>
</html>
