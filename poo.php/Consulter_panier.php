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

    public function getCartItems($user_id) {
        $sql = "SELECT o.order_id as order_id, p.id as product_id, p.name, p.price, p.description, o.quantity, o.quantity * p.price as total_price_produit
                FROM order_has_product as o
                INNER JOIN product as p ON o.product_id = p.id
                WHERE o.order_id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);

        return $cartItems;
    }

    public function updateQuantity($order_id, $product_id, $new_quantity) {
        $updateSql = "UPDATE order_has_product SET quantity = ? WHERE order_id = ? AND product_id = ?";
        $updateStmt = $this->conn->prepare($updateSql);
        $updateStmt->bind_param('iii', $new_quantity, $order_id, $product_id);
        $updateStmt->execute();
    }

    public function deleteProduct($order_id, $product_id) {
        $deleteSql = "DELETE FROM order_has_product WHERE order_id = ? AND product_id = ?";
        $deleteStmt = $this->conn->prepare($deleteSql);
        $deleteStmt->bind_param('ii', $order_id, $product_id);
        $deleteStmt->execute();
    }

    public function clearCart($user_id) {
        $clearSql = "DELETE FROM order_has_product WHERE order_id = ?";
        $clearStmt = $this->conn->prepare($clearSql);
        $clearStmt->bind_param('i', $user_id);
        $clearStmt->execute();
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $shopping_cart = new ShoppingCart($host, $db, $user, $pass, $port);
    $cartItems = $shopping_cart->getCartItems($user_id);

    $totalCartPrice = array_sum(array_column($cartItems, 'total_price_produit'));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_quantity'])) {
            $order_id = $_POST['order_id'];
            $product_id = $_POST['product_id'];
            $new_quantity = $_POST['new_quantity'];

            $shopping_cart->updateQuantity($order_id, $product_id, $new_quantity);
            header('Location: Consulter_panier.php');
            exit();
        }

        if (isset($_POST['delete_product'])) {
            $order_id = $_POST['order_id'];
            $product_id = $_POST['product_id'];

            $shopping_cart->deleteProduct($order_id, $product_id);
            header('Location: Consulter_panier.php');
            exit();
        }

        if (isset($_POST['clear_cart'])) {
            $shopping_cart->clearCart($user_id);
            header('Location: Consulter_panier.php');
            exit();
        }
    }

    $shopping_cart->closeConnection();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter le Panier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Contenu du Panier</h2>
        <?php if(isset($cartItems) && !empty($cartItems)) { ?>
    <form method="post" action="">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom du Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item) { ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?= $item['order_id'] ?>">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <input type="number" name="new_quantity" value="<?= $item['quantity'] ?>" min="1" required>
                                <button type="submit" name="update_quantity" class="btn btn-primary">Mettre à jour</button>
                            </form>
                        </td>
                        <td><?= $item['total_price_produit'] ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?= $item['order_id'] ?>">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <button type="submit" name="delete_product" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total du Panier</strong></td>
                    <td><?= $totalCartPrice ?></td>
                    <td>
                        <form method="post" action="">
                            <button type="submit" name="clear_cart" class="btn btn-danger">Vider le Panier</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
<?php } else { ?>
    <p>Votre panier est vide.</p>
<?php } ?>
<!-- Lien pour ajouter d'autres produits -->
<a href="Accueil_liste_produit.php" class="btn btn-primary">Ajouter d'autres produits</a>
        
        <!-- Lien pour passer la commande -->
        <a href="Confirmation_commande.php" class="btn btn-success">Passer la commande</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
