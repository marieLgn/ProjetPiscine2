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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1 style="display: flex; align-items: center;">
            <img src="Image/Logo.jpg" style="height: 10%; width: 10%; margin-right: 550px;" alt="Logo"> Omnes Emmobilier
            </h1>
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
            <p>&copy; 2024 Omnes Emmobilier. Tous droits réservés.</p>
            <p>Contactez-nous : <a href="mailto:contact@emnesemmobilier.com">contact@emnesimmobilier.com</a> | Téléphone : 0123456789 | Adresse : 123 Rue de Paris, 75001 Paris</p>
            <div id="map" style="height: 1px; width: 100%;"></div>
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
        </footer>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9999447347695!2d2.294481315674134!3d48.85884407928711!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fdd7e75adbf%3A0x8f1f9b0f5f4e7f8c!2sTour%20Eiffel!5e0!3m2!1sen!2sfr!4v1597045516535!5m2!1sen!2sfr" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
</body>
</html>