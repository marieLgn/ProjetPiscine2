<?php
include 'config.php';
session_start(); // Démarre la session pour vérifier si l'utilisateur est connecté

// Fetch property details
$id = intval($_GET['id']);
$sql = "SELECT * FROM propriete WHERE Id_Propriete = $id";
$result = $conn->query($sql);
$property = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la propriété - Omnes Immobilier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        nav {
            background-color: #007BFF;
            color: white;
            display: flex;
            justify-content: space-around;
            padding: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        nav a:hover {
            background-color: #0056b3;
        }
        .container {
            flex: 1;
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Omnes Immobilier</h1>
        </header>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="browse.php">Tout parcourir</a>
            <a href="search.php">Rechercher</a>
            <a href="rendez_vous.php">Rendez-vous</a>
            <a href="login.php">Votre compte</a>
        </nav>
        <div class="container">
            <h2><?php echo $property['Adresse']; ?></h2>
            <img src="<?php echo isset($property['Photo']) ? $property['Photo'] : 'default.jpg'; ?>" alt="Photo de la propriété" class="img-fluid">
            <p><?php echo $property['Description']; ?></p>
            <ul>
                <li>Ville: <?php echo $property['Ville']; ?></li>
                <li>Code postal: <?php echo $property['Code_postal']; ?></li>
                <li>Nombre de pièces: <?php echo $property['Nombre_piece']; ?></li>
                <li>Dimensions: <?php echo $property['Dimensions']; ?></li>
                <li>Étage: <?php echo $property['Etage']; ?></li>
                <li>Statut: <?php echo $property['Statut']; ?></li>
                <li>Prix: <?php echo $property['Prix']; ?>€</li>
            </ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="rendez_vous.php?agent_id=<?php echo isset($property['Id_Agent']) ? $property['Id_Agent'] : 'N/A'; ?>" class="btn btn-primary">Prendre rendez-vous avec l'agent</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary">Prendre rendez-vous avec l'agent</a>
            <?php endif; ?>
        </div>
        <footer>
            <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
            <p>Contactez-nous : <a href="mailto:contact@omnesimmobilier.com">contact@omnesimmobilier.com</a> | Téléphone : 0123456789 | Adresse : 123 Rue de Paris, 75001 Paris</p>
            <div id="map" style="height: 200px; width: 100%;"></div>
            <script>
                function initMap() {
                    var location = {lat: 48.8566, lng: 2.3522};
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 14,
                        center: location
                    });
                    var marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                }
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
        </footer>
    </div>
</body>
</html>
