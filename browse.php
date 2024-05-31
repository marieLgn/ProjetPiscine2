<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tout Parcourir - Omnes Immobilier</title>
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
        .category {
            margin-bottom: 20px;
        }
        .category h3 {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            margin: 0;
        }
        .category ul {
            list-style-type: none;
            padding: 0;
        }
        .category ul li {
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }
        .category ul li a {
            text-decoration: none;
            color: #007BFF;
        }
        .category ul li a:hover {
            text-decoration: underline;
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
            <a href="account.php">Votre compte</a>
        </nav>
        <div class="container">
            <h2>Tout Parcourir</h2>
            <div class="categories">
                <div class="category">
                    <h3>Immobilier résidentiel</h3>
                    <ul>
                        <?php
                        include 'config.php';

                        $sql = "SELECT * FROM propriete WHERE Type_propriete = 'Résidentiel'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<li><a href='property.php?id=" . $row['Id_Propriete'] . "'>" . $row['Adresse'] . "</a></li>";
                            }
                        } else {
                            echo "<li>Aucune propriété disponible.</li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="category">
                    <h3>Immobilier commercial</h3>
                    <ul>
                        <?php
                        $sql = "SELECT * FROM propriete WHERE Type_propriete = 'Commercial'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<li><a href='property.php?id=" . $row['Id_Propriete'] . "'>" . $row['Adresse'] . "</a></li>";
                            }
                        } else {
                            echo "<li>Aucune propriété disponible.</li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="category">
                    <h3>Terrain</h3>
                    <ul>
                        <?php
                        $sql = "SELECT * FROM propriete WHERE Type_propriete = 'Terrain'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<li><a href='property.php?id=" . $row['Id_Propriete'] . "'>" . $row['Adresse'] . "</a></li>";
                            }
                        } else {
                            echo "<li>Aucune propriété disponible.</li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="category">
                    <h3>Appartement à louer</h3>
                    <ul>
                        <?php
                        $sql = "SELECT * FROM propriete WHERE Type_propriete = 'Appartement à louer'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<li><a href='property.php?id=" . $row['Id_Propriete'] . "'>" . $row['Adresse'] . "</a></li>";
                            }
                        } else {
                            echo "<li>Aucune propriété disponible.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
            <p>Email : contact@omnesimmobilier.com | Téléphone : 01 23 45 67 89</p>
            <div id="map" style="height: 200px; width: 100%;"></div>
        </footer>
    </div>
</body>
</html>
