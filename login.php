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
            exit();
        } else {
            $login_error = "Invalid login credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="wrapper">
    <?php
    include ('nav.php')
    ?>
    <div class="login-container">
        <h2>Se connecter</h2>
        <?php if (isset($login_error)) echo "<p style='color: red; text-align: center;'>$login_error</p>"; ?>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Connexion</button>
        </form>
        <p>Pas de compte ? <a href="register_form.php" class="btn btn-secondary">S'inscrire</a></p>
    </div>
</div>
</body>
<?php
include ('footer.php')
?>
</html>
