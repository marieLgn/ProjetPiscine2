<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['client_id'])) {
    echo "Erreur: paramètres manquants.";
    exit();
}

$user_id = $_SESSION['user_id'];
$client_id = $_GET['client_id'];

$sql = "SELECT * FROM Messages WHERE (Id_Sender = ? AND Id_Receiver = ?) OR (Id_Sender = ? AND Id_Receiver = ?) ORDER BY Timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $client_id, $client_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
