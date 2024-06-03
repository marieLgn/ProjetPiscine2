<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function getTimeSlotClass($agent_schedules, $day, $time) {
    foreach ($agent_schedules as $schedule) {
        if ($schedule['Creneau'] == "$day $time") {
            return ($schedule['Disponible_Indisponible'] == 'Indisponible') ? 'non-working' : 'busy';
        }
    }
    return 'free';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_appointment'])) {
    $agent_id = $_POST['agent_id'];
    $day = $_POST['day'];
    $time = $_POST['time'];
    $user_id = $_SESSION['user_id'];
    $creanaux = "$day $time";
    $id_paiement = 0; 
    $date_heure = date('Y-m-d H:i:s', strtotime("$day $time"));

    $check_query = "SELECT * FROM disponibilite WHERE Id_Agent = ? AND Creneau = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("is", $agent_id, $creanaux);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $conn->begin_transaction(); 
        try {
            $insert_query = "INSERT INTO Rendez_vous (Id_Agent, Date_heure, Id_Client, Id_Paiement) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isii", $agent_id, $date_heure, $user_id, $id_paiement);
            $stmt->execute();

            $insert_dispo_query = "INSERT INTO disponibilite (Id_Agent, Creneau, Disponible_Indisponible) VALUES (?, ?, 'Indisponible')";
            $stmt = $conn->prepare($insert_dispo_query);
            $stmt->bind_param("is", $agent_id, $creanaux);
            $stmt->execute();

            $conn->commit(); 

            echo "<script>alert('Rendez-vous pris avec succès !');</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Une erreur s\'est produite lors de la prise du rendez-vous.');</script>";
        }
    } else {
        echo "<script>alert('Le créneau demandé n\'est plus disponible.');</script>";
    }
}

$sql = "SELECT * FROM agent";
$agents = $conn->query($sql);

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
            width: 100%;
            background-color: #000;
            color: #ff4081;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-sizing: border-box;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

        .view-cv {
            margin-top: 10px;
        }
    </style>
    <script>
        function scheduleAppointment(agentId, day, time) {
            if (confirm(`Voulez-vous prendre rendez-vous à ${time} le ${day} ?`)) {
                document.getElementById('agent_id').value = agentId;
                document.getElementById('day').value = day;
                document.getElementById('time').value = time;
                document.getElementById('appointmentForm').submit();
            }
        }
    </script>
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
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '08:00-09:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '08:00-09:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '09:00-10:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '09:00-10:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '10:00-11:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '10:00-11:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '11:00-12:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '11:00-12:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '12:00-13:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '12:00-13:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '13:00-14:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '13:00-14:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '14:00-15:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '14:00-15:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '15:00-16:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '15:00-16:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '16:00-17:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '16:00-17:00')">&nbsp;</div>
                                <div class="time-slot <?php echo getTimeSlotClass($agent_schedules[$agent['Id_Agent']], $day, '17:00-18:00'); ?>" onclick="scheduleAppointment(<?php echo $agent['Id_Agent']; ?>, '<?php echo $day; ?>', '17:00-18:00')">&nbsp;</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <form method="GET" action="agent_cv.php" class="view-cv">
                        <input type="hidden" name="id_agent" value="<?php echo $agent['Id_Agent']; ?>">
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

<form id="appointmentForm" method="POST" style="display: none;">
    <input type="hidden" name="agent_id" id="agent_id">
    <input type="hidden" name="day" id="day">
    <input type="hidden" name="time" id="time">
    <input type="hidden" name="schedule_appointment" value="1">
</form>
</body>
</html>
