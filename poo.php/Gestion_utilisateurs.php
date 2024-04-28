<?php
// Démarrage de la session
session_start();

// Inclusion du fichier de configuration pour la connexion à la base de données
include('config.php');

// Classe pour gérer les opérations liées aux utilisateurs
class UserManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour récupérer le rôle de l'utilisateur en cours
    public function getUserRole($user_id) {
        $stmt = mysqli_prepare($this->conn, 'SELECT role_id FROM user WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userRole);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $userRole;
    }

    // Méthode pour récupérer les utilisateurs en fonction du rôle de l'utilisateur en cours
    public function getUsersByRole($userRole) {
        if ($userRole == 2) {
            return mysqli_query($this->conn, 'SELECT * FROM user WHERE role_id = 3');
        } else if ($userRole == 1) {
            return mysqli_query($this->conn, 'SELECT * FROM user WHERE role_id != 1');
        }
        return null;
    }
}

// Vérification de la connexion à la base de données
if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Création d'une instance de la classe UserManager
    $userManager = new UserManager($conn);

    // Récupération du rôle de l'utilisateur en cours
    $userRole = $userManager->getUserRole($user_id);

    // Récupération des utilisateurs en fonction du rôle de l'utilisateur en cours
    $recupUtilisateurs = $userManager->getUsersByRole($userRole);
} else {
    echo "Utilisateur non connecté";
    exit;
}

// Affichage des utilisateurs
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afficher les utilisateurs</title>
</head>
<body>
    <header>
        <h1>User Management</h1>
    </header>

    <nav>
        <a href="Accueil_admin.php">Retour à l\'Accueil</a>
    </nav>

    <main>';

if ($recupUtilisateurs && mysqli_num_rows($recupUtilisateurs) > 0) {
    while ($utilisateur = mysqli_fetch_assoc($recupUtilisateurs)) {
        echo '<p>
            Username: ' . $utilisateur["user_name"] . '<br>
            Role: ' . ($utilisateur["role_id"] == 2 ? 'Admin' : 'Client') . '<br>
            <a href="Supprimer_utilisateur.php?id=' . $utilisateur['id'] . '" class="btn btn-primary">Supprimer l\'utilisateur</a><br>
            <a href="Change_statut_utilisateur.php?id=' . $utilisateur['id'] . '" class="btn btn-primary">Change_statut_utilisateur</a>
            </p>';
    }
} else {
    echo 'Aucun utilisateur à gérer!';
}

echo '  </main>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>';

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
