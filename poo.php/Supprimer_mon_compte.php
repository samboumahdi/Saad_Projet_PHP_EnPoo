<?php
// Inclusion du fichier de configuration
include('config.php');

// Classe pour gérer les opérations liées à l'utilisateur
class UserManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour supprimer un utilisateur
    public function deleteUser($user_id) {
        // Préparation de la requête DELETE
        $stmt = mysqli_prepare($this->conn, 'DELETE FROM user WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);

        // Fermeture du statement
        mysqli_stmt_close($stmt);
    }

    // Méthode pour récupérer les informations d'un utilisateur par son ID
    public function getUserInfo($user_id) {
        // Préparation de la requête SELECT
        $stmt = mysqli_prepare($this->conn, 'SELECT * FROM user WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Récupération des informations de l'utilisateur
        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return false;
        }

        // Fermeture du statement
        mysqli_stmt_close($stmt);
    }
}

// Vérifiez si la session est démarrée
session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Création d'une instance de la classe UserManager
    $userManager = new UserManager($conn);

    // Récupération des informations de l'utilisateur
    $userInfo = $userManager->getUserInfo($user_id);

    if ($userInfo !== false) {
        // Suppression de l'utilisateur
        $userManager->deleteUser($user_id);

        // Déconnexion de l'utilisateur
        session_destroy();

        echo "Utilisateur supprimé avec succès";

        // Assurez-vous qu'aucun contenu n'a été envoyé avant la redirection
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Redirection vers la page de connexion
        header('Location: login.php');
        exit;
    } else {
        echo "Aucun utilisateur n'a été trouvé";
    }
} else {
    echo "Utilisateur non connecté";
}

// Fermeture de la connexion MySQLi
mysqli_close($conn);
?>
