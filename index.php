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
        <?php
        include ('nav.php')
        ?>
        <div class="container">
            <div class="welcome-section">
                <h2>Bienvenue chez Omnes Emmobilier</h2>
                <p>Bienvenue sur notre site. Nous sommes ravis de vous aider à trouver la maison de vos rêves. Explorez nos offres et contactez-nous pour plus d'informations.</p>
            </div>
            <div class="event-section">
                <h2><br>Évènement de la semaine</h2>
                <p>Cette semaine, nous organisons une porte ouverte pour vous permettre de découvrir nos nouvelles propriétés. Rejoignez-nous le samedi à partir de 10h.<br></p>
                <img src="Image\facade.png" class="d-block w-100" alt="Propriété 1">
            </div>

        </div>

        <div class="container">
            <div class="welcome-section">
                <h2>Propriétés en vogue</h2>
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
            <div class="welcome-section">
                <p>Pour en voir plus, consulter la page 'Tout Parcourir'.</p>
            </div>
        </div>

        <div class="container">
            <section>
                <h2>Qui sommes-nous ?</h2>
                <p>Omness Emmobilier est une agence immobilière unique, dédiée à la communauté emo. Fondée sur des valeurs d'authenticité, d'empathie et de compréhension, notre mission est de créer un espace où chaque individu peut trouver un foyer qui reflète sa véritable essence.</p>

                <h2>Notre Vision</h2>
                <p>Chez Omness Emmobilier, nous croyons que votre maison devrait être plus qu'un simple lieu de résidence. Elle doit être un sanctuaire,
                    un refuge où vous pouvez exprimer librement vos émotions et vous sentir en sécurité. Notre vision est de transformer le paysage immobilier en offrant des propriétés qui résonnent avec l'âme de chaque client, en mettant l'accent sur le confort, le style et la singularité.</p>

                <h2>Nos Services</h2>
                <p>Conseil personnalisé : Nous prenons le temps de comprendre vos besoins et vos aspirations pour vous proposer des biens qui correspondent à votre personnalité et à votre style de vie.
                    Sélection de propriétés : Des appartements cosy aux maisons gothiques, en passant par les lofts industriels, nous proposons une large gamme de biens immobiliers sélectionnés pour leur caractère unique et leur ambiance particulière.
                    Assistance complète : De la recherche du bien idéal à la finalisation de l'achat ou de la location, nous vous accompagnons à chaque étape pour garantir une expérience fluide et sans stress.
                    Communauté et soutien : Nous organisons régulièrement des événements et des rencontres pour renforcer les liens au sein de la communauté emo, créant ainsi un réseau de soutien et de solidarité.</p>

                <h2>Pourquoi choisir Omnes Emmobilier ?</h2>
                <p>Compréhension profonde : Notre équipe est composée de professionnels qui partagent vos goûts et votre sensibilité, garantissant ainsi une compréhension authentique de vos besoins.
                    Sélection exclusive : Nous avons accès à des propriétés exclusives qui ne sont pas disponibles sur le marché général, vous offrant des options uniques et souvent cachées.
                    Engagement et intégrité : Nous nous engageons à offrir un service transparent, honnête et axé sur le client, en mettant toujours vos intérêts en premier.</p>
            </section>
        </div>
        <?php
        include ('footer.php')
        ?>
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
