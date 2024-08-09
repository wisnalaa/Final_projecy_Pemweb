<?php
session_start();
require "Login_function.php";
require "cek.php";

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password  = $_POST["password"];

    $cekuser = mysqli_query($koneksi, "SELECT * FROM login WHERE username = '$username' AND password = '$password'");
    $hitung = mysqli_num_rows($cekuser);

    if ($hitung > 0) {
        $_SESSION['log'] = 'True';
        $ambildatarole = mysqli_fetch_array($cekuser);
        $role = $ambildatarole["role"];
        $_SESSION['log'] = 'logged';
        $_SESSION['role'] = $role;
        $_SESSION['iduser'] = $ambildatarole["iduser"];

        if ($role == 'admin') {
            header('location: index.php');
        } else if ($role == 'user') {
            header('location: Account.php');
        } else {
            header('location: Account.php');
        }
    } else {
        echo "<script>alert('Username or password is incorrect');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FurniturQyu Home</title>
    <link rel="stylesheet" href="css/Login2.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="Login2-container">
        <div class="Login2-box">
            <img src="Images/logo.png" alt="FurniturQyu Home Logo" class="logo">
            <h2>Log in</h2>
            <p>Enter your username or Email<br> and password</p>
            <form id="loginForm" method="post">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username or Email" required>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
            <a href="privacy.html" class="privacy-link">Privacy</a>
        </div>
    </div>
    <div id="notification" style="display:none;" class="alert alert-success"></div>
</body>
</html>
