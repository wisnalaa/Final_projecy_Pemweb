<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "dbmebel");

// Pastikan ID produk dikirim melalui POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query untuk mengambil data produk berdasarkan ID
    $stmt = $koneksi->prepare("SELECT * FROM product WHERE id_product = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan, kirim data dalam format JSON
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'Produk tidak ditemukan'));
    }
} else {
    echo json_encode(array('error' => 'ID produk tidak tersedia'));
}
?>
