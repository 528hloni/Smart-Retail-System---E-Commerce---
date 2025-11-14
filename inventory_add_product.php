<?php
session_start();
include('connection.php');



//session check
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Inventory Manager') {
    header("Location: login.php");
    exit();
}




try{

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

    if($action === 'Save' && $wheel_name && $wheel_model && $size_inch && $bolt_pattern && $offset && $center_bore && $color && $price && $stock_quantity){
        // Handle file upload
        if (isset($_FILES['wheel_image']) && $_FILES['wheel_image']['error'] === UPLOAD_ERR_OK) {
           $fileTmpPath = $_FILES['wheel_image']['tmp_name'];
           $fileName = $_FILES['wheel_image']['name'];
           $fileNameCmps = explode(".", $fileName);
           $fileExtension = strtolower(end($fileNameCmps));

            

             $baseName = preg_replace("/[^a-zA-Z0-9_\-]/", "_", pathinfo($fileName, PATHINFO_FILENAME));
             $baseName = substr($baseName, 0, 200); // keep filename length reasonable
             $candidateName = $baseName . '.' . $fileExtension;

              $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($fileExtension, $allowedfileExtensions)) {
        echo 'Invalid file type. Allowed types: jpg, jpeg, png, gif.';
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

    // If a file with the candidate name already exists, append suffix (_1, _2, ...)
    $finalName = $candidateName;
    $i = 1;
    while (file_exists($uploadedDir . $finalName)) {
        $finalName = $baseName . '_' . $i . '.' . $fileExtension;
        $i++;
    }

    $uploadedFullPath = $uploadedDir . $finalName;

    // Path in Rims_Images to check (staging area)
    $rimsFolderPath = __DIR__ . '/Rims_Images/' . $fileName;

    if (file_exists($rimsFolderPath)) {
        // Copy from staging to uploaded (preserve original file content)
        if (!copy($rimsFolderPath, $uploadedFullPath)) {
            echo 'Error: Failed to copy file from Rims_Images to Uploaded_Images.';
            exit();
        }
    } else {
        // Move uploaded temp file into Uploaded_Images
        if (!move_uploaded_file($fileTmpPath, $uploadedFullPath)) {
            echo 'Error: Failed to move uploaded file to Uploaded_Images.';
            exit();
        }
    }

    // Save DB path (use forward slashes for web)
    $image_url = 'Uploaded_Images/' . $finalName;
} else {
    echo 'Please upload a valid image.';
    exit();
}




           
               

               
               

        // Insert into database

    $stmt1 = $pdo->prepare("INSERT INTO rims (rim_name, model, size_inch, bolt_pattern, offset, center_bore, color, price, quantity, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->execute([$wheel_name, $wheel_model, $size_inch, $bolt_pattern, $offset, $center_bore, $color, $price, $stock_quantity, $image_url]);

    echo  '<script>
         alert("New Wheel added successfully!");
        </script>';
        header('Location: inventory_dashboard.php?user_id=' . urlencode($_GET['user_id']));
        exit();

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
    
        header('Location: inventory_dashboard.php?user_id=' . urlencode($_GET['user_id']));
        
    } else {
        echo "invalid request";
        exit();
    }
    exit();
 

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
    <title>Inventory Add Product</title>
    <link rel="stylesheet" href="inventory_add_product.css">
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
    <h1> Add New Wheel </h1>
    <br><br>
    <form method="POST" enctype="multipart/form-data">
        <h2>1 Wheel Information</h2>
        <br>
        <label for="wheel_name">Wheel Name</label>
        <input type="text" id="wheel_name" name="wheel_name" required>
        <br>
        <label for="wheel_model">Wheel Model</label>
        <input type="text" id="wheel_model" name="wheel_model" required>
        <br><br>
        <h2> 2 Wheel Specifications </h2>
        <br>
        <label for="size_inch">Size Inch</label>
        <input type="number" id="size_inch" name="size_inch" min="13" max="25" step="any" required>
        <br>
        <label for="bolt_pattern">Bolt Pattern</label>
        <input type="text" id="bolt_pattern" name="bolt_pattern" required>
        <br>
        <label for="offset">Offset</label>
        <input type="text" id="offset" name="offset" required>
        <br>
        <label for="center_bore">Center Bore</label>
        <input type="text" id="center_bore" name="center_bore" required>
        <br>
        <label for="color">Color</label>
        <input type="text" id="color" name="color" required>
        <br><br>
        <h2> 3 Pricing and Inventory </h2>
        <br>
        <label for="price">Price (ZAR)</label>
        <input type="number" id="price" name="price" required>
        <br>
        <label for="stock_quantity">Stock Quantity</label>
        <input type="number" id="stock_quantity" name="stock_quantity" required>
        <br><br>
        <h2> 4 Wheel Image </h2>
        <label for="wheel_image">Upload Image:</label>
        <input type="file" id="wheel_image" name="wheel_image" accept="image/*" required>
        <br><br>

    
        <input type="submit" name="action" value="Save">
        <input type="reset" name="action" value="Reset"> <br>
        <input type="submit" name="action" value="Return To Dashboard" formnovalidate>




    </form>
    


</body>
</html>