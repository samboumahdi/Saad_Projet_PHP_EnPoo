<?php
// Classe pour gérer les opérations liées aux utilisateurs
class UserManager {
    private $conn;

    // Constructeur prenant la connexion à la base de données en paramètre
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour supprimer un utilisateur en fonction de son identifiant
    public function deleteUser($userId) {
        // Vérifie si l'identifiant de l'utilisateur est défini et non vide
        if (!isset($userId) || empty($userId)) {
            echo "L'identifiant n'a pas été récupéré";
            return;
        }

        // Prépare et exécute la requête pour récupérer les informations de l'utilisateur
        $recupUserSql = "SELECT * FROM user WHERE id = ?";
        $recupUserStmt = mysqli_prepare($this->conn, $recupUserSql);
        mysqli_stmt_bind_param($recupUserStmt, 'i', $userId);
        mysqli_stmt_execute($recupUserStmt);

        // Vérifie si l'utilisateur a été trouvé
        $recupUserResult = mysqli_stmt_get_result($recupUserStmt);

        if (mysqli_num_rows($recupUserResult) > 0) {
            // Prépare et exécute la requête pour supprimer l'utilisateur
            $supprimerUserSql = "DELETE FROM user WHERE id = ?";
            $supprimerUserStmt = mysqli_prepare($this->conn, $supprimerUserSql);
            mysqli_stmt_bind_param($supprimerUserStmt, 'i', $userId);
            mysqli_stmt_execute($supprimerUserStmt);

            // Redirige vers la page de gestion des utilisateurs après la suppression
            header('Location: Gestion_utilisateurs.php');
            exit; // Arrête l'exécution après la redirection
        } else {
            echo "Aucun membre n'a été trouvé";
        }
    }
}

// Inclure le fichier de configuration qui contient la connexion à la base de données
include('config.php');

// Création d'une instance de la classe UserManager en passant la connexion à la base de données en paramètre
$userManager = new UserManager($conn);

// Vérifie si l'identifiant est défini et non vide dans la requête GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Récupère l'identifiant depuis la requête GET
    $userId = $_GET['id'];

    // Appel à la méthode deleteUser pour supprimer l'utilisateur avec l'identifiant spécifié
    $userManager->deleteUser($userId);
}
?>
