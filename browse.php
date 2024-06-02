<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tout Parcourir - Omnes Immobilier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="wrapper">
        <?php
        include ('nav.php')
        ?>
        <div class="container" >
            <h2>Tout Parcourir</h2>
            <div class="btn-group mb-3" role="group" aria-label="Filtrer les biens immobiliers">
                <button type="button" class="btn btn-primary filter-btn" data-filter="all">Tous</button>
                <button type="button" class="btn btn-primary filter-btn" data-filter="Résidentiel">Immobilier résidentiel</button>
                <button type="button" class="btn btn-primary filter-btn" data-filter="Commercial">Immobilier commercial</button>
                <button type="button" class="btn btn-primary filter-btn" data-filter="Terrain">Terrain</button>
                <button type="button" class="btn btn-primary filter-btn" data-filter="Appartement à louer">Appartement à louer</button>
            </div>
            <div class="row">
                <?php
                include 'config.php';

                $sql = "SELECT * FROM propriete";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='col-md-4 mb-4 property-item' data-type='" . $row['Type_propriete'] . "'>";
                        echo "<div class='card'>";
                        echo "<img src='" . $row['Image'] . "' class='card-img-top' alt='" . $row['Adresse'] . "'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . $row['Adresse'] . "</h5>";
                        echo "<a href='property.php?id=" . $row['Id_Propriete'] . "' class='btn btn-primary'>Voir plus</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Aucune propriété disponible.</p>";
                }
                ?>
            </div>
        </div>
        <?php
        include ('footer.php')
        ?>
    </div>

    <!-- Lien vers Bootstrap JS et jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const propertyItems = document.querySelectorAll('.property-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    propertyItems.forEach(item => {
                        if (filter === 'all' || item.getAttribute('data-type') === filter) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
