<?php
include('connection.php');
session_start();

// session check
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo "Invalid request.";
    exit();
}
$user_id = intval($_GET['user_id']);


// fetch active cart items
try {
    $stmt = $pdo->prepare("
        SELECT c.cart_id, c.rim_id, c.quantity, r.price, r.rim_name
        FROM cart c
        JOIN rims r ON c.rim_id = r.rim_id
        WHERE c.user_id = ? AND c.status = 'active'
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} catch (Exception $e) {
    echo "Error fetching cart: " . $e->getMessage();
    exit();
}

$message = "";
$order_placed = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // front-end inputs (we will NOT store them)
    $card_name   = trim($_POST['card_name']);
    $card_number = preg_replace('/\D/', '', $_POST['card_number']);
    $expiry      = trim($_POST['expiry']);
    $cvv         = trim($_POST['cvv']);

    $errors = [];
    if ($card_name === '') $errors[] = "Name on card is required.";
    if (!preg_match('/^\d{13,19}$/', $card_number)) $errors[] = "Card number looks invalid.";
    if (!preg_match('/^\d{2}\/\d{2}$/', $expiry) && !preg_match('/^\d{4}-\d{2}$/', $expiry)) $errors[] = "Expiry format invalid.";
    if (!preg_match('/^\d{3,4}$/', $cvv)) $errors[] = "CVV invalid.";

    if (count($cart_items) === 0) $errors[] = "Your cart is empty.";

    if (empty($errors)) {
        try {
            // insert order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, payment_status) VALUES (?, ?, 'Pending', 'Unpaid')");
                                   
    
            $stmt->execute([$user_id, $total]);
            $order_id = $pdo->lastInsertId();

            // insert order items
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, rim_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
                                        
            foreach ($cart_items as $item) {
                $stmt_item->execute([$order_id, $item['rim_id'], $item['quantity'], $item['price']]);
            }

            // insert payment 
            $stmt_pay = $pdo->prepare("INSERT INTO payments (order_id, amount, method, status) VALUES (?, ?, ?, 'Pending')");
            $stmt_pay->execute([$order_id, $total, 'Card']);

            // mark cart items as checked_out
            foreach ($cart_items as $item) {
                $stmt_update = $pdo->prepare("UPDATE cart SET status='checked_out' WHERE cart_id=?");
                $stmt_update->execute([$item['cart_id']]);
            }

            $order_placed = true;
            $message = "
                <div style='padding:20px; background:#e8fff0; border-radius:8px; text-align:center;'>
                    <h2>Payment Submitted!</h2>
                    <p>Your order is being processed.</p>
                    <p><strong>Order Status:</strong> Pending</p>
                    <p><strong>Payment Status:</strong> Pending Verification</p>
                    <p>Waiting for payment processor...</p>
                    <br>
                    <a href='c_order_history.php?user_id=$user_id' style='color:#ff1a1a; font-weight:bold;'>→ View Order History</a>
                </div>
            ";

            // clear local cart array for display
            $cart_items = [];
            $total = 0;

        } catch (Exception $e) {
            $errors[] = "Server error: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $message = "<div style='padding:10px; background:#ffecec; border-radius:6px; color:#a00'>"
                 . implode("<br>", $errors) 
                 . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - Wheels of Fortune</title>
<style>
body{font-family:Poppins,sans-serif;background:#f9f9f9;margin:0;padding:0}
.container{max-width:500px;margin:40px auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 4px 10px rgba(255,0,0,0.2)}
h1{text-align:center;color:#ff1a1a;margin-bottom:20px}
label{display:block;margin-top:10px;font-weight:600}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #ddd;border-radius:5px}
button{width:100%;padding:12px;margin-top:20px;border:none;border-radius:6px;background:#ff1a1a;color:#fff;font-size:16px;cursor:pointer}
button:hover{background:#cc0000}
.summary{background:#fcfcfc;padding:10px;border-radius:6px;margin-bottom:12px}
.small{font-size:14px;color:#666}
.message{margin-top:12px}
</style>
</head>
<body>
<div class="container">
<h1>Checkout</h1>

<?php if ($message) echo $message; ?>

<?php if (!$order_placed): ?>
    <div class="summary">
        <div class="small"><strong>Order Summary</strong></div>
        <?php if (count($cart_items) === 0): ?>
            <div class="small">Your cart is empty.</div>
        <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="small">
                    <?= htmlentities($item['rim_name']) ?> — Qty: <?= $item['quantity'] ?> — Unit: R<?= number_format($item['price'],2) ?> — Sub: R<?= number_format($item['price']*$item['quantity'],2) ?>
                </div>
            <?php endforeach; ?>
            <hr>
            <div><strong>Total: R<?= number_format($total,2) ?></strong></div>
        <?php endif; ?>
    </div>

    <form method="POST">
        <label>Name on Card</label>
        <input name="card_name" required placeholder="Cardholder name">

        <label>Card Number</label>
        <input name="card_number" required placeholder="Numbers only">

        <label>Expiry (MM/YY)</label>
        <input name="expiry" required placeholder="MM/YY">

        <label>CVV</label>
        <input name="cvv" required placeholder="3 or 4 digits">

        <button type="submit" name="place_order">Place Order (R<?= number_format($total,2) ?>)</button>
    </form>
<?php endif; ?>
</div>
</body>
</html>