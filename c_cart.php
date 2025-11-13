<?php
include('connection.php');
session_start();

//session check:
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo "Invalid request.";
    exit();
}
$user_id = intval($_GET['user_id']);

try {
    // Fetch all items in cart for this user
    $stmt = $pdo->prepare("
        SELECT 
            c.cart_id,
            r.rim_id,
            r.rim_name,
            r.model,
            r.price,
            r.image_url,
            c.quantity
        FROM cart c
        JOIN rims r ON c.rim_id = r.rim_id
        WHERE c.user_id = ? AND c.status = 'active'
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #ff1a1a;
            margin-top: 30px;
        }

        .cart-container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(255, 0, 0, 0.3);
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
        }

        .cart-details {
            flex: 1;
            margin-left: 20px;
        }

        .cart-details h3 {
            margin: 0;
            color: #111;
        }

        .cart-details p {
            margin: 5px 0;
            color: #555;
        }

        .remove-link {
            color: #ff1a1a;
            text-decoration: none;
            font-weight: bold;
        }

        .remove-link:hover {
            text-decoration: underline;
        }

        .summary {
            text-align: right;
            margin-top: 30px;
        }

        .summary h3 {
            color: #111;
        }

        .summary-buttons {
            text-align: right;
            margin-top: 20px;
        }

        .summary-buttons button {
            background-color: #ff1a1a;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            transition: 0.3s;
        }

        .summary-buttons button:hover {
            background-color: #cc0000;
        }

        .empty-cart {
            text-align: center;
            color: #555;
            margin-top: 40px;
        }
    </style>

</head>
<body>
    <h1> Shopping Cart </h1>
    <br>

    <div class="cart-container">
    <?php if (count($cart_items) > 0): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?= $item['image_url'] ?>" alt="<?= $item['rim_name'] ?>">
                <div class="cart-details">
                    <h3><?= htmlentities($item['rim_name']) ?></h3>
                    <p><strong>Model:</strong> <?= htmlentities($item['model']) ?></p>
                    <p><strong>Price:</strong> R<?= number_format($item['price'], 2) ?></p>
                    <p><strong>Quantity:</strong> <?= $item['quantity'] ?></p>
                    <p><strong>Subtotal:</strong> R<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                    <a class="remove-link" href="c_remove_item.php?cart_id=<?= $item['cart_id'] ?>&user_id=<?= $user_id ?>" 
                        onclick="return confirm('Remove this item from your cart?');">Remove</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="summary">
            <h3>Total: R<?= number_format($total, 2) ?></h3>
        </div>

        <div class="summary-buttons">
            <form method="POST" action="c_delete_all.php" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to clear your cart?');">🗑 Delete All</button>
            </form>

            <form method="GET" action="c_checkout.php" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <input type="hidden" name="total" value="<?= $total ?>">
                <button type="submit">Proceed to Checkout →</button>
            </form>
        </div>

    <?php else: ?>
        <p class="empty-cart">Your cart is empty <br><a href="products.php?user_id=<?= $user_id ?>">Continue Shopping</a></p>
    <?php endif; ?>
</div>


</body>
</html>