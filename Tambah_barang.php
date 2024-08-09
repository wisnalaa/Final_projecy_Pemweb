<?php
$koneksi = mysqli_connect("localhost", "root", "", "dbmebel");

// Handle image upload function
function uploadImage($file) {
    $targetDir = "Images/";
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($file["tmp_name"], $targetFilePath);
    return $fileName;
}

// Cek apakah ada data yang dikirim dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desk = $_POST['desk'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_FILES['image']['name'];

    // Handle file upload
    if (!empty($image)) {
        $target = "Images/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO product (name, desk, color, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssssis", $name, $desk, $color, $price, $quantity, $image);
        } else {
            echo "Failed to upload image";
            exit;
        }
    } else {
        $sql = "INSERT INTO product (name, desk, color, price, quantity) VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssssi", $name, $desk, $color, $price, $quantity);
    }

    // Eksekusi pernyataan SQL untuk menambahkan data produk
    if ($stmt->execute()) {
        echo "Product added successfully";
        // Redirect back to index.php
        header("Location: index.php");
        exit(); // Ensure script stops execution after redirection
    } else {
        echo "Error adding product: " . $stmt->error;
    }
} else {
    echo "Invalid request";
    exit;
}
?>
