<?php 
include('connection.php');

try{


if (isset($_GET['rim_id']) && is_numeric($_GET['rim_id'])){
    $rim_id = trim($_GET['rim_id']);
    $stmt = $pdo->prepare("SELECT * FROM rims WHERE rim_id = ?");
    $stmt->execute([$rim_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found";
        exit();
        
    }
} else {
    echo "Invalid request";
    exit();
}
} catch (Exception $e) {
    echo "Error: " . $e->getmessage();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <img src="<?= $product['image_url'] ?>" alt="<?= $product['rim_name'] ?>"> 
    <br>
    <h3><?= $product['rim_name'] ?></h3>
    <p><strong> <?= $product['model'] ?> </strong> </p>
    <br>
    <p class="price">R<?= $product['price'] ?></p>
    <h4>Specifications:</h4>
    <br>
    <p><strong>Size:</strong> <?= $product['size_inch'] ?> inch </p>
    <p><strong>Color:</strong> <?= $product['color'] ?> </p>
    <br>
    <p><strong>Bolt Pattern:</strong> <?= $product['bolt_pattern'] ?> </p>
    <p><strong>Offset:</strong> <?= $product['offset'] ?> </p>
    <br>
    <p><strong>Center Bore:</strong> <?= $product['center_bore'] ?> </p>
    <br>
    <div class="quantity-container">
    <label for="quantity">Quantity:</label>
    <div class="quantity-box">
        <button type="button" class="qty-btn" id="decrease">−</button>
        <input type="text" id="quantity" value="1" readonly>
        <button type="button" class="qty-btn" id="increase">+</button>
    </div>
</div>

<button class="add-to-cart">ADD TO CART</button>







<script>
const decreaseBtn = document.getElementById('decrease');
const increaseBtn = document.getElementById('increase');
const quantityInput = document.getElementById('quantity');

decreaseBtn.addEventListener('click', () => {
    let value = parseInt(quantityInput.value);
    if (value > 1) {
        quantityInput.value = value - 1;
    }
});

increaseBtn.addEventListener('click', () => {
    let value = parseInt(quantityInput.value);
    quantityInput.value = value + 1;
});
</script>
</body>
</html>