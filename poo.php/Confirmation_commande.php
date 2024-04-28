<?php
// Confirmation_commande.php

include('config.php');
session_start();

class OrderManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCartItems($user_id) {
        $sql = "SELECT o.order_id as order_id, p.id as product_id, p.name, p.price, p.description, o.quantity, o.quantity * p.price as total_price_produit
                FROM order_has_product as o
                INNER JOIN product as p ON o.product_id = p.id
                WHERE o.order_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getTotalCartPrice($cartItems) {
        $totalCartPrice = 0;
        foreach ($cartItems as $item) {
            $totalCartPrice += $item['total_price_produit'];
        }
        return $totalCartPrice;
    }

    public function placeOrder($user_id, $totalCartPrice) {
        $orderRef = uniqid();
        $orderDate = date('Y-m-d H:i:s');
        $insertOrderSql = "INSERT INTO user_order (ref, date, total, user_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $insertOrderSql);
        mysqli_stmt_bind_param($stmt, 'ssdi', $orderRef, $orderDate, $totalCartPrice, $user_id);
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->conn);
    }

    public function deleteCartItems($user_id) {
        $deleteCartItemsSql = "DELETE FROM order_has_product WHERE order_id = ?";
        $stmt = mysqli_prepare($this->conn, $deleteCartItemsSql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
    }
}

class AddressManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertAddress($street_name, $street_nb, $city, $province, $zip_code, $country) {
        $insertAddressSql = "INSERT INTO address (street_name, street_nb, city, province, zip_code, country) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $insertAddressSql);
        mysqli_stmt_bind_param($stmt, 'ssssss', $street_name, $street_nb, $city, $province, $zip_code, $country);
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->conn);
    }
}

