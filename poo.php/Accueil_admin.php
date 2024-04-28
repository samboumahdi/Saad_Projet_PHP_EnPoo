<?php
// Classe pour gérer les sessions utilisateur
class SessionManager {
    // Méthode pour démarrer la session
    public static function startSession() {
        session_start();
    }

    // Méthode pour vérifier si l'utilisateur est connecté
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Méthode pour rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    public static function redirectToLogin() {
        header('Location: login.php');
        exit;
    }
}

// Démarrer la session
SessionManager::startSession();

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!SessionManager::isLoggedIn()) {
    SessionManager::redirectToLogin();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil administrateur</title>
    <meta charset="utf-8">
    <style>
        /* Styles pour la mise en page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: space-around;
            background-color: #007bff;
            padding: 10px;
        }

        nav a {
            text-decoration: none;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #0056b3;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Accueil administrateur</h1>
    </header>

    <!-- Barre de navigation avec des liens vers différentes pages d'administration -->
    <nav>
        <a href="Gestion_utilisateurs.php">Gérer utilisateurs</a>
        <br>
        <a href="Gestion_produit.php">Gérer les produits</a>
        <br>
        <a href="publier-produit.php">Ajouter un nouveau produit</a>
        <br>
        <a href="Liste_command_admin.php" id="orders">Consulter les commandes en cours</a>
        <br>
        <a href="Logout_Admin.php">Se déconnecter</a>
    </nav>

    <!-- Contenu principal de la page -->
    <main>
    </main>
</body>
</html>
