<?php
// Inclure le fichier de configuration
include('config.php');

// Classe pour gérer les opérations liées aux utilisateurs
class UserManager {
    private $conn;

    // Constructeur prenant la connexion à la base de données en paramètre
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour récupérer les informations de l'utilisateur en fonction de son identifiant
    public function getUserInfo($userId) {
        // Prépare et exécute une requête SELECT pour récupérer les informations de l'utilisateur
        $recupUserSql = "SELECT * FROM user WHERE id = ?";
        $recupUserStmt = mysqli_prepare($this->conn, $recupUserSql);
        mysqli_stmt_bind_param($recupUserStmt, 'i', $userId);
        mysqli_stmt_execute($recupUserStmt);
        $recupUserResult = mysqli_stmt_get_result($recupUserStmt);

        // Vérifie si un utilisateur avec l'id donné existe
        if (mysqli_num_rows($recupUserResult) > 0) {
            return mysqli_fetch_assoc($recupUserResult);
        } else {
            return null;
        }
    }

    // Méthode pour mettre à jour le rôle de l'utilisateur
    public function updateUserRole($userId, $newRoleId) {
        // Prépare et exécute une requête UPDATE pour mettre à jour le rôle de l'utilisateur
        $updateUserSql = "UPDATE user SET role_id = ? WHERE id = ?";
        $updateUserStmt = mysqli_prepare($this->conn, $updateUserSql);
        mysqli_stmt_bind_param($updateUserStmt, 'ii', $newRoleId, $userId);
        mysqli_stmt_execute($updateUserStmt);
    }
}

// Créer une instance de la classe UserManager en passant la connexion à la base de données en paramètre
$userManager = new UserManager($conn);

// Vérifier si 'id' est défini dans l'URL et n'est pas vide
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Récupérer 'id' depuis l'URL
    $userId = $_GET['id'];

    // Récupérer les informations de l'utilisateur en fonction de son identifiant
    $userInfo = $userManager->getUserInfo($userId);

    // Vérifier si un utilisateur avec l'id donné existe
    if ($userInfo !== null) {
        // Récupérer le rôle actuel de l'utilisateur
        $role_id = $userInfo['role_id'];

        // Vérifier si le formulaire est soumis
        if (isset($_POST['valider'])) {
            // Filtrer et obtenir le role_id sélectionné depuis le formulaire
            $role_id_saisi = htmlspecialchars($_POST['role_id']);

            // Mettre à jour le rôle de l'utilisateur
            $userManager->updateUserRole($userId, $role_id_saisi);

            // Rediriger vers la page de gestion des utilisateurs
            header('Location: Gestion_utilisateurs.php');
            exit;
        }
    } else {
        // Afficher un message si aucun utilisateur n'est trouvé avec l'id donné
        echo "Aucun identifiant trouvé";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier le rôle de l'utilisateur</title>
    <meta charset="utf-8">
    <style>
       .buttons-container {
        text-align: center;
        margin-top: 20px; /* Réduire l'espace en haut des boutons */
    }
    .btn {
        margin: 10px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
        display: inline-block; /* Pour que les boutons soient alignés horizontalement */
    }

    .btn:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
    <!-- Formulaire pour mettre à jour le rôle de l'utilisateur -->
    <form method="POST" action="">
        <label for="role_id">Rôle de l'utilisateur :</label>
        <select name="role_id">
            <!-- Options pour différents rôles avec l'attribut selected basé sur $role_id -->
            <option value="2" <?= ($role_id == 2) ? 'selected' : ''; ?>>Admin</option>
            <option value="3" <?= ($role_id == 3) ? 'selected' : ''; ?>>Client</option>
        </select>

        <br><br>
        <!-- Bouton de soumission -->
        <input type="submit" name="valider">
        <br>
        <div class="buttons-container">
            <a href="Gestion_utilisateurs.php" class="btn">retourner vers la gestion des utilisateurs</a>
        </div>
    </form>

    <!-- Afficher un message si $message est défini -->
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>
</body>
</html>
