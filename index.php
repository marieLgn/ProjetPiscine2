<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Omnes Immobilier</title>
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
        .carousel-item img {
            max-height: 400px;
            object-fit: cover;
            width: 100%;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 20px;
        }
        .event-section, .welcome-section {
            margin-bottom: 20px;
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
            <div class="welcome-section">
                <h2>Bienvenue chez Omnes Immobilier</h2>
                <p>Bienvenue sur notre site. Nous sommes ravis de vous aider à trouver la maison de vos rêves. Explorez nos offres et contactez-nous pour plus d'informations.</p>
            </div>
            <div class="event-section">
                <h2>Évènement de la semaine</h2>
                <p>Cette semaine, nous organisons une porte ouverte pour vous permettre de découvrir nos nouvelles propriétés. Rejoignez-nous le samedi à partir de 10h.</p>
            </div>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="property1.jpg" class="d-block w-100" alt="Propriété 1">
                    </div>
                    <div class="carousel-item">
                        <img src="property2.jpg" class="d-block w-100" alt="Propriété 2">
                    </div>
                    <div class="carousel-item">
                        <img src="property3.jpg" class="d-block w-100" alt="Propriété 3">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Précédent</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Suivant</span>
                </a>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
            <p>Email : contact@omnesimmobilier.com | Téléphone : 01 23 45 67 89</p>
            <div id="map" style="height: 200px; width: 100%;"></div>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function initMap() {
            var uluru = {lat: -25.344, lng: 131.036};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
</body>
</html>
