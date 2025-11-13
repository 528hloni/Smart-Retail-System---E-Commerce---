<?php
include('connection.php');
session_start();

// Session Check
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];

    

    try {
        //  Get the user_id for this cart item
        $stmt = $pdo->prepare("SELECT user_id FROM cart WHERE cart_id = ?");
        $stmt->execute([$cart_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['user_id'];

            //  Delete the cart item
            $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ?");
            $stmt->execute([$cart_id]);

            //  Redirect back to cart with the correct user_id
            header("Location: c_cart.php?user_id=$user_id");
            exit();
        } else {
            echo "Cart item not found.";
        }

    } catch (PDOException $e) {
        echo "Error deleting cart item: " . $e->getMessage();
    }

} else {
    echo "No cart item specified.";
}
?>