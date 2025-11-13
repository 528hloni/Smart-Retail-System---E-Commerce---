<?php
include('connection.php');
session_start();

// Session Check
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}

if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

    // Redirect back to cart
    header("Location: c_cart.php?user_id=$user_id");
    exit();

} catch (PDOException $e) {
    echo "Error deleting cart items: " . $e->getMessage();
}
} else {
    echo "No user specified.";
}
?>