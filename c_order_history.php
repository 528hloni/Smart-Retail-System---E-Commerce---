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
        .orders-container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(255,0,0,0.3);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #ff1a1a;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .status-pending { color: orange; font-weight: bold; }
        .status-completed { color: green; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
        .payment-unpaid { color: orange; }
        .payment-paid { color: green; }
        .payment-refunded { color: red; }
        .empty-orders {
            text-align: center;
            margin-top: 40px;
            color: #555;
        }
        
    </style>
</head>
<body>
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