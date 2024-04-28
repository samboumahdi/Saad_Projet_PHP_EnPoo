<?php
// Inclusion du fichier de configuration
include('config.php');

// Classe pour gérer les opérations liées aux produits
class ProductManager {
    private $conn;

    // Constructeur prenant la connexion à la base de données en paramètre
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour supprimer un produit en fonction de son identifiant
    public function deleteProduct($productId) {
        // Prépare et exécute la requête DELETE pour supprimer le produit avec l'identifiant spécifié
        $deleteProductSql = "DELETE FROM product WHERE id = ?";
        $deleteProductStmt = mysqli_prepare($this->conn, $deleteProductSql);
        mysqli_stmt_bind_param($deleteProductStmt, 'i', $productId);
        mysqli_stmt_execute($deleteProductStmt);

        // Redirige vers la page de gestion des produits après la suppression
        header('Location: Gestion_produit.php');
        exit();
    }
}

// Créer une instance de la classe ProductManager en passant la connexion à la base de données en paramètre
$productManager = new ProductManager($conn);

// Vérifie si l'identifiant est défini et non vide dans la requête GET
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // Récupère l'identifiant depuis la requête GET
    $productId = $_GET['id'];

    // Supprime le produit avec l'identifiant spécifié
    $productManager->deleteProduct($productId);
} else {
    echo "Aucun identifiant trouvé";
}

// Ferme la connexion à la base de données à la fin du script
mysqli_close($conn);
?>
