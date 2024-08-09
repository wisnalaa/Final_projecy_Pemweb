<?php
// Edit_barang.php

// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "dbmebel");

// Handle image upload function
function uploadImage($file) {
    $targetDir = "Images/";
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($file["tmp_name"], $targetFilePath);
    return $fileName;
}

// Cek apakah ada data yang dikirim dari form edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_product'])) {
    $id = $_POST['id_product'];
    $name = $_POST['name'];
    $desk = $_POST['desk'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_FILES['image']['name'];

    // Cek apakah ada file gambar yang diunggah
    if (!empty($image)) {
        $target = "Images/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "UPDATE product SET name=?, color=?, price=?, desk=?, quantity=?, image=? WHERE id_product=?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssssisi", $name, $color, $price, $desk, $quantity, $image, $id);
        } else {
            echo "Failed to upload image";
            exit;
        }
    } else {
        $sql = "UPDATE product SET name=?, color=?, price=?, desk=?, quantity=? WHERE id_product=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssssii", $name, $color, $price, $desk, $quantity, $id);
    }

    // Eksekusi pernyataan SQL untuk mengupdate data produk
    if ($stmt->execute()) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product";
    }
} else {
    echo "Invalid request";
    exit;
}

if ($stmt->execute()) {
    echo "Product updated successfully";
    // Redirect back to index.php
    header("Location: index.php");
    exit(); // Ensure script stops execution after redirection
} else {
    echo "Error updating product";
}

?>
