<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 1) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Mettre à jour les informations du client
if (isset($_POST['update_profile'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $code_postal = $_POST['code_postal'];
    $pays = $_POST['pays'];
    $adresse_ligne_1 = $_POST['adresse_ligne_1'];
    $adresse_ligne_2 = $_POST['adresse_ligne_2'];
    $ville = $_POST['ville'];

    $sql = "UPDATE Client SET Nom = ?, Prenom = ?, Email = ?, No_Telephone = ?, Code_postal = ?, Pays = ?, Adresse_ligne_1 = ?, Adresse_ligne_2 = ?, Ville = ? WHERE Id_Client = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $nom, $prenom, $email, $telephone, $code_postal, $pays, $adresse_ligne_1, $adresse_ligne_2, $ville, $user_id);
    $stmt->execute();
}

// Annuler un rendez-vous
if (isset($_GET['cancel_rdv'])) {
    $rdv_id = $_GET['cancel_rdv'];

    $sql = "DELETE FROM Rendez_vous WHERE Id_Rendez_vous = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rdv_id);
    $stmt->execute();
}

// Récupérer les informations du client
$sql = "SELECT * FROM Client WHERE Id_Client = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// Récupérer l'historique des consultations
$sql = "SELECT * FROM Rendez_vous WHERE Id_Client = ? AND Date_heure < NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$consultations = $stmt->get_result();

// Récupérer les rendez-vous à venir
$sql = "SELECT * FROM Rendez_vous WHERE Id_Client = ? AND Date_heure > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$rendez_vous = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord client</title>
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

        .profile-section button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .profile-section button:hover {
            background-color: #0056b3;
        }

        .table-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-button {
            display: none;
        }

        .logout-button {
            background-color: #d9534f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #c9302c;
        }
    </style>
    <script>
        function toggleEditMode() {
            var formElements = document.querySelectorAll(".profile-section input");
            var editButton = document.getElementById("editButton");
            var updateButton = document.getElementById("updateButton");

            formElements.forEach(function(element) {
                element.disabled = !element.disabled;
            });

            editButton.style.display = editButton.style.display === "none" ? "block" : "none";
            updateButton.style.display = updateButton.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur votre tableau de bord client</h1>

        <div class="profile-section">
            <h2>Mes informations</h2>
            <form method="POST" action="client_dashboard.php">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo $client['Nom']; ?>" disabled required>
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo $client['Prenom']; ?>" disabled required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $client['Email']; ?>" disabled required>
                <label for="telephone">Téléphone:</label>
                <input type="text" id="telephone" name="telephone" value="<?php echo $client['No_Telephone']; ?>" disabled required>
                <label for="code_postal">Code postal:</label>
                <input type="text" id="code_postal" name="code_postal" value="<?php echo $client['Code_postal']; ?>" disabled required>
                <label for="pays">Pays:</label>
                <input type="text" id="pays" name="pays" value="<?php echo $client['Pays']; ?>" disabled required>
                <label for="adresse_ligne_1">Adresse ligne 1:</label>
                <input type="text" id="adresse_ligne_1" name="adresse_ligne_1" value="<?php echo $client['Adresse_ligne_1']; ?>" disabled required>
                <label for="adresse_ligne_2">Adresse ligne 2:</label>
                <input type="text" id="adresse_ligne_2" name="adresse_ligne_2" value="<?php echo $client['Adresse_ligne_2']; ?>" disabled>
                <label for="ville">Ville:</label>
                <input type="text" id="ville" name="ville" value="<?php echo $client['Ville']; ?>" disabled required>
                <button type="button" id="editButton" onclick="toggleEditMode()">Modifier</button>
                <button type="submit" name="update_profile" id="updateButton" class="edit-button">Enregistrer</button>
            </form>
        </div>

        <div class="table-section">
            <h2>Historique des consultations</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Agent immobilier</th>
                </tr>
                <?php while ($consultation = $consultations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d-m-Y', strtotime($consultation['Date_heure'])); ?></td>
                        <td><?php echo date('H:i', strtotime($consultation['Date_heure'])); ?></td>
                        <td><?php
                            $agent_id = $consultation['Id_Agent'];
                            $sql = "SELECT Nom, Prenom FROM Agent WHERE Id_Agent = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $agent_id);
                            $stmt->execute();
                            $agent_result = $stmt->get_result();
                            $agent = $agent_result->fetch_assoc();
                            echo $agent['Nom'] . ' ' . $agent['Prenom'];
                        ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <h2>Rendez-vous à venir</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Agent immobilier</th>
                    <th>Action</th>
                </tr>
                <?php while ($rdv = $rendez_vous->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d-m-Y', strtotime($rdv['Date_heure'])); ?></td>
                        <td><?php echo date('H:i', strtotime($rdv['Date_heure'])); ?></td>
                        <td><?php
                            $agent_id = $rdv['Id_Agent'];
                            $sql = "SELECT Nom, Prenom FROM Agent WHERE Id_Agent = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $agent_id);
                            $stmt->execute();
                            $agent_result = $stmt->get_result();
                            $agent = $agent_result->fetch_assoc();
                            echo $agent['Nom'] . ' ' . $agent['Prenom'];
                        ?></td>
                        <td><a href="client_dashboard.php?cancel_rdv=<?php echo $rdv['Id_Rendez_vous']; ?>">Annuler ce RDV</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Se déconnecter</button>
        </form>
    </div>
</body>
</html>
