<?php
  
 include('connection.php');
session_start();


//if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Student')) {
//    header("Location: login.php");
//    exit();
//}


try{

//Getting _id from URL and validating it (using GET method)
if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $order_id = trim($_GET['order_id']);

            // Fetching order items data to prefill the form
 $stmt = $pdo->prepare("SELECT oi.*, r.rim_name, r.model, r.image_url 
            FROM order_items oi
            JOIN rims r ON oi.rim_id = r.rim_id
            WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$order_items) {
        echo "Order items not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action ==='Return Dashboard'){
        session_destroy();
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
    <title>Document</title>
</head>
<body>


<h1>Order Items for Order <?= htmlentities($order_id) ?></h1>
<table id="orders" border="1">
        <thead>
            <tr>
                <th>Image</th>
                <th>Wheel Name</th>
                <th>Model</th>
                <th>Quantity</th>
                <th>Price (R)</th>
                <th>Total (R)</th>
                
               
                
            </tr>
       </thead>
       <tbody>
        
            <?php foreach ($order_items as $row): ?>
            <tr>
                <td>
                 <img src="<?= $row['image_url'] ?>" alt="<?= $row['rim_name'] ?>"  width="100">
                </td>
                <td><?= htmlspecialchars($row['rim_name']); ?></td>
                <td><?= htmlspecialchars($row['model']); ?></td>
                <td><?= htmlspecialchars($row['quantity']); ?></td>
                <td><?= number_format($row['unit_price'], 2); ?></td>
                <td><?= number_format($row['quantity'] * $row['unit_price'], 2); ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<br>

<form method="POST">
    <input type="submit" name="action" value="Return Dashboard">
</form>
       
    
</body>
</html>