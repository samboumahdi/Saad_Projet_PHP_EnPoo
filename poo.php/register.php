<?php
// Inclusion du fichier de configuration
include("config.php");

class UserRegistration {
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

    public function registerUser($email, $user_name, $pwd, $fname, $lname) {
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (email, user_name, pwd, role_id, fname, lname) VALUES (?, ?, ?, 3, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $email, $user_name, $pwd, $fname, $lname);
            $result = $stmt->execute();
            if ($result) {
                return true; // Inscription réussie
            } else {
                return "Erreur lors de l'inscription : " . $this->conn->error;
            }
            $stmt->close();
        } else {
            return "Erreur lors de la préparation de la requête.";
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_registration = new UserRegistration($host, $db, $user, $pass, $port);
    $user_registration->connect();

    $email = $_POST['email'];
    $user_name = $_POST['username'];
    $pwd = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    $registration_result = $user_registration->registerUser($email, $user_name, $pwd, $fname, $lname);

    if ($registration_result === true) {
        echo 'Inscription réussie!';
        // Redirection vers la page de connexion
        header('Location: login.php');
        exit; // Arrêter l'exécution après la redirection
    } else {
        $message = $registration_result;
    }

    $user_registration->closeConnection();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
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
    input[type="password"],
    input[type="email"] {
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

    p {
        color: red;
        font-weight: bold;
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

<div class="login-container">
    <h2>Inscription</h2>

   

    <form action="register.php" method="post">
        <div>
            <label for="email">Adresse e-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username">
        </div>
        <div>
        <label for="fname">fname :</label>
        <input type="text" id="fname" name="fname">
        </div>
        <div>
        <label for="lname">lname :</label>
        <input type="text" id="lname" name="lname">
        </div>
        <div>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
    
        </div>

        <div>
            <input type="submit" value="S'inscrire">
        </div>
        <div class="buttons-container">
            <a href="index.php" class="btn">Quitter</a>
        </div>

    </form>
</div>

</body>
</html>
