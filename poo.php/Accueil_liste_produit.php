<?php
include('config.php');

class ProductList {
    private $conn;

    public function __construct($host, $db, $user, $pass, $port = '3306') {
        $this->conn = new mysqli($host, $user, $pass, $db, $port);
        if ($this->conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $this->conn->connect_error);
        }
    }

    public function getProducts($search = null) {
        $products = array();

        if ($search !== null) {
            $search = '%' . $search . '%';
            $sql = "SELECT * FROM product WHERE name LIKE ?";
            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("s", $search);
                $stmt->execute();
                $result = $stmt->get_result();
                $products = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
            } else {
                die("Erreur lors de la préparation de la requête : " . $this->conn->error);
            }
        } else {
            $result = $this->conn->query("SELECT * FROM product");

            if ($result === false) {
                die("Erreur de requête : " . $this->conn->error);
            }

            $products = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
        }

        return $products;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

$product_list = new ProductList($host, $db, $user, $pass, $port);
$products = $product_list->getProducts($_GET['search'] ?? null);
$product_list->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Accueil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-container {
            border: 1px solid #ddd;
            background-color: #fff;
            margin: 10px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            width: 100%;
        }

        .product-container:hover {
            transform: scale(1.05);
        }

        .product-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .quantity-select {
            width: 50px;
            padding: 5px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <!-- Ajouter le bouton pour consulter le panier -->
    <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <a href="Consulter_panier.php" class="btn btn-primary">Consulter le Panier</a>
                    <a href="accueil_client.php" class="btn btn-secondary">Retour à l'Accueil</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Ajouter un formulaire de recherche -->
    <form method="GET" action="">
        <label for="search">Rechercher un produit par nom :</label>
        <input type="text" name="search" id="search">
        <input type="submit" value="Rechercher">
    </form>

    <div class="container">
        <div class="row">
        <?php foreach ($products as $product) : ?>
    <div class="col-md-4">
        <form method="POST" action="Ajouter_au_panier.php">
            <div class="product-container">
                <img src="<?= "Image_produit/" . $product['img_url'] ?>" alt="Product Image">
                <h4>ID: <?= $product['id'] ?></h4>
                <p>Name: <?= $product['name'] ?></p>
                <p>Price: <?= $product['price'] ?></p>
                <p>Description: <?= $product['description'] ?></p>

                <label for="quantity">Quantity:</label>
                <select name="quantity" class="quantity-select">
                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <input type="hidden" name="price" value="<?= $product['price'] ?>">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <br>
                <input type="submit" value="Ajouter au panier">
            </div>
        </form>
    </div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
