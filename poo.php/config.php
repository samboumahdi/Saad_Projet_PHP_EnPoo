<?php
class Database {
    private $host;
    private $db;
    private $user;
    private $pass;
    private $port;
    private $charset;
    private $conn;

    public function __construct($host, $db, $user, $pass, $port = '3306', $charset = 'utf8mb4') {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->port = $port;
        $this->charset = $charset;
    }

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db, $this->port);
        if ($this->conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        $this->conn->close();
    }
}

// Paramètres de connexion à la base de données
$host = 'localhost';
$db   = 'ecom1_project';
$user = 'root';
$pass = '';
$port = '3306';
$charset = 'utf8mb4';

// Création d'une instance de la classe Database
$database = new Database($host, $db, $user, $pass, $port, $charset);

// Connexion à la base de données
$database->connect();

// Utilisation de la connexion
$conn = $database->getConnection();

// À la fin, n'oubliez pas de fermer la connexion
//$database->close(); // Décommentez cette ligne lorsque vous avez fini d'utiliser la connexion
?>
