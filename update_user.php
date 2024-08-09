<?php
require 'Login_function.php';
require 'cek.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['iduser'] ?? null;
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $role = $_POST['role'] ?? null;

    if ($id_user && $username && $email && $role) {
        if ($password) {
            // Update password if provided
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("UPDATE login SET username = ?, email = ?, password = ?, role = ? WHERE iduser = ?");
            $stmt->bind_param("ssssi", $username, $email, $hashedPassword, $role, $id_user);
        } else {
            // Do not update password if not provided
            $stmt = $koneksi->prepare("UPDATE login SET username = ?, email = ?, role = ? WHERE iduser = ?");
            $stmt->bind_param("sssi", $username, $email, $role, $id_user);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "sukses", "pesan" => "Pengguna berhasil diupdate"]);
        } else {
            echo json_encode(["status" => "gagal", "pesan" => "Terjadi kesalahan saat mengupdate pengguna"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "gagal", "pesan" => "Semua field harus diisi"]);
    }
}
?>
