<?php
// Classe pour gérer la session utilisateur et la connexion à la base de données
class AppManager {
    private $conn;

    // Constructeur prenant la connexion à la base de données en paramètre
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour démarrer la session PHP
    public function startSession() {
        session_start();
    }

    // Méthode pour vérifier si l'utilisateur est connecté
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Méthode pour rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    public function redirectToLogin() {
        header('Location: login.php');
        exit;
    }

    // Méthode pour récupérer les commandes en cours avec éventuellement un terme de recherche
    public function getOrders($search = null) {
        $query = "SELECT id, ref, date, total FROM user_order";

        // Ajoute la condition de recherche si un terme est spécifié
        if (!empty($search)) {
            $query .= ' WHERE ref LIKE ?';
        }

        $stmt = mysqli_prepare($this->conn, $query);

        // Vérification de la préparation de la requête
        if (!$stmt) {
            die('Erreur de préparation de la requête: ' . mysqli_error($this->conn));
        }

        // Ajoute le bind_param si un terme de recherche est spécifié
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            mysqli_stmt_bind_param($stmt, 's', $searchTerm);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        return $orders;
    }
}

// Inclure le fichier de configuration qui contient la connexion à la base de données
include('config.php');

// Création d'une instance de la classe AppManager en passant la connexion à la base de données en paramètre
$appManager = new AppManager($conn);

// Démarrer la session PHP
$appManager->startSession();

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!$appManager->isLoggedIn()) {
    $appManager->redirectToLogin();
}

// Récupération du terme de recherche
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;

// Récupération des commandes en cours
$orders = $appManager->getOrders($searchTerm);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Commandes en cours</title>
    <meta charset="utf-8">
    <style>
        /* Ajoutez vos styles pour afficher les commandes ici */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .search-form {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        /* Ajoutez cette règle pour ajuster la taille du bouton "Retour" */
        .back-button {
            display: inline-block;
            padding: 10px 20px; /* Ajustez les valeurs selon vos besoins */
            font-size: 16px;   /* Ajustez la taille de la police selon vos besoins */
            text-decoration: none;
            background-color: #4CAF50; /* Couleur de fond du bouton */
            color: white; /* Couleur du texte du bouton */
            border: none;
            border-radius: 5px; /* Coins arrondis du bouton */
        }
    </style>
</head>
<body>
    <h2>Commandes en cours</h2>

    <!-- Formulaire de recherche -->
    <form method="GET" class="search-form">
        <label for="search">Rechercher par référence :</label>
        <input type="text" name="search" id="search" placeholder="Entrez la référence">
        <button type="submit">Rechercher</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>Référence</th>
                <th>Date</th>
                <th>Prix Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['ref'] ?></td>
                    <td><?= $order['date'] ?></td>
                    <td><?= $order['total'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Bouton "Retour" pour revenir à la page d'accueil de l'administrateur -->
    <a href="Accueil_admin.php" class="back-button">Retour</a>
</body>
</html>
