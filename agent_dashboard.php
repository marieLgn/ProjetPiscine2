<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 2) {
    header("Location: login.html");
    exit();
}

$agent_id = $_SESSION['user_id'];

// Récupérer les rendez-vous à venir
$sql = "SELECT Rendez_vous.Date_heure, Client.Nom, Client.Prenom, Client.No_Telephone
        FROM Rendez_vous
        JOIN Client ON Rendez_vous.Id_Client = Client.Id_Client
        WHERE Rendez_vous.Id_Agent = ? AND Rendez_vous.Date_heure > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$rendez_vous = $stmt->get_result();

// Récupérer les clients suivis par l'agent
$sql = "SELECT DISTINCT Client.Id_Client, Client.Nom, Client.Prenom
        FROM Communiquer
        JOIN Client ON Communiquer.Id_Client = Client.Id_Client
        WHERE Communiquer.Id_Agent = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$clients = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord agent immobilier</title>
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

        .table-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
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
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur votre tableau de bord agent immobilier</h1>

        <div class="table-section">
            <h2>Rendez-vous à venir</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Nom du client</th>
                    <th>Prénom du client</th>
                    <th>Numéro de téléphone</th>
                </tr>
                <?php while ($rdv = $rendez_vous->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d-m-Y', strtotime($rdv['Date_heure'])); ?></td>
                        <td><?php echo date('H:i', strtotime($rdv['Date_heure'])); ?></td>
                        <td><?php echo $rdv['Nom']; ?></td>
                        <td><?php echo $rdv['Prenom']; ?></td>
                        <td><?php echo $rdv['No_Telephone']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="table-section">
            <h2>Mes clients</h2>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Action</th>
                </tr>
                <?php while ($client = $clients->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $client['Nom']; ?></td>
                        <td><?php echo $client['Prenom']; ?></td>
                        <td><a href="view_client.php?id=<?php echo $client['Id_Client']; ?>">Voir le dossier</a></td>
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
