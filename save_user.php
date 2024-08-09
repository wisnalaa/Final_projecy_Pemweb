<?php
session_start();
require 'dbconnect.php';
require "Login_function.php";

if (!isset($_SESSION['log'])) {
    header('location: Login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['iduser'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "UPDATE login SET username = ?, name = ?, email = ?, password = ? WHERE iduser = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ssssi", $username, $name, $email, $password, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data updated successfully');window.location='Account.php';</script>";
    } else {
        echo "<script>alert('Data update failed');window.location='Account.php';</script>";
    }
}
?>
