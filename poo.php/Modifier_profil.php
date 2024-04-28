<?php
// Inclusion du fichier de configuration
include('config.php');

// Classe pour gérer les opérations liées à l'utilisateur
class UserManagement {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour récupérer les informations de l'utilisateur
    public function getUserInfo($user_id) {
        $stmt = mysqli_prepare($this->conn, 'SELECT * FROM user WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return false;
        }
    }

    // Méthode pour mettre à jour les informations de l'utilisateur
    public function updateUser($user_id, $first_name, $last_name, $email, $password = null) {
        if ($password !== null) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($this->conn, 'UPDATE user SET fname = ?, lname = ?, email = ?, pwd = ? WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'ssssi', $first_name, $last_name, $email, $hashed_password, $user_id);
        } else {
            $stmt = mysqli_prepare($this->conn, 'UPDATE user SET fname = ?, lname = ?, email = ? WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'sssi', $first_name, $last_name, $email, $user_id);
        }
        
        mysqli_stmt_execute($stmt);
    }
}

// Vérifiez si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérez l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Création d'une instance de la classe UserManagement
$userManager = new UserManagement($conn);

// Récupérez les informations de l'utilisateur
$userInfos = $userManager->getUserInfo($user_id);

if ($userInfos !== false) {
    $first_name = $userInfos['fname'];
    $last_name = $userInfos['lname'];
    $email = $userInfos['email'];

    if (isset($_POST['valider'])) {
        // Récupérez les données du formulaire
        $first_name_saisi = htmlspecialchars($_POST['first_name']);
        $last_name_saisi = htmlspecialchars($_POST['last_name']);
        $pwd_saisi = htmlspecialchars($_POST['password']);
        $email_saisi = htmlspecialchars($_POST['email']);

        // Mettez à jour les informations de l'utilisateur dans la base de données
        $userManager->updateUser($user_id, $first_name_saisi, $last_name_saisi, $email_saisi, $pwd_saisi);

        // Redirigez l'utilisateur après la mise à jour
        header('Location: Accueil_client.php');
        exit;
    }
} else {
    // Redirection si aucune information sur l'utilisateur n'est trouvée
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #00acee;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #00acee;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>
    <header>
        <h1>Modifier Profil</h1>
    </header>

    <form method="POST" action="">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?= isset($first_name) ? $first_name : ''; ?>">

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?= isset($lname) ? $lname : ''; ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= isset($email) ? $email : ''; ?>">

        <label for="password">Password:</label>
        <input type="password" name="password" value="<?= isset($pwd) ? $pwd : ''; ?>">

        <input type="submit" name="valider" value="Submit">
    </form>
</body>
</html>

