<?php
// Inclusion du fichier de configuration contenant la connexion à la base de données
include("config.php");

class ClientPage {
    private $conn;

    public function __construct($host, $db, $user, $pass, $port = '3306') {
        $this->conn = new mysqli($host, $user, $pass, $db, $port);
        if ($this->conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $this->conn->connect_error);
        }
    }

    public function checkSession() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

$client_page = new ClientPage($host, $db, $user, $pass, $port);
$client_page->checkSession();
$client_page->closeConnection();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
        }

        /* Styles pour le conteneur du tableau de bord */
        .accueil_client-container {
            max-width: 800px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            margin-bottom: 30px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }

        /* Styles pour le bouton du tableau de bord */
        .accueil_client-button {
            display: block;
            margin: 10px auto;
            padding: 15px 30px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s;
        }

        .accueil_client-button:hover {
            background-color: #0056b3;
            color: #fff; /* Changé de couleur lors du survol */
        }
    </style>
</head>
<body>

<!-- Contenu du tableau de bord -->
<div class="accueil_client-container">
    <h2>Bienvenue sur votre tableau de bord</h2>
    <p>Explorez toutes les fonctionnalités à votre disposition.</p>

    <!-- Liens vers d'autres pages du site en haut -->
    <!-- Lien vers le site de vente -->
    <a class="accueil_client-button" href="Accueil_liste_produit.php">Découvrir le site</a>
    <!-- Lien vers les commandes qui sont en cours  du profil -->
    <a class="accueil_client-button" href="Liste_command_client.php">Mes commandes en cours</a>
        <!-- Lien vers la modification du profil -->
    <a class="accueil_client-button" href="Modifier_profil.php">Modifier mon profil</a>
    <!-- Lien vers la déconnexion -->
    <a class="accueil_client-button" href="Logout_client.php">Se déconnecter</a>
    <!-- Lien vers la suppression du compte -->
    <a class="accueil_client-button" href="Supprimer_mon_compte.php">Supprimer mon compte</a>
</div>

</body>
</html>
