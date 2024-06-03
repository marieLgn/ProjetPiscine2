<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">

    <h1 style="display: flex; align-items: center;">
            <img src="Image\Logo.jpg" style="height: 10%; width: 10%; margin-right: 600px;" alt="Propriété 1"> Omnes Emmobilier
            </h1>
    <nav>
            <a href="index.php">Accueil</a>
            <a href="browse.php">Tout parcourir</a>
            <a href="search.php">Rechercher</a>
            <a href="rendez_vous.php">Rendez-vous</a>
            <a href="login.php">Votre compte</a>
    </nav>
</head>
<body>
    <div class="login-container">

    <!--<a href="index.php" class="back-button">Retour</a>-->
        <h2>Se connecter</h2>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Connexion</button>
        </form>
        <p>Pas de compte ? <a href="register_form.php" class="btn btn-secondary">S'inscrire</a></p>
    </div>
</body>
</html>