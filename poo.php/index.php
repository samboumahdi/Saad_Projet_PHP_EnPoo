<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header, nav, footer {
            background-color: #232f3e;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        main {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .buttons-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            margin: 10px;
            padding: 20px 40px; /* Ajustez la taille selon vos besoins */
            background-color: #3498db; /* Couleur bleue */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background-color: #2980b9; /* Couleur bleue plus foncée au survol */
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenue sur notre site</h1>
</header>

<nav>
    <h2> WELCOME </h2>
    <!-- Ajoutez ici vos liens de navigation -->
</nav>

<main>
    <div class="buttons-container">
        <a href="login.php" class="btn">Se Connecter</a>
        <a href="register.php" class="btn">S'inscrire</a>
        <!-- <a href="accueil_client.php" class="btn">accueil_client</a> -->
    </div>
</main>

<footer>
    <p>&copy; 2023 le propriétaire du site saad boumahdi </p>
    
    <!-- Ajoutez le lien Instagram ici -->
    <a href="https://www.instagram.com/sam.11.b?igsh=OGQ5ZDc2ODk2ZA==" target="_blank" style="color: #ff0000;">Vers mon Instagram</a>
</footer>




</body>
</html>

