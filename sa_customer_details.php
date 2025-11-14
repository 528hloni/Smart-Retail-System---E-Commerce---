<?php
  
 include('connection.php');
session_start();


if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Sales Associate')) {
    header("Location: login.php");
    exit();
}

try{

//Getting user_id from URL and validating it (using GET method)
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = trim($_GET['user_id']);

        // Fetching user data to prefill the form
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action ==='Return Dashboard'){
        
        header('Location: sales_associate_dashboard.php');
        exit();
    }
}

} catch (Exception $e) {
    // Handle general errors
    echo "Error: " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales: Customer Details</title>
    <link rel="stylesheet" href="sales_associate_dashboard.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">Wheels Of Fortune</div>
        <ul class="nav-links">
            <li><a href="sales_associate_dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<h1> Customer Profile : <?= htmlentities($customer['name']) ?> <?= htmlentities($customer['surname']) ?> </h1> 
<br>

   <p><strong>User ID:</strong> <br>
   <?= htmlentities($customer['user_id']) ?></p>
   <p><strong>ID Number:</strong> <br>
   <?= htmlentities($customer['id_number']) ?></p>
   <p><strong>Date Of Birth:</strong> <br> 
   <?= htmlentities($customer['date_of_birth']) ?></p>
   <p><strong>Email:</strong> <br>
   <?= htmlentities($customer['email']) ?></p>
   <p><strong>Phone:</strong> <br>
   <?= htmlentities($customer['phone']) ?></p>
   
   <form method="POST">
    <input type="submit" name="action" value="Return Dashboard">
</form>
  
   
    
</body>
</html>