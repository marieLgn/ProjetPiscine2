<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        /* Ajoutez vos styles ici */
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Connexion</button>
        </form>
    </div>
</body>
</html>
