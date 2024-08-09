<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET name='$name', email='$email', address='$address', birthday='$birthday', country='$country', phone='$phone' WHERE username='$username'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        header("Location: Account.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
