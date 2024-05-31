<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        /* Ajoutez vos styles ici */
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Inscription</h2>
        <form action="register.php" method="POST">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <label for="user_level">Type d'utilisateur:</label>
            <select id="user_level" name="user_level" required>
                <option value="1">Client</option>
                <option value="2">Agent</option>
                <option value="3">Administrateur</option>
            </select>
            <button type="submit" name="register">Inscription</button>
        </form>
    </div>
</body>
</html>
