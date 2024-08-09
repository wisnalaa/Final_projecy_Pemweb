<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
require 'conf.php';

$id = $_GET['id_product'];
$query = mysqli_query($conn, "select * from product where id_product = '$id'");

function rupiah($price){
    return "IDR " . number_format($price, 2, ',', '.');
}    

if (!$query) {
    die("Error: " . $conn->error);
} elseif ($query->num_rows > 0) {
    if ($row = $query->fetch_assoc()) {
        $idproduct = $row['id_product'];
        $name = htmlspecialchars($row['name']);
        $color = htmlspecialchars($row['color']);
        $image = htmlspecialchars($row['image']);
        $price = htmlspecialchars($row['price']);
        $desk = htmlspecialchars($row['desk']); // Assuming 'description' is the column name in your database

        echo '
        <div class="Container">
            <div class="image">
                <img src="'. $image .'" alt="'. $name .'">
            </div>
            <div class="details">
                <form id="wishlistForm" method="POST" action=""> 
                    <div class="name">'. $name .'</div>
                    <div class="stock">In Stock</div>
                    <div class="harga">'. rupiah($price) .'</div>
                    <div class="deskripsi">'. $desk .'</div>
                    <div class="wishlist">
                        <button class="btn-wish" type="submit" name="add_wish">
                            <i class="fa-regular fa-heart fa-2xl"></i>
                        </button>
                        <button class="buy_button" type="submit" name="add_cart">Add to cart</button>
                    </div>
                </form>
            </div>
        </div>';
    }
} else {
    echo "No products found.";
}


if(isset($_POST['add_wish'])) {

    $idproduct = $row['id_product'];
    $name = htmlspecialchars($row['name']);
    $color = htmlspecialchars($row['color']);
    $image = htmlspecialchars($row['image']);
    $price = htmlspecialchars($row['price']);
    $desk = htmlspecialchars($row['desk']);

    $select_wish = mysqli_query($conn, "SELECT * FROM wishlist WHERE name = '$name'");

    if (mysqli_num_rows($select_wish) > 0) {
        // echo '<script>swal("Product is in the list!");</script>';
        echo "<script>alert('Product already in the list')</script>";
        echo "<script>window.location = 'detailProduk.php?id_product='id_product''</script>";
    } else {
        $insert_wish = mysqli_query($conn, "INSERT INTO wishlist (id_product, name, color, image, price, desk) VALUES ('$idproduct', '$name', '$color', '$image', '$price', '$desk')");
        if ($insert_wish) {
            // echo '<script>swal("Barang berhasil dimasukkan!");</script>';
            echo "<script>alert('Product succesfully added!')</script>";
            echo "<script>window.location = 'detailProduk.php?id_product='id_product''</script>";
        } else {
            // echo '<script>swal("Barang gagal dimasukkan!");</script>';
            echo "<script>alert('Product failed adde!')</script>";
            echo "<script>window.location = 'detailProduk.php?id_product='id_product''</script>";
        }
    }
}

if(isset($_POST['add_cart'])) {

    $idproduct = $row['id_product'];
    $name = htmlspecialchars($row['name']);
    $color = htmlspecialchars($row['color']);
    $image = htmlspecialchars($row['image']);
    $price = htmlspecialchars($row['price']);
    $desk = htmlspecialchars($row['desk']);

    $select_wish = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$name'");

    if (mysqli_num_rows($select_wish) > 0) {
        // echo '<script>swal("Product is in the list!");</script>';
        echo "<script>alert('Product already in the list')</script>";
        echo "<script>window.location = 'detailProduk.php?id_product='id_product''</script>";
    } else {
        $insert_wish = mysqli_query($conn, "INSERT INTO cart (id_product, name, color, image, price, desk) VALUES ('$idproduct', '$name', '$color', '$image', '$price', '$desk')");
        if ($insert_wish) {
            // echo '<script>swal("Barang berhasil dimasukkan!");</script>';
            echo "<script>alert('Product succesfully added!')</script>";
            echo "<script>window.location = 'detailProduk.php?id_product='id_product''</script>";
        } else {
            // echo '<script>swal("Barang gagal dimasukkan!");</script>';
            echo "<script>alert('Product failed adde!')</script>";
            echo "<script>window.location = 'detailProduk.php?id_product='id_product''</script>";
        }
    }
}

?>