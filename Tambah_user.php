<?php
require 'Login_function.php';
require 'cek.php';
require 'dbconnect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username']; 
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // hashing password
    $role = $_POST['role'];

    $stmt = $koneksi->prepare("INSERT INTO login (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'sukses', 'pesan' => 'User berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Error menambahkan user']);
    }
}
?>
