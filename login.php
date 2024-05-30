<?php
session_start();
include('config.php');

function login($email, $password) {
    global $conn;
    // Vérification pour tous les utilisateurs
    $sql = "
        SELECT Id_Client AS user_id, 'client' AS user_type, Mot_de_passe FROM Client WHERE Email = ? UNION ALL
        SELECT Id_Agent AS user_id, 'agent' AS user_type, Mot_de_passe FROM Agent WHERE Email = ? UNION ALL
        SELECT Id_Administrateur AS user_id, 'admin' AS user_type, Mot_de_passe FROM Administrateur WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($user = $result->fetch_assoc()) {
            if ($password === $user['Mot_de_passe']) {  // Comparaison en clair
                $_SESSION['user_id'] = $user['user_id'];
                // Définir le niveau de l'utilisateur en fonction de l'ID
                if (substr($user['user_id'], 0, 1) == '1') {
                    $_SESSION['user_level'] = 1;
                } elseif (substr($user['user_id'], 0, 1) == '2') {
                    $_SESSION['user_level'] = 2;
                } elseif (substr($user['user_id'], 0, 1) == '3') {
                    $_SESSION['user_level'] = 3;
                }
                return true;
            }
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        if (login($email, $password)) {
            header("Location: dashboard.php");
        } else {
            echo "Invalid login credentials.";
        }
    }
}
?>
