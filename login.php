<?php 
session_start();
include('connection.php');



//user input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']??'');


    try{

    if ($action === 'Login' && $email && $password){ //checking if button Login was clicked and all inputs are filled
        // $sql = "SELECT * FROM users WHERE email = ?"; // query to find user with matching email
         $sql = "SELECT user_id, email, password_hash, role FROM users WHERE email = ?";   
         $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            //if matching user is found then compare passwords(input and database)
            if ($user) {

                if (password_verify($password, $user['password_hash'])) {
        // Redirect based on role
        $role = $user['role'];
       
       $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $role;
        $_SESSION['loggedin'] = true;

        if ($role === 'Sales Associate') {
            header('Location: sales_associate_dashboard.php'); 
            exit();
        } elseif ($role === 'Inventory Manager') {
            header('Location: inventory_dashboard.php?user_id=' . $user['user_id']);
           exit();
        } elseif ($role === 'Payment Processor') {
            header('Location: payment_processor_dashboard.php');
            exit();
        } elseif ($role === 'Customer') {
            header('Location: customer_dashboard.php?user_id=' . $user['user_id']);
            
            exit();
        }

                


                }else{ //alert if password is incorrect
                    echo '<script>  
                    alert("Login failed, Invalid email or password ")
                    </script>';
                }
            
            } else { //alert if user not found
     echo '<script>  
    alert("Login failed, Invalid email or password!")
    </script>';
   
}

    }

} catch (Exception $e) {
    // Handle general errors
    echo "Error: " . $e->getMessage();
}
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheels Of Fortune - Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Wheels Of Fortune</h1>
            <h3>Where Every Wheel Is A Win!</h3>
        </div>

        <form method="POST" class="login-form">
            <p class="form-title">Login to your account</p>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="your@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <input type="submit" name="action" value="Login" class="btn-login">

            <p class="register-text">
                Don’t have an account? <a href="register.php">Register</a>
            </p>
        </form>
    </div>
</body>
</html>
