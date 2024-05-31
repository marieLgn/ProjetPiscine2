<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher - Omnes Immobilier</title>
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
        .search-form {
            margin-bottom: 20px;
        }
        .search-form label {
            font-weight: bold;
        }
        .search-results {
            margin-top: 20px;
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
            <h2>Rechercher</h2>
            <form class="search-form" method="GET" action="search.php">
                <div class="form-group">
                    <label for="searchType">Type de recherche :</label>
                    <select class="form-control" id="searchType" name="searchType" required>
                        <option value="agent">Agent</option>
                        <option value="property">Propriété</option>
                        <option value="city">Ville</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchQuery">Recherche :</label>
                    <input type="text" class="form-control" id="searchQuery" name="searchQuery" required>
                </div>
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
            <div class="search-results">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['searchType']) && isset($_GET['searchQuery'])) {
                    include 'config.php';

                    $searchType = $_GET['searchType'];
                    $searchQuery = $conn->real_escape_string($_GET['searchQuery']);

                    if ($searchType == 'agent') {
                        $sql = "SELECT * FROM agent WHERE Nom LIKE '%$searchQuery%' OR Prenom LIKE '%$searchQuery%'";
                    } elseif ($searchType == 'property') {
                        $sql = "SELECT * FROM propriete WHERE Id_Propriete LIKE '%$searchQuery%'";
                    } elseif ($searchType == 'city') {
                        $sql = "SELECT * FROM propriete WHERE Ville LIKE '%$searchQuery%'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<ul>";
                        while($row = $result->fetch_assoc()) {
                            if ($searchType == 'agent') {
                                echo "<li>Agent : " . $row['Nom'] . " " . $row['Prenom'] . " - Email : " . $row['Email'] . "</li>";
                            } elseif ($searchType == 'property' || $searchType == 'city') {
                                echo "<li>Propriété : <a href='property.php?id=" . $row['Id_Propriete'] . "'>" . $row['Adresse'] . ", " . $row['Ville'] . "</a></li>";
                            }
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Aucun résultat trouvé.</p>";
                    }

                    $conn->close();
                }
                ?>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>