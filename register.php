<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2> Register to start shopping </h2>
    <form method="POST">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" required>
        <br>
        <label for="date_of_birth">Date of Birth</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required>
        <br>
        <label for="identity_number">ID Number</label>
        <input type="number" id="identity_number" name="identity_number" required>
        <br>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="your@gmail.com" required>
        <br>
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" required>
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm_password">Confirm Password </label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br><br>
        <input type="submit" name="action" value="Create Account">
        <br>
        <p> Already have an account? <a href ="login.php">Login </a> </p>
    </form>
</body>
</html>