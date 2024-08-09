<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['log'])) {
    header('location: Login.php');
    exit;
}

$id_user = $_POST['id_user'];
$query = "DELETE FROM login WHERE iduser = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_user);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'User berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Error menghapus user']);
}
?>
