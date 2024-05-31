<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'emobillier2');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch property details
$id = intval($_GET['id']);
$sql = "SELECT * FROM propriete WHERE Id_Propriete = $id";
$result = $conn->query($sql);
$property = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la propriété</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Omnes Immobilier</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="browse.php">Tout parcourir</a></li>
                    <li><a href="search.php">Rechercher</a></li>
                    <li><a href="appointments.php">Rendez-vous</a></li>
                    <li><a href="account.php">Votre compte</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <h2><?php echo $property['Adresse']; ?></h2>
            <img src="<?php echo $property['Photo']; ?>" alt="Photo de la propriété">
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
            <a href="appointments.php?agent_id=<?php echo $property['Id_Agent']; ?>">Prendre rendez-vous avec l'agent</a>
        </main>

        <footer>
            <p>Contactez-nous : <a href="mailto:contact@omnesimmobilier.com">contact@omnesimmobilier.com</a> | Téléphone : 0123456789 | Adresse : 123 Rue de Paris, 75001 Paris</p>
            <div id="map"></div>
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
