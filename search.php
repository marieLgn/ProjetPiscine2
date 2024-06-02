<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher - Omnes Emmobilier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <?php
        include ('nav.php')
        ?>
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
        <?php
        include ('footer.php')
        ?>
    </div>
</body>
</html>