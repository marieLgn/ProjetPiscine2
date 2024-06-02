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
            <p>&copy; 2024 Omnes Emmobilier. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>
