 <?php
 
include('connection.php');
session_start();

// Determine if user is logged in
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$role = $isLoggedIn ? $_SESSION['role'] : 'visitor';

// Only fetch user data if logged in and user_id is provided
$user_id = null;
$customer = null;

if ($isLoggedIn && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = trim($_GET['user_id']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo "Customer not found.";
        exit();
    }
}

// Fetch all rims (available to everyone)
try {
    $sql = "SELECT * FROM rims";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="css/products.css">

    
</head>
<body>



<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <?php if ($isLoggedIn && $user_id): ?>
                <a href="customer_dashboard.php?user_id=<?= $user_id ?>">Wheels of Fortune</a>
            <?php else: ?>
                <a href="index.php">Wheels of Fortune</a>
            <?php endif; ?>
        </div>
        <ul class="nav-links">
            <?php if ($isLoggedIn && $user_id): ?>
                <li><a href="customer_dashboard.php?user_id=<?= $user_id ?>">Dashboard</a></li>
                <li><a href="products.php?user_id=<?= $user_id ?>">Shop</a></li>
                <li><a href="c_cart.php?user_id=<?= $user_id ?>">Cart</a></li>
                <li><a href="c_order_history.php?user_id=<?= $user_id ?>">My Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>





    <h1> PREMIUM WHEELS FOR YOUR RIDE </h1>
    <br><br>
    <h3>Upgrade Your Wheels, Upgrade Your Style</h3>
    <br><br>

<h2>FEATURED WHEELS</h2>
  
<div class="product-grid">
    <?php foreach ($results as $row): ?>
        <?php
        // Logged in users go to product details, visitors go to login
        $productLink = $isLoggedIn && $user_id
            ? "product_details.php?rim_id={$row['rim_id']}&user_id={$user_id}"
            : "login.php";
        ?>
        <a href="<?= $productLink ?>" class="product-link">
            <div class="product-card">
                <img src="<?= $row['image_url'] ?>" alt="<?= $row['rim_name'] ?>">
                <h3><?= $row['rim_name'] ?></h3>
                <p><?= $row['size_inch'] ?> inch</p>
                <p><?= $row['color'] ?></p>
                <p class="price">R<?= $row['price'] ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>
     
        
        
    </div>
    
</body>
</html>