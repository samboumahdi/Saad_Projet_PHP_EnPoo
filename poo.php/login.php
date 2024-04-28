<?php
include("config.php");

class UserLogin {
    private $conn;
    private $host;
    private $db;
    private $user;
    private $pass;
    private $port;

    public function __construct($host, $db, $user, $pass, $port = '3306') {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->port = $port;
    }

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db, $this->port);
        if ($this->conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $this->conn->connect_error);
        }
    }

    public function loginUser($user_name, $pwd) {
        $sql = "SELECT * FROM user WHERE user_name = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if ($user && password_verify($pwd, $user['pwd'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                if ($user['role_id'] == '1' || $user['role_id'] == '2'){
                    header('Location: Accueil_admin.php');
                } else {
                    header('Location: Accueil_client.php');
                }
                exit;
            } else {
                return 'Identifiant inexistant ou mot de passe incorrect. Veuillez réessayer SVP!';
            }
            $stmt->close();
        } else {
            return 'Erreur lors de la préparation de la requête.';
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

$message = ''; // Message pour afficher les erreurs ou notifications

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_login = new UserLogin($host, $db, $user, $pass, $port);
    $user_login->connect();

    $user_name = $_POST['username'];
    $pwd = $_POST['password'];

    $message = $user_login->loginUser($user_name, $pwd);

    $user_login->closeConnection();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Métadonnées du document -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- Styles CSS -->
    <style>
        /* Styles CSS pour la mise en page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav {
            background-color: #007BFF;
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

        .login-container {
            max-width: 400px;
            margin: 50px auto; /* Ajuster l'espace autour du formulaire de connexion */
            background-color: #fff;
            padding: 20px 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            color: red;
            font-weight: bold;
        }

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

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Conteneur principal du formulaire de connexion -->
<div class="login-container">
    <h2>Connexion</h2>

    <!-- Affichage du message d'erreur, le cas échéant -->
    <?php if (!empty($message)): ?>
        <p style="color:red"><?= $message ?></p>
    <?php endif; ?>

    <!-- Formulaire de connexion -->
    <form action="login.php" method="post">
        <div>
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username">
        </div>

        <div>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
        </div>

        <!-- Boutons de soumission et liens associés -->
        <div class="buttons-container">
            <input type="submit" value="Se connecter">
        </div>

        <div class="buttons-container">
            <a href="register.php" class="btn">S'inscrire</a>
            <a href="index.php" class="btn">Quitter</a>
        </div>
    </form>
</div>

</body>
</html>
