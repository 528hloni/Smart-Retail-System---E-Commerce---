 <?php
  
 include('connection.php');
session_start();


if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Payment Processor' && $_SESSION['role'] !== 'Inventory Manager' && $_SESSION['role'] !== 'Sales Associate')) {
    header("Location: login.php");
    exit();
}

try{

    $role = $_SESSION['role'];

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

// Fetch all rims (product list)
    $sql = "SELECT * FROM rims";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Document</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>


<?php if ($role === 'Customer'): ?>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="customer_dashboard.php?user_id=<?= $user_id ?>">Wheels of Fortune</a>
        </div>
        <ul class="nav-links">
            <li><a href="customer_dashboard.php?user_id=<?= $user_id ?>">Dashboard</a></li>
            <li><a href="products.php?user_id=<?= $user_id ?>">Shop</a></li>
            <li><a href="c_cart.php?user_id=<?= $user_id ?>">Cart </a></li>
            <li><a href="c_order_history.php?user_id=<?= $user_id ?>">My Orders</a></li>
            
        </ul>
    </div>
</nav>

<!-- ======= NAVBAR STYLE ======= -->
<style>
/* Reset default margins */
body, ul {
    margin: 0;
    padding: 0;
}

/* Navbar container */
.navbar {
    background: linear-gradient(90deg, #000000, #1a0000);
    padding: 15px 0;
    box-shadow: 0 4px 10px rgba(255, 0, 0, 0.3);
    font-family: "Poppins", sans-serif;
    position: sticky;
    top: 0;
    z-index: 100;
}

/* Inner content layout */
.nav-container {
    width: 90%;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Logo */
.nav-logo a {
    color: #ff1a1a;
    text-decoration: none;
    font-size: 24px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s;
}

.nav-logo a:hover {
    color: #fff;
    text-shadow: 0 0 10px #ff1a1a;
}

/* Navigation links */
.nav-links {
    list-style: none;
    display: flex;
    gap: 30px;
}

.nav-links li a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: 0.3s ease;
}

.nav-links li a:hover {
    color: #ff1a1a;
    text-shadow: 0 0 5px #ff1a1a;
}

/* Hover underline animation */
.nav-links li a::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background: #ff1a1a;
    transition: width 0.3s;
}

.nav-links li a:hover::after {
    width: 100%;
}

/* Optional: Add slight glow for Hot Wheels feel */
.navbar {
    border-bottom: 2px solid #ff1a1a;
}
</style>
<?php endif; ?>



    <h1> PREMIUM RIMS FOR YOUR RIDE </h1>
    <br><br>
    <h3>Upgrade Your Wheels, Upgrade Your Style</h3>
    <br><br>

<h2>FEATURED RIMS</h2>

    <div class="product-grid">
        <?php
        
        foreach ($results as $row): ?>
    <a href="product_details.php?rim_id=<?= $row['rim_id'] ?>&user_id=<?= $user_id ?>" class="product-link">    
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
    
</body>
</html>