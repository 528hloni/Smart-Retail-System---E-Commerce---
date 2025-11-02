<?php
include('connection.php');
session_start();

//session check: only admin allowed
//if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Inventory Manager') {
//    header("Location: login.php");
//    exit();

//}

try{
//check if ID is passed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

//Fetch product info before deletion 
    $stmt = $pdo->prepare("SELECT * FROM rims WHERE rim_id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

 if ($product) {
        // Delete student securely
        $stmt = $pdo->prepare("DELETE FROM product WHERE rim_id = ?");
        $stmt->execute([$id]);    

 //Redirect with  message
        echo '<script>
        alert("Successfully deleted student record");
        window.location.href = "dashboard.php";
       </script>';
        exit();
        
    } else {
    
        echo '<script>
        alert("Student not found");
        window.location.href = "dashboard.php";
       </script>';
    }
} else {
    
    echo '<script>
        alert("Invalid request");
        window.location.href = "dashboard.php";
       </script>';
}
}catch (Exception $e) {
    // General error handler
    echo "Error: " . $e->getMessage();
}

?>        



