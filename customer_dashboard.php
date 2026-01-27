<?php
include('connection.php');
session_start();

//session check:
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}

try{

//Getting user_id from URL and validating it (using GET method)
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = trim($_GET['user_id']);

    // Fetching customer
 $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo "Customer not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
}  catch (Exception $e) {
    // General error handler
    echo "Error: " . $e->getMessage();
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="css/customer_dashboard.css">

    
</head>

<header>
  <h1>Welcome, <?= htmlentities($customer['name']) ?> <?= htmlentities($customer['surname']) ?> </h1>
  <h2>Customer Dashboard</h2>
</header>

<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="customer_dashboard.php?user_id=<?= $user_id ?>">Wheels of Fortune</a>
        </div>
        <ul class="nav-links">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>






<div class="container">
  <a href="products.php?user_id=<?= urlencode($user_id) ?>" class="card"> Browse Products</a>
  <a href="c_order_history.php?user_id=<?= urlencode($user_id) ?>" class="card"> My Order History</a>
  <a href="c_cart.php?user_id=<?= urlencode($user_id) ?>" class="card"> My Cart</a>
</div>
    
</body>
</html>