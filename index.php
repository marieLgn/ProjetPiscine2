<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Omnes Emmobilier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header>
        
            <h1 style="display: flex; align-items: center;">
            <img src="Image\Logo.jpg" style="height: 10%; width: 10%; margin-right: 550px;" alt="Propriété 1"> Omnes Emmobilier
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
            <div class="welcome-section">
                <h2>Bienvenue chez Omnes Emobilier</h2>
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
                        <img src="Image\property1.jpg" class="d-block w-100" alt="Propriété 1">
                    </div>
                    <div class="carousel-item">
                        <img src="Image\property2.jpg" class="d-block w-100" alt="Propriété 2">
                    </div>
                    <div class="carousel-item">
                        <img src="Image\property3.jpg" class="d-block w-100" alt="Propriété 3">
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
            <p>&copy; 2024 Omnes Emmobilier. Tous droits réservés.</p>
            <p>Email : contact@omnesemmobilier.com | Téléphone : 01 23 45 67 89</p>
            <div id="map" style="height: 1px; width: 100%;"></div>
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
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9999447347695!2d2.294481315674134!3d48.85884407928711!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fdd7e75adbf%3A0x8f1f9b0f5f4e7f8c!2sTour%20Eiffel!5e0!3m2!1sen!2sfr!4v1597045516535!5m2!1sen!2sfr" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
</body>
</html>
