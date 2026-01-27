<?php
include('connection.php');
session_start();

//session check: only admin is allowed here
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Sales Associate') {
    header("Location: login.php");
    exit();
}



try {
    // Total sales today
    $stmt = $pdo->prepare("
    SELECT COUNT(*) AS total_sales_today
        FROM orders
        WHERE status = 'Completed' 
          AND DATE(order_date) = CURDATE()
        
    ");
    $stmt->execute();
    $today_sales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales_today'] ?? 0;

    // Total sales this week
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS total_sales_week
        FROM orders
        WHERE status = 'Completed'
          AND YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1)
    ");
    $stmt->execute();
    $week_sales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales_week'] ?? 0;

    // Total sales this month
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS total_sales_month
        FROM orders
        WHERE status = 'Completed'
          AND MONTH(order_date) = MONTH(CURDATE())
          AND YEAR(order_date) = YEAR(CURDATE())
    ");
    $stmt->execute();
    $month_sales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales_month'] ?? 0;

    
    // Top 3 selling rims
    $stmt = $pdo->prepare("
        SELECT 
            r.rim_name,
            r.model,
            r.image_url,
            SUM(oi.quantity) AS total_sold,
            SUM(oi.quantity * oi.unit_price) AS total_revenue
        FROM order_items oi
        JOIN rims r ON oi.rim_id = r.rim_id
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.status = 'Completed'
        GROUP BY r.rim_id, r.rim_name, r.model
        ORDER BY total_sold DESC
        LIMIT 3
    ");
    $stmt->execute();
    $top_rims = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Orders table
    $stmt = $pdo->prepare("
    SELECT 
        o.order_id,
        o.user_id,
        u.name,
        u.surname,
        o.order_date,
        o.total_amount,
        o.status,
        o.payment_status
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="css/sales_associate_dashboard.css">
    
</head>
<body>

 <nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            Wheels Of Fortune
        </div>
        <ul class="nav-links">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
<br>
<br>






    <h1>Sales Associate Dashboard</h1>
    <br><br>

    


    <div class="sales-stats">
    <div class="sales-card">
        <h3>Total Sales This Month</h3>
        <p><?= htmlentities($month_sales) ?></p>
    </div>
    <div class="sales-card">
        <h3>Total Sales This Week</h3>
        <p><?= htmlentities($week_sales) ?></p>
    </div>
    <div class="sales-card">
        <h3>Total Sales Today</h3>
        <p><?= htmlentities($today_sales) ?></p>
    </div>
</div>
    <br>
    <div class="top-products">
    <h2>Top 3 Selling Wheels</h2>
    <div class="top-rims-container">
        <?php if (count($top_rims) > 0): ?>
            <?php foreach ($top_rims as $rim): ?>
                <div class="rim-card">
                     
                    <img src="<?= $rim['image_url'] ?>" alt="<?= $rim['rim_name'] ?>" >
                    <h3><?= htmlentities($rim['rim_name']); ?></h3>
                    <p>Model: <?= htmlentities($rim['model']); ?></p>
                    <p>Total Sold: <?= htmlentities($rim['total_sold']); ?></p>
                    <p>Total Revenue: R<?= number_format($rim['total_revenue'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No top rims available.</p>
        <?php endif; ?>
    </div>
</div>
    <br>
    <form method="POST">
        <h3>All Orders</h3>
        <input type="text" id="search_input" name="search_input" placeholder="Search Customer...">
        <div id="autocomplete-results"></div>
         <select id="filter" name="filter">
            <option value="" >All Order</option>
            <option value="Completed">Completed</option>
            <option value="Pending">Pending</option>
            <option value="Cancelled">Cancelled</option>
        </select>
        
        <form method="post" action="export_report.php">
    <input type="submit" name="action" >Export PDF</button>
</form>
        
        <br><br>


        <table id="orders" border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Actions [View]</th>
                
            </tr>
       </thead>
       <tbody>
        <?php foreach ($orders as $row): ?>
        <tr>
            <td><?php echo htmlentities($row['order_id']); ?></td>
            <td><?php echo htmlentities($row['name']) ." ". htmlentities($row['surname']); ?></td>
            <td><?php echo htmlentities($row['order_date']); ?></td>
            <td><?php echo htmlentities($row['total_amount']); ?></td>
            <td><?php echo htmlentities($row['status']); ?></td>
            <td><?php echo htmlentities($row['payment_status']) ?></td>
            <td>
                <a href="sa_customer_details.php?user_id=<?= $row['user_id'] ?>">Customer Details</a> | 
                <a href="sa_order_items.php?order_id=<?= $row['order_id'] ?>">Order Items</a>  
                
                 
            </td>
            <?php endforeach; ?>




       </tbody>



      
    </form>




  <div class="pagination" id="pagination"></div>

  <script src="js/sales_associate_dashboard.js"></script>

</body>
</html>