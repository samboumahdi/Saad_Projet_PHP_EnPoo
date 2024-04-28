<?php
include('config.php');

// Classe pour gérer les opérations liées aux produits
class ProductManager {
    private $conn;

    // Constructeur prenant la connexion à la base de données en paramètre
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour récupérer les détails d'un produit en fonction de son identifiant
    public function getProductDetails($productId) {
        // Prépare et exécute la requête SELECT pour récupérer les informations du produit avec l'identifiant spécifié
        $getProductSql = "SELECT * FROM product WHERE id = ?";
        $getProductStmt = mysqli_prepare($this->conn, $getProductSql);
        mysqli_stmt_bind_param($getProductStmt, 'i', $productId);
        mysqli_stmt_execute($getProductStmt);
        $result = mysqli_stmt_get_result($getProductStmt);

        // Vérifie si un produit avec l'identifiant spécifié existe
        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    }

    // Méthode pour mettre à jour les informations d'un produit
    public function updateProduct($productId, $name, $description, $imgUrl, $quantity, $price) {
        // Prépare et exécute la requête UPDATE pour mettre à jour les informations du produit
        $updateProductSql = "UPDATE product SET name = ?, description = ?, img_url = ?, quantity = ?, price = ? WHERE id = ?";
        $updateProductStmt = mysqli_prepare($this->conn, $updateProductSql);
        mysqli_stmt_bind_param($updateProductStmt, 'sssidi', $name, $description, $imgUrl, $quantity, $price, $productId);
        mysqli_stmt_execute($updateProductStmt);
    }
}

// Vérifie si l'identifiant du produit est défini dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $productId = $_GET['id'];

    // Crée une instance de la classe ProductManager en passant la connexion à la base de données en paramètre
    $productManager = new ProductManager($conn);

    // Récupère les détails du produit en fonction de son identifiant
    $productDetails = $productManager->getProductDetails($productId);

    // Vérifie si le produit existe
    if ($productDetails !== null) {
        // Vérifie si le formulaire est soumis
        if (isset($_POST['valider'])) {
            // Assainit et récupère les valeurs du formulaire
            $name = htmlspecialchars($_POST['name']);
            $description = nl2br(htmlspecialchars($_POST['description']));
            $imgUrl = htmlspecialchars($_POST['img_url']);
            $quantity = intval($_POST['quantity']);
            $price = floatval($_POST['price']);

            // Met à jour les informations du produit dans la base de données
            $productManager->updateProduct($productId, $name, $description, $imgUrl, $quantity, $price);

            // Redirige vers la page de gestion des produits après la mise à jour réussie
            header('Location: Gestion_produit.php');
            exit;
        }
    } else {
        echo "Aucun produit trouvé";
    }
} else {
    echo "Aucun identifiant trouvé";
}

// Ferme la connexion à la base de données à la fin du script
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier produit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier produit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input, textarea, select {
            width: 95%;
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

        /* Style pour le champ de fichier */
        .form-control {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Ajout de marge en haut pour l'étiquette */
        label.form-label {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <label for="name">Titre :</label>
        <input type="text" name="name" value="<?= isset($name) ? $name : ''; ?>">

        <label for="description">Description :</label>
        <textarea name="description"><?= isset($description) ? $description : ''; ?></textarea>

        <label class="form-label">Image :</label>
        <input type="file" class="form-control" name="img_url">

        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" value="<?= isset($quantity) ? $quantity : 0; ?>">

        <label for="price">Prix :</label>
        <input type="number" step="0.01" name="price" value="<?= isset($price) ? $price : 0.00; ?>">

        <input type="submit" name="valider">
    </form>
</body>
</html>
