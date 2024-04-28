<?php
include('config.php');

class ShoppingCart {
    private $conn;

    public function __construct($host, $db, $user, $pass, $port = '3306') {
        $this->conn = new mysqli($host, $user, $pass, $db, $port);
        if ($this->conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $this->conn->connect_error);
        }
    }

    public function addToCart($product_id, $product_quantity, $product_price, $user_id) {
        $produitExisteDansPanier = $this->conn->prepare("SELECT * FROM order_has_product WHERE order_id = ? AND product_id = ?");
        $produitExisteDansPanier->bind_param('ii', $user_id, $product_id);
        $produitExisteDansPanier->execute();
        $produitExisteDansPanier->store_result();

        if ($produitExisteDansPanier->num_rows > 0) {
            $updateQuantity = $this->conn->prepare("UPDATE order_has_product SET quantity = quantity + ? WHERE order_id = ? AND product_id = ?");
            $updateQuantity->bind_param('iii', $product_quantity, $user_id, $product_id);
            $updateQuantity->execute();
        } else {
            $insertCartItem = $this->conn->prepare("INSERT INTO order_has_product (product_id, quantity, price, order_id) VALUES (?, ?, ?, ?)");
            $insertCartItem->bind_param('iiii', $product_id, $product_quantity, $product_price, $user_id);
            $insertCartItem->execute();
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $shopping_cart = new ShoppingCart($host, $db, $user, $pass, $port);

    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['quantity'];
    $product_price = $_POST['price'];
    $userId = $_SESSION['user_id'];

    $shopping_cart->addToCart($product_id, $product_quantity, $product_price, $userId);
    $shopping_cart->closeConnection();

    echo 'Produit ajouté au panier avec succès!';
} else {
    echo 'Requête invalide, produit non-ajouté au panier.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Résultat</title>
</head>
<body>

<!-- Ajouter un bouton de retour vers la page précédente -->
<a href="Accueil_liste_produit.php">Retour à la liste des produits</a>

</body>
</html>
