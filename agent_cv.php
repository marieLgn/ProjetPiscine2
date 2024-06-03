<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_agent'])) {
    header("Location: rendez_vous.php");
    exit();
}

$agent_id = $_GET['id_agent'];

// Récupérer les informations de l'agent
$sql = "SELECT * FROM agent WHERE Id_Agent = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$agent = $result->fetch_assoc();

if (!$agent) {
    echo "Agent non trouvé.";
    exit();
}

// Récupérer le CV de l'agent
$sql_cv = "SELECT * FROM cv WHERE Id_CV = ?";
$stmt_cv = $conn->prepare($sql_cv);
$stmt_cv->bind_param("i", $agent['Id_CV']);
$stmt_cv->execute();
$result_cv = $stmt_cv->get_result();
$cv = $result_cv->fetch_assoc();

// Récupérer l'emploi du temps de l'agent
$sql_schedule = "SELECT * FROM disponibilite WHERE Id_Agent = ?";
$stmt_schedule = $conn->prepare($sql_schedule);
$stmt_schedule->bind_param("i", $agent_id);
$stmt_schedule->execute();
$schedules = $stmt_schedule->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CV de l'Agent - Omnes Immobilier</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #1b1b1b;
            color: #c0c0c0;
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
            background-color: #ff4081;
            color: #000;
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
            background-color: #e5006b;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #2c2c2c;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .profile-section {
            margin-bottom: 20px;
        }

        .profile-section label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .profile-section input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            background-color: #333;
            color: #fff;
            border: none;
        }

        .schedule {
            display: grid;
            grid-template-columns: 80px repeat(6, 1fr);
            gap: 5px;
            margin-bottom: 20px;
        }

        .day, .time-label {
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .time-label {
            margin-top: 20px;
        }

        .time-slot {
            width: 100%;
            height: 30px;
            margin: 1px 0;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            cursor: pointer;
        }

        .free {
            background-color: white;
        }

        .busy {
            background-color: #ddd;
        }

        .non-working {
            background-color: black;
            color: white;
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
        <h2>CV de l'Agent</h2>
        <div class="profile-section">
            <h2>Informations personnelles</h2>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo $agent['Nom'] ?? 'N/A'; ?>" disabled>
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $agent['Prenom'] ?? 'N/A'; ?>" disabled>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $agent['Email'] ?? 'N/A'; ?>" disabled>
            <label for="telephone">Téléphone:</label>
            <input type="text" id="telephone" name="telephone" value="<?php echo $agent['No_Telephone'] ?? 'N/A'; ?>" disabled>
            <label for="code_postal">Code postal:</label>
            <input type="text" id="code_postal" name="code_postal" value="<?php echo $agent['Code_postal'] ?? 'N/A'; ?>" disabled>
            <label for="pays">Pays:</label>
            <input type="text" id="pays" name="pays" value="<?php echo $agent['Pays'] ?? 'N/A'; ?>" disabled>
            <label for="adresse_ligne_1">Adresse Ligne 1:</label>
            <input type="text" id="adresse_ligne_1" name="adresse_ligne_1" value="<?php echo $agent['Adresse_ligne_1'] ?? 'N/A'; ?>" disabled>
            <label for="adresse_ligne_2">Adresse Ligne 2:</label>
            <input type="text" id="adresse_ligne_2" name="adresse_ligne_2" value="<?php echo $agent['Adresse_ligne_2'] ?? 'N/A'; ?>" disabled>
            <label for="ville">Ville:</label>
            <input type="text" id="ville" name="ville" value="<?php echo $agent['Ville'] ?? 'N/A'; ?>" disabled>
        </div>
        <div class="profile-section">
            <h2>Informations sur le CV</h2>
            <label for="formation">Formation:</label>
            <input type="text" id="formation" name="formation" value="<?php echo $cv['Formation'] ?? 'N/A'; ?>" disabled>
            <label for="experiences">Expériences:</label>
            <input type="text" id="experiences" name="experiences" value="<?php echo $cv['Experiences'] ?? 'N/A'; ?>" disabled>
            <label for="competence">Compétences:</label>
            <input type="text" id="competence" name="competence" value="<?php echo $cv['Competence'] ?? 'N/A'; ?>" disabled>
            <label for="centre_interet">Centre d'intérêt:</label>
            <input type="text" id="centre_interet" name="centre_interet" value="<?php echo $cv['Centre_d_interet'] ?? 'N/A'; ?>" disabled>
        </div>
        <div class="profile-section">
            <h2>Emploi du temps</h2>
            <div class="schedule">
                <div class="time-label">
                    <div class="time-slot">08:00-09:00</div>
                    <div class="time-slot">09:00-10:00</div>
                    <div class="time-slot">10:00-11:00</div>
                    <div class="time-slot">11:00-12:00</div>
                    <div class="time-slot">12:00-13:00</div>
                    <div class="time-slot">13:00-14:00</div>
                    <div class="time-slot">14:00-15:00</div>
                    <div class="time-slot">15:00-16:00</div>
                    <div class="time-slot">16:00-17:00</div>
                    <div class="time-slot">17:00-18:00</div>
                </div>
                <?php
                $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                foreach ($days as $day):
                ?>
                    <div class="day">
                        <div><?php echo $day; ?></div>
                        <?php
                        for ($hour = 8; $hour < 18; $hour++) {
                            $time_slot = sprintf("%02d:00-%02d:00", $hour, $hour + 1);
                            $class = 'free';
                            foreach ($schedules as $schedule) {
                                if ($schedule['Creneau'] === "$day $time_slot") {
                                    $class = ($schedule['Disponible_Indisponible'] === 'Indisponible') ? 'non-working' : 'busy';
                                    break;
                                }
                            }
                            echo "<div class='time-slot $class'>&nbsp;</div>";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
    </footer>
</div>
</body>
</html>
