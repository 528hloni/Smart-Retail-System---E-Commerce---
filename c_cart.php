<?php
include('connection.php');
session_start();

//session check:
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
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
     <title>My Cart</title>
    <link rel="stylesheet" href="c_cart.css">

    
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="customer_dashboard.php?user_id=<?= $user_id ?>">Wheels of Fortune</a>
        </div>
        <ul class="nav-links">
            <li><a href="customer_dashboard.php?user_id=<?= $user_id ?>">Dashboard</a></li>
            <li><a href="products.php?user_id=<?= $user_id ?>">Shop</a></li>
            <li><a href="c_cart.php?user_id=<?= $user_id ?>">Cart</a></li>
            <li><a href="c_order_history.php?user_id=<?= $user_id ?>">My Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>




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
<button type="button" onclick="window.history.back()">← Return</button>

            <form method="POST" action="c_delete_all.php" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to clear your cart?');"> Delete All</button>
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