<?php
require 'Login_function.php'; 
require 'cek.php';
require 'dbconnect.php';

if (isset($_POST['id_user'])) {
    $id_user = $_POST['id_user'];
    $stmt = $koneksi->prepare("SELECT * FROM login WHERE iduser = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'User tidak ditemukan.']);
    }
}
?>
