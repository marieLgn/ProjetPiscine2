<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

// Fonction pour vérifier les créneaux
function getTimeSlotClass($agent_schedules, $day, $time) {
    foreach ($agent_schedules as $schedule) {
        if ($schedule['Creneau'] == "$day $time") {
            return ($schedule['Disponible_Indisponible'] == 'Indisponible') ? 'non-working' : 'busy';
        }
    }
    return 'free';
}

// Récupérer tous les agents
$sql = "SELECT * FROM agent";
$agents = $conn->query($sql);

// Récupérer les créneaux de rendez-vous pour chaque agent
$agent_schedules = [];
if ($agents->num_rows > 0) {
    while ($agent = $agents->fetch_assoc()) {
        $agent_id = $agent['Id_Agent'];
        $sql_schedule = "SELECT * FROM disponibilite WHERE Id_Agent = $agent_id";
        $schedules = $conn->query($sql_schedule);
        $agent_schedules[$agent_id] = $schedules->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rendez-vous - Omnes Immobilier</title>
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

        .view-cv {
            margin-top: 10px;
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
            <a href="login.html">Votre compte</a> <!-- Lien mis à jour -->
        </nav>
        <div class="container">
            <h2>Rendez-vous</h2>
            <?php if ($agents->num_rows > 0): ?>
                <?php foreach ($agents as $agent): ?>
                    <div class="agent-schedule">
                        <h3><?php echo $agent['Prenom'] . ' ' . $agent['Nom']; ?></h3>
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
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '08:00-09:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '09:00-10:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '10:00-11:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '11:00-12:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '12:00-13:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '13:00-14:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '14:00-15:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '15:00-16:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '16:00-17:00'); ?>">&nbsp;</div>
                                    <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '17:00-18:00'); ?>">&nbsp;</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <form method="GET" action="view_cv.php" class="view-cv">
                            <input type="hidden" name="id_cv" value="<?php echo $agent['Id_CV']; ?>">
                            <button type="submit">Voir CV</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun agent disponible.</p>
            <?php endif; ?>
        </div>
        <footer>
            <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>
