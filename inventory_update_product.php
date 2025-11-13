<?php
  
 include('connection.php');
session_start();

//session check: only admin is allowed here
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Inventory Manager') {
    header("Location: login.php");
    exit();
}



try{

//Getting rim_id from URL and validating it (using GET method)
if (isset($_GET['rim_id']) && is_numeric($_GET['rim_id'])) {
    $rim_id = trim($_GET['rim_id']);

    // Fetching student data to prefill the form
 $stmt = $pdo->prepare("SELECT * FROM rims WHERE rim_id = ?");
    $stmt->execute([$rim_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Wheel not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $wheel_name = htmlentities(trim($_POST['wheel_name']));
    $wheel_model = htmlentities(trim($_POST['wheel_model']));
    $size_inch = htmlentities(trim($_POST['size_inch']));
    $bolt_pattern = htmlentities(trim($_POST['bolt_pattern']));
    $offset = htmlentities(trim($_POST['offset']));
    $center_bore = htmlentities(trim($_POST['center_bore']));  
    $color = htmlentities(trim($_POST['color']));
    $price = htmlentities(trim($_POST['price']));
    $stock_quantity = htmlentities(trim($_POST['stock_quantity']));

    if($action === 'Update' && $wheel_name && $wheel_model && $size_inch && $bolt_pattern && $offset && $center_bore && $color && $price && $stock_quantity){
        // Handle file upload
        if (isset($_FILES['wheel_image']) && $_FILES['wheel_image']['error'] === UPLOAD_ERR_OK) {
           $fileTmpPath = $_FILES['wheel_image']['tmp_name'];
           $originalName = $_FILES['wheel_image']['name'];
           $fileNameCmps = explode(".", $originalName);
           $fileExtension = strtolower(end($fileNameCmps));

            
             $baseName = preg_replace("/[^a-zA-Z0-9_\-]/", "_", pathinfo($originalName, PATHINFO_FILENAME));
             $baseName = substr($baseName, 0, 200); // limit length
             $candidateName = $baseName . '.' . $fileExtension;

             $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($fileExtension, $allowedfileExtensions)) {
        echo 'Upload failed. Allowed file types: ' . implode(', ', $allowedfileExtensions);
        exit();
    }

    // Ensure Uploaded_Images folder exists
    $uploadedDir = __DIR__ . '/Uploaded_Images/';
    if (!is_dir($uploadedDir)) {
        if (!mkdir($uploadedDir, 0755, true)) {
            echo 'Error: Unable to create Uploaded_Images folder. Check permissions.';
            exit();
        }
    }

    // Resolve final filename (avoid overwrite by appending _1, _2, etc.)
    $finalName = $candidateName;
    $i = 1;
    while (file_exists($uploadedDir . $finalName)) {
        $finalName = $baseName . '_' . $i . '.' . $fileExtension;
        $i++;
    }
    $uploadedFullPath = $uploadedDir . $finalName;

    // Move uploaded file into Uploaded_Images
    if (!move_uploaded_file($fileTmpPath, $uploadedFullPath)) {
        echo 'Error: Failed to move uploaded file to Uploaded_Images.';
        exit();
    }

    // New image URL to store in DB (use forward slash for web)
    $image_url = 'Uploaded_Images/' . $finalName;

    // Delete old image from Uploaded_Images if it exists and points to Uploaded_Images/
    if (!empty($product['image_url'])) {
        // Normalize backslashes to forward slashes
        $oldDbPath = str_replace('\\', '/', $product['image_url']);
        // Only delete if old path is inside Uploaded_Images (safety)
        if (strpos($oldDbPath, 'Uploaded_Images/') === 0) {
            $oldFullPath = __DIR__ . '/' . $oldDbPath;
            if (file_exists($oldFullPath)) {
                // Suppress warning in case of race condition, but you can check return value if you want
                @unlink($oldFullPath);
            }
        }
    }
} else {
    // No new image uploaded — keep existing image path
    $image_url = $product['image_url'];
}



            

        
     // Update table
    $stmt = $pdo->prepare("UPDATE rims SET rim_name = ?, model = ?, size_inch = ?, bolt_pattern = ?, 
                          offset = ?, center_bore = ?, color = ?, price = ?, quantity = ?, image_url = ? WHERE rim_id = ?");
        $stmt->execute([$wheel_name, $wheel_model, $size_inch, $bolt_pattern, $offset, $center_bore, $color, $price, $stock_quantity, $image_url, $rim_id]);

        echo  '<script>
         alert("Wheel updated successfully!");
         window.location.href = "inventory_dashboard.php";
        </script>';

     } else {
        $missing = [];
        if (!$wheel_name) $missing[] = "Wheel Name";
        if (!$wheel_model) $missing[] = "Wheel Model";
        if (!$size_inch) $missing[] = "Size Inch";
        if (!$bolt_pattern) $missing[] = "Bolt Pattern";
        if (!$offset) $missing[] = "Offset";
        if (!$center_bore) $missing[] = "Center Bore";
        if (!$color) $missing[] = "Color";
        if (!$price) $missing[] = "Price";
        if (!$stock_quantity) $missing[] = "Stock Quantity";

        $errorMsg = "Missing or invalid fields: " . implode(", ", $missing);
            echo '<script>alert("' . $errorMsg . '");</script>';
        }

        if($action === 'Return To Dashboard'){
     header('Location: inventory_dashboard.php');
        exit();
}    

}
} catch (Exception $e) {
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
</head>
<body>
    <h1> Update Wheel Information </h1>
    <br><br>
    <form method="POST" enctype="multipart/form-data">
        <h2>1 Wheel Information</h2>
        <br>
        <label for="wheel_name">Wheel Name</label>
        <input type="text" id="wheel_name" name="wheel_name" 
        value="<?= htmlentities($product['rim_name']) ?>" required>
        <br>
        <label for="wheel_model">Wheel Model</label>
        <input type="text" id="wheel_model" name="wheel_model" 
        value="<?= htmlentities($product['model']) ?>" required>
        <br><br>
        <h2> 2 Wheel Specifications </h2>
        <br>
        <label for="size_inch">Size Inch</label>
        <input type="number" id="size_inch" name="size_inch" min="13" max="25" step="any" 
        value="<?= htmlentities($product['size_inch']) ?>" required>
        <br>
        <label for="bolt_pattern">Bolt Pattern</label>
        <input type="text" id="bolt_pattern" name="bolt_pattern" 
        value="<?= htmlentities($product['bolt_pattern']) ?>" required>
        <br>
        <label for="offset">Offset</label>
        <input type="text" id="offset" name="offset" 
        value="<?= htmlentities($product['offset']) ?>" required>
        <br>
        <label for="center_bore">Center Bore</label>
        <input type="text" id="center_bore" name="center_bore" 
        value="<?= htmlentities($product['center_bore']) ?>" required>
        <br>
        <label for="color">Color</label>
        <input type="text" id="color" name="color" 
        value="<?= htmlentities($product['color']) ?>" required>
        <br><br>
        <h2> 3 Pricing and Inventory </h2>
        <br>
        <label for="price">Prixe (ZAR)</label>
        <input type="number" id="price" name="price" 
        value="<?= htmlentities($product['price']) ?>" required>
        <br>
        <label for="stock_quantity">Stock Quantity</label>
        <input type="number" id="stock_quantity" name="stock_quantity" 
        value="<?= htmlentities($product['quantity']) ?>" required>
        <br><br>
        <h2> 4 Wheel Image </h2>
         <img src="<?= $product['image_url'] ?>" alt="<?= $product['rim_name'] ?> " width="100"> 
    <br>
        <label for="wheel_image">Upload Image:</label>
        <input type="file" id="wheel_image" name="wheel_image" accept="image/*" >
        
        <br><br>

        <input type="submit" name="action" value="Update">
        <input type="reset" name="action" value="Reset"> <br>
        <input type="submit" name="action" value="Return To Dashboard" formnovalidate>

    </form>
    
</body>
</html>