<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">

    <h1 style="display: flex; align-items: center;">
            <img src="Image\Logo.jpg" style="height: 10%; width: 10%; margin-right: 600px;" alt="Propriété 1"> Omnes Emmobilier
            </h1>
</head>
<body style="font-family: 'Courier New', monospace; background-color: #1b1b1b; color: #c0c0c0; margin: 0; padding: 0;">
    <nav>
        <a href="index.php">Accueil</a>
        <a href="browse.php">Tout parcourir</a>
        <a href="search.php">Rechercher</a>
        <a href="rendez_vous.php">Rendez-vous</a>
        <a href="login.php">Votre compte</a>
    </nav>
    <div class="register-container" style="max-width: 500px; width: 100%; padding: 20px; border-radius: 10px; background-color: #2c2c2c; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); margin: 50px auto;">
        <h2 style="text-align: center; margin-bottom: 20px;">Inscription</h2>
        <form action="register.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="username" style="display: block; font-weight: bold; color: #ff4081; margin-bottom: 5px;">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required style="width: 100%; padding: 10px; border: 1px solid #555; border-radius: 5px; background-color: #1b1b1b; color: #c0c0c0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="email" style="display: block; font-weight: bold; color: #ff4081; margin-bottom: 5px;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #555; border-radius: 5px; background-color: #1b1b1b; color: #c0c0c0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; font-weight: bold; color: #ff4081; margin-bottom: 5px;">Mot de passe:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #555; border-radius: 5px; background-color: #1b1b1b; color: #c0c0c0;">
            </div>
            <div style="margin-bottom: 20px;">
                <label for="user_level" style="display: block; font-weight: bold; color: #ff4081; margin-bottom: 5px;">Type d'utilisateur:</label>
                <select id="user_level" name="user_level" required style="width: 100%; padding: 10px; border: 1px solid #555; border-radius: 5px; background-color: #1b1b1b; color: #c0c0c0;">
                    <option value="1">Client</option>
                    <option value="2">Agent</option>
                    <option value="3">Administrateur</option>
                </select>
            </div>
            <button type="submit" name="register" style="width: 100%; padding: 10px; background-color: #ff4081; color: #000; border: none; border-radius: 5px; cursor: pointer;">Inscription</button>
        </form>
    </div>
</body>
</html>
