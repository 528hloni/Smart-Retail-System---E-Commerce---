<?php
include('connection.php');
session_start();

// Session Check
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
    // Fetch all orders for this customer
    $stmt = $pdo->prepare("
        SELECT order_id, order_date, total_amount, status, payment_status
        FROM orders
        WHERE user_id = ?
        ORDER BY order_date DESC
    ");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching orders: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="c_order_history.css">
    
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

    <a href="customer_dashboard.php?user_id=<?= $user_id ?>">
    <button type="button" class="return-btn"> Return</button>
</a>





    <h1>My Order History</h1>
    <div class="orders-container">
        <?php if(count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount (R)</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                        <tr>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= date("d M Y H:i", strtotime($order['order_date'])) ?></td>
                            <td><?= number_format($order['total_amount'], 2) ?></td>
                            <td class="status-<?= strtolower($order['status']) ?>">
                                <?= $order['status'] ?>
                                <?php if($order['status'] === 'Pending'): ?>
                                    <br><small>Waiting for payment processor...</small>
                                <?php endif; ?>
                            </td>
                            <td class="payment-<?= strtolower($order['payment_status']) ?>">
                                <?= $order['payment_status'] ?>
                            </td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-orders">You have not placed any orders yet. <a href="products.php">Shop Now</a></p>
        <?php endif; ?>
    </div>
</body>
</html>