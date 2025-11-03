<?php
  
 include('connection.php');
session_start();

//session check: only admin is allowed here
//if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrator') {
//    header("Location: login.php");
//    exit();
//}



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
            $fileName = $_FILES['wheel_image']['name'];
            $fileSize = $_FILES['wheel_image']['size'];
            $fileType = $_FILES['wheel_image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Sanitize file name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            // Check if the file has one of the allowed extensions
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Directory in which the uploaded file will be moved
                $uploadFileDir = './uploaded_images/';
                $dest_path = $uploadFileDir . $newFileName;

                if(move_uploaded_file($fileTmpPath, $dest_path)) 
                {
                  $image_url = $dest_path;
                }
                else 
                {
                  echo 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                  exit();
                }
            } else {
                echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
                exit();
            }
        } else {
             $image_url = $product['image_url'];
        } 

        
     // Update students table
    $stmt = $pdo->prepare("UPDATE rims SET rim_name = ?, model = ?, size_inch = ?, bolt_pattern = ?, 
                          offset = ?, center_bore = ?, color = ?, price = ?, quantity = ?, image_url = ? WHERE rim_id = ?");
        $stmt->execute([$wheel_name, $wheel_model, $size_inch, $bolt_pattern, $offset, $center_bore, $color, $price, $stock_quantity, $image_url, $rim_id]);

        echo  '<script>
         alert("Wheel updated successfully!");
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