$orderManager = new OrderManager($conn);
$addressManager = new AddressManager($conn);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $cartItems = $orderManager->getCartItems($user_id);
    $totalCartPrice = $orderManager->getTotalCartPrice($cartItems);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Insérer la commande dans la base de données
        $order_id = $orderManager->placeOrder($user_id, $totalCartPrice);

        // Insérer l'adresse de livraison
        $deliveryAddressId = $addressManager->insertAddress($_POST['delivery_street_name'], $_POST['delivery_street_nb'], $_POST['delivery_city'], $_POST['delivery_province'], $_POST['delivery_zip_code'], $_POST['delivery_country']);

        // Insérer l'adresse de paiement
        $paymentAddressId = $addressManager->insertAddress($_POST['payment_street_name'], $_POST['payment_street_nb'], $_POST['payment_city'], $_POST['payment_province'], $_POST['payment_zip_code'], $_POST['payment_country']);

        // Mettre à jour l'utilisateur avec les adresses
        $updateOrderSql = "UPDATE user SET shipping_address_id = ?, billing_address_id = ? WHERE id = ?";
        $updateOrderStmt = mysqli_prepare($conn, $updateOrderSql);
        mysqli_stmt_bind_param($updateOrderStmt, 'iii', $deliveryAddressId, $paymentAddressId, $user_id);
        mysqli_stmt_execute($updateOrderStmt);

        // Supprimer les articles du panier
        $orderManager->deleteCartItems($user_id);

        // Rediriger vers une page de remerciement
        header('Location: Merci_achat.php');
        exit();
    }
} else {
    header('Location: Accueil.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            color: #343a40;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            margin-top: 20px;
        }

        table {
            margin-top: 20px;
            background-color: #fff;
        }

        th, td {
            text-align: center;
        }

        .btn-primary {
            margin-top: 20px;
        }

        .btn-success {
            margin-top: 20px;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: #343a40;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirmation des détails de la commande</h2>

        <?php if(isset($cartItems) && !empty($cartItems)) { ?>
            <p>Merci pour votre commande!</p>

            <h3>Détails de la commande :</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom du Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cartItems as $item) { ?>
    <tr>
        <td><?php echo $item['name']; ?></td>
        <td><?php echo $item['price']; ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo $item['total_price_produit']; ?></td>
    </tr>
<?php } ?>

                    <tr>
                        <td colspan="3" class="text-right"><strong>Total de la Commande</strong></td>
                        <td><?php echo $totalCartPrice; ?></td>
                    </tr>
                </tbody>
            </table>

            <p>Veuillez fournir les adresses de livraison et de paiement:</p>
            <!-- Ajoutez ici le formulaire pour l'adresse de livraison et de paiement -->
            <form method="post" action="">
                <!-- Autres champs du formulaire -->

                <h3>Valider l'adresse de Livraison</h3>
                <?php if(isset($addresses[0])) { ?>
                    <!-- Les champs du formulaire pour l'adresse de livraison -->
                    <div class="form-group">
                        <label for="delivery_street_nb">Numéro de Rue:</label>
                        <input type="text" name="delivery_street_nb" class="form-control" value="<?= $addresses[0]['street_nb'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_street_name">Nom de la Rue:</label>
                        <input type="text" name="delivery_street_name" class="form-control" value="<?= $addresses[0]['street_name'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_city">Ville:</label>
                        <input type="text" name="delivery_city" class="form-control" value="<?= $addresses[0]['city'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_province">Province:</label>
                        <input type="text" name="delivery_province" class="form-control" value="<?= $addresses[0]['province'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_zip_code">Code Postal:</label>
                        <input type="text" name="delivery_zip_code" class="form-control" value="<?= $addresses[0]['zip_code'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_country">Pays:</label>
                        <input type="text" name="delivery_country" class="form-control" value="<?= $addresses[0]['country'] ?>" required>
                    </div>
                <?php } else { ?>
                    <div class="form-group">
                        <label for="delivery_street_nb">Numéro de Rue:</label>
                        <input type="text" name="delivery_street_nb" class="form-control"  required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_street_name">Nom de la Rue:</label>
                        <input type="text" name="delivery_street_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_city">Ville:</label>
                        <input type="text" name="delivery_city" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_province">Province:</label>
                        <input type="text" name="delivery_province" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_zip_code">Code Postal:</label>
                        <input type="text" name="delivery_zip_code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="delivery_country">Pays:</label>
                        <input type="text" name="delivery_country" class="form-control" required>
                    </div>
                <?php } ?>

                <!-- Adresse de paiement -->
                <h3>Valider l'adresse de paiement</h3>
                <?php if(isset($addresses[1])) { ?>
                    <!-- Les champs du formulaire pour l'adresse de paiement -->
                    <div class="form-group">
                        <label for="payment_street_nb">Numéro de Rue:</label>
                        <input type="text" name="payment_street_nb" class="form-control" value="<?= $addresses[1]['street_nb'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_street_name">Nom de la Rue:</label>
                        <input type="text" name="payment_street_name" class="form-control" value="<?= $addresses[1]['street_name'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_city">Ville:</label>
                        <input type="text" name="payment_city" class="form-control" value="<?= $addresses[1]['city'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_province">Province:</label>
                        <input type="text" name="payment_province" class="form-control" value="<?= $addresses[1]['province'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_zip_code">Code Postal:</label>
                        <input type="text" name="payment_zip_code" class="form-control" value="<?= $addresses[1]['zip_code'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_country">Pays:</label>
                        <input type="text" name="payment_country" class="form-control" value="<?= $addresses[1]['country'] ?>" required>
                    </div>
                <?php } else { ?>
                    <div class="form-group">
                        <label for="payment_street_nb">Numéro de Rue:</label>
                        <input type="text" name="payment_street_nb" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_street_name">Nom de la Rue:</label>
                        <input type="text" name="payment_street_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_city">Ville:</label>
                        <input type="text" name="payment_city" class="form-control"  required>
                    </div>
                    <div class="form-group">
                        <label for="payment_province">Province:</label>
                        <input type="text" name="payment_province" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_zip_code">Code Postal:</label>
                        <input type="text" name="payment_zip_code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_country">Pays:</label>
                        <input type="text" name="payment_country" class="form-control" required>
                    </div>
                <?php } ?>

                <h3>Valider le paiement</h3>
                <?php if(isset($paymentSuccess) && $paymentSuccess) { ?>
                    <div class="alert alert-success">
                        Payment successful!
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="card_number">Card Number</label>
                    <input type="text" name="card_number" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="card_holder">Card Holder Name</label>
                    <input type="text" name="card_holder" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="expiry_month">Expiry Month</label>
                        <input type="text" name="expiry_month" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="expiry_year">Expiry Year</label>
                        <input type="text" name="expiry_year" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" name="cvv" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
            </form>

        <?php } else { ?>
            <p>Votre panier est vide.</p>
        <?php } ?>

        <a href="Accueil_liste_produit.php" class="btn btn-primary">Retour à l'Accueil</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
