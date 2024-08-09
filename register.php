<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FurniturQyu Home</title>
    <link rel="stylesheet" href="css/register.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <img src="Images/logo.png" alt="FurniturQyu Home Logo" class="logo">
            <h2>Register</h2>
            <p>Enter your username, email, password, and phone number</p>
            <form action="process_register.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="notelp" placeholder="Phone Number">
                <button type="submit" name="register" class="btn btn-primary">Register</button>
            </form>
            <div class="notification"></div> <!-- Container for notification -->
            <p>Already have an account? <a href="login.html">Log in</a></p>
            <a href="privacy.html" class="privacy-link">Privacy</a>
        </div>
    </div>
</body>
</html>
