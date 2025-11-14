<?php
include('connection.php');
session_start();

//session check: only admin allowed
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Inventory Manager') {
    header("Location: login.php");
    exit();
}

try {
    //check if ID is passed
 

        if (isset($_GET['rim_id']) && is_numeric($_GET['rim_id'])) {
    $id = trim($_GET['rim_id']);

        //Fetch product info before deletion 
        $stmt = $pdo->prepare("SELECT * FROM rims WHERE rim_id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // --- Delete the image file first ---
            if (!empty($product['image_url'])) {
                // Assuming image_url looks like: "uploaded_images/Rim1.jpg"
                $imagePath = __DIR__ . '/' . $product['image_url'];

                if (file_exists($imagePath)) {
                    unlink($imagePath); // delete the image file
                }
            }

            // --- Delete the product record ---
            $stmt = $pdo->prepare("DELETE FROM rims WHERE rim_id = ?");
            $stmt->execute([$id]);    

            //Redirect with message
            echo '<script>
                alert("Product deleted successfully!");
                window.location.href = "inventory_dashboard.php?user_id=' . urlencode($_GET['user_id']) . '";
                
            </script>';                  
            exit();

        } else {
            echo '<script>
                alert("Product not found");
                window.location.href = "inventory_dashboard.php?user_id=' . $_SESSION['user_id'] . '";
            </script>';
        }
    } else {
        echo '<script>
            alert("Invaliddddddddd request");
            window.location.href = "inventory_dashboard.php?user_id=' . $_SESSION['user_id'] . '";
        </script>';
    }
} catch (Exception $e) {
    // General error handler
    echo "Error: " . $e->getMessage();
}
?>

