<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['message']) || !isset($_POST['receiver_id'])) {
    echo "Erreur: paramètres manquants.";
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

$sql = "INSERT INTO Messages (Id_Sender, Id_Receiver, Message_Text) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);

if ($stmt->execute()) {
    echo "Message envoyé.";
} else {
    echo "Erreur: message non envoyé.";
}
?>