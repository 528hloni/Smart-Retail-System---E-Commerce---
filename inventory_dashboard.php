<?php
include('connection.php');
session_start();


//session check: only admin is allowed here
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Inventory Manager' ) {
    header("Location: login.php");
    exit();
}






try{

    //Getting user_id from URL and validating it (using GET method)
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = ($_GET['user_id']);
    } else {
    echo "Invalid request.";
    exit();
    }
   

    // fetch data to display in table
$stmt = $pdo->prepare("
    SELECT rim_id, rim_name, model, size_inch, price, quantity, image_url
    FROM rims
");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

//button action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action ==='Add New Wheel'){
        header('Location: inventory_add_product.php?user_id=' . urlencode($_GET['user_id']));
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
    <title>Inventory Dashboard</title>
    <link rel="stylesheet" href="css/inventory_dashboard.css">
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
<div class="container">
    <h1>Inventory Dashboard</h1>
    <br>
    <form method="POST">
        
        <br><br>
        <input type="submit" name="action" value="Add New Wheel">
        <br><br>
        <input type="text" id="search_input" name="search_input" placeholder="Search Name Or Model...">
       


        

    </form>

    <table id="inventory_table" border="1">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Model</th>
                <th>Size</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
       </thead>

       <tbody>
        <?php foreach ($products as $row): ?>
        <tr>
            
            <td><img src="<?= $row['image_url'] ?>" alt="<?= $row['rim_name'] ?>" width="100">  </td>
            
            <td><?php echo htmlentities($row['rim_name']); ?></td>
            <td><?php echo htmlentities($row['model']); ?></td>
            <td><?php echo htmlentities($row['size_inch']); ?></td>
            <td><?php echo htmlentities($row['price']); ?></td>
            <td id="stock_<?= $row['rim_id']; ?>"> <!-- For real time stock update -->
                <?php echo htmlentities($row['quantity']); ?>
            </td>
            <td>
         
        <a href="product_details.php?rim_id=<?= $row['rim_id'] ?>&user_id=<?= $user_id ?>">View</a>  |
        <a href="<?php echo 'inventory_update_product.php?rim_id=' . $row['rim_id'] . '&user_id=' . $user_id; ?>">Update</a> |
        <a href="<?php echo 'inventory_delete_product.php?rim_id=' . $row['rim_id'] . '&user_id=' . $user_id; ?>" 
        onclick="return confirm('Are you sure you want to delete this wheel?');">Delete</a> |
           
          
    </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>


        <script src="js/inventory_dashboard.js"></script>








   
</body>
</html>