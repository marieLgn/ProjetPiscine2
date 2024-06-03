<?php
session_start();
include('config.php');

function register($username, $email, $password, $user_level) {
    global $conn;

    if ($user_level == 1) {
        $prefix = '1';
        $sql = "INSERT INTO Client (Id_Client, Nom, Email, Mot_de_passe) VALUES (CONCAT('$prefix', LPAD(FLOOR(RAND() * 99999), 5, '0')), ?, ?, ?)";
    } elseif ($user_level == 2) {
        $prefix = '2';
        $sql = "INSERT INTO Agent (Id_Agent, Nom, Email, Mot_de_passe) VALUES (CONCAT('$prefix', LPAD(FLOOR(RAND() * 99999), 5, '0')), ?, ?, ?)";
    } elseif ($user_level == 3) {
        $prefix = '3';
        $sql = "INSERT INTO Administrateur (Id_Administrateur, Email, Mot_de_passe) VALUES (CONCAT('$prefix', LPAD(FLOOR(RAND() * 99999), 5, '0')), ?, ?)";
    }

    $stmt = $conn->prepare($sql);

    if ($user_level == 1 || $user_level == 2) {
        $stmt->bind_param("sss", $username, $email, $password);
    } elseif ($user_level == 3) {
        $stmt->bind_param("ss", $email, $password);
    }

    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user_level = $_POST['user_level'];  // Capture du niveau utilisateur
        if (register($username, $email, $password, $user_level)) {
            echo "Registration successful.";
        } else {
            echo "Error in registration.";
        }
    }
}
?>