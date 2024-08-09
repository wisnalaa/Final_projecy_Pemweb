<?php
$koneksi = mysqli_connect("localhost","root","","dbmebel");

//menambah barang baru
if (isset($_POST["addnewproduct"])) {
    $name = $_POST["name"];
    $desk = $_POST["desk"];
    $color = $_POST["color"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $image = $_FILES['image']['name'];
    $target = "Images/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $addtotable = mysqli_query($koneksi, "INSERT INTO product (name, color, price, desk, quantity, image) VALUES ('$name','$color', '$price', '$desk', '$quantity', '$image')");
        if ($addtotable) {
            header("location: index.php");
        } else {
            echo 'gagal menambah produk';
            header('location: index.php');
        }
    } else {
        echo 'gagal upload gambar';
    }
}


// Update product
if (isset($_POST['updateproduct'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['desk'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $color = $_POST['color'];
    $image = $_FILES['image']['name'];



    if ($image) {
        $target = "Images/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "UPDATE product SET name=?, desk=?, quantity=?, price=?, color=?, image=? WHERE id_product=?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssissi", $name, $description, $quantity, $price, $color, $image, $id);
        } else {
            echo 'gagal upload gambar';
            return;
        }
    } else {
        $sql = "UPDATE product SET name=?, desk=?, quantity=?, price=?, color=? WHERE id_product=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssisi", $name, $description, $quantity, $price, $color, $id);
    }

    if ($stmt->execute()) {
        header("location: index.php");
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}


// Delete Product
if (isset($_POST['deleteproduct'])) {
    $id_product = $_POST['id_product'];
    $sql = "DELETE FROM product WHERE id_product=?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_product);

    if ($stmt->execute()) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
}


?>
