<?php

session_start();

$servername = "localhost";   // Ganti dengan nama server MySQL Anda
$username = "root";      // Ganti dengan username MySQL Anda
$password = "";      // Ganti dengan password MySQL Anda
$dbname = "dbmebel";      // Ganti dengan nama database yang ingin Anda gunakan

// Buat koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

require_once('createDb.php');
require_once('component.php');

$database = new createDb("dbmebel", "wishlist");

require 'conf.php';
if (isset($_POST['id_product'])) {
$id = $_POST['id_product'];
$query = mysqli_query($conn, "select * from product where id_product = '$id'");


if(isset($_POST['add_cart'])) {
    if ($row = $query->fetch_assoc()) {
        $idproduct = $row['id_product'];
        $name = htmlspecialchars($row['name']);
        $color = htmlspecialchars($row['color']);
        $image = htmlspecialchars($row['image']);
        $price = htmlspecialchars($row['price']);
        $desk = htmlspecialchars($row['desk']);

        $select_wish = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$name'");

        if (mysqli_num_rows($select_wish) > 0) {
            // echo '<script>swal("Product is in the list!");</script>';
            echo "<script>alert('Product already in the list 1')</script>";
            echo "<script>window.location = 'wishlist.php''</script>";
        } else {
            $insert_wish = mysqli_query($conn, "INSERT INTO cart (id_product, name, color, image, price, desk) VALUES ('$idproduct', '$name', '$color', '$image', '$price', '$desk')");
            if ($insert_wish) {
                // echo '<script>swal("Barang berhasil dimasukkan!");</script>';
                echo "<script>alert('Product succesfully added!')</script>";
                echo "<script>window.location = 'wishlist.php''</script>";
            } else {
                // echo '<script>swal("Barang gagal dimasukkan!");</script>';
                echo "<script>alert('Product failed adde!')</script>";
                echo "<script>window.location = 'wishlist.php''</script>";
            }
        }
    }
    session_unset();
    }
}
// if (isset($_POST['Add'])){
//     // print_r($_POST['id_product']);
//     if(isset($_SESSION['cart'])){
//         // print_r($_SESSION['cart']);
//         echo "<script>alert('Product is added in the cart..!')</script>";
//         echo "<script>window.location = 'Wishlist.php'</script>";

//         $item_array_id = array_column($_SESSION['cart'], "id_product");

//         if(in_array($_POST['id_product'], $item_array_id)){
//             echo "<script>alert('Product is already added in the cart..!')</script>";
//             echo "<script>window.location = 'Wishlist.php'</script>";
//         }else{

//             $count = count($_SESSION['cart']);
//             $item_array = array(
//                 'id_product' => $_POST['id_product']
//             );

//             $_SESSION['cart'][$count] = $item_array;
//         }

//     }else{

//         $item_array = array(
//             'id_product' => $_POST['id_product']
//         );

//         // Create new session variable
//         // $_SESSION['cart'][0] = $item_array;
//         // print_r($_SESSION['cart']);
//         $item_array = array(
//             'id_product' => $_POST['id_product']
//         );
    
//         // Buat variabel sesi baru atau tambahkan ke variabel sesi yang sudah ada
//         if (isset($_SESSION['cart'])) {
//             $count = count($_SESSION['cart']);
//             $_SESSION['cart'][$count] = $item_array;
//         } else {
//             $_SESSION['cart'][0] = $item_array;
//         }
//         // print_r($_SESSION['cart']);
//     }
// }

if (isset($_POST['Trash'])){
    $id_product = $_POST['id_product'];
    $deleteQuery = "DELETE FROM wishlist WHERE id_product='$id_product'";
    if(mysqli_query($conn, $deleteQuery)){
        echo "<script>alert('Product has been Removed from Wishlist...!')</script>";
        echo "<script>window.location = 'Wishlist.php'</script>";
    } else{
        echo "Error:". $deleteQuery . "<br>" . mysqli_error( $conn );
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="../css/Wishstyle.css">

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>

    <section id="header">
        <div class="header header">
            <div class="header-1">
                <a href="Homepage.html" class="logo">
                    <img src="../Images/logo.png" alt="Logo" width="100">
                </a>

                <form action="" class="search-form">
                    <input type="search" name="" placeholder="search here..." id="search-box">
                    <label for="search-box" class="fas fa-search"></label>
                </form>

                <div class="icons">
                    <div id="search-btn" class="fas fa-search"></div>
                    <a href="Wishlist.php" class="fa-regular fa-heart"></a>
                    <a href="cart.php" class="fa-solid fa-cart-plus"></a>
                    <a href="../../Account.php" class="fa-regular fa-user"></a>
                </div>
            </div>

            <div class="header-2">
                <nav class="navbar">
                    <a href="../Homepage.html">HOME</a>
                    <a href="produk.php">SHOP</a>
                    <a href="../AboutUs.html">ABOUT US</a>
                    <a href="contact.php">CONTACT</a>
                </nav>
            </div>
        </div>
    </section>

    <!-- Wishlist -->
    <div class="nose">
        <h2>Wishlist</h2>
    </div>

    <div class="befo">
        <div class="befo-container">
            <?php
                $result= $database->getData();
                while($row=mysqli_fetch_assoc($result)) {
                    component($row['name'], $row['price'], $row['image'], $row['id_product']);
                }
            ?>
        </div>
    </div>
    
    <!-- footer -->
    <section id="footer">
        <div class="footer">
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="../Images/logo.png" alt="Logo">
                </div>
                <div>
                    <p>Email: furniturQyu@gmail.com</p>
                    <br>
                    <p>Alamat: Jalan Contoh No. 123</p>
                    <br>
                    <p>Nomor HP: 08123456789</p>
                </div>
            </div>
            
            <div class="footer-center">
                <div class="useful-links">
                    <h4>Useful Links</h4>
                    <a href="#">About Us</a>
                    <a href="#">Contact Us</a>
                    <a href="#">Blog</a>
                </div>
                <div class="idea-advice">
                    <h4>Idea & Advice</h4>
                    <a href="#">Reviews</a>
                    <a href="#">Get Design Help</a>
                    <a href="#">Material Care</a>
                </div>
            </div>
    
            <div class="footer-right">
                <div class="payment-methods">
                    <h4>Payments Method</h4>
                    <img src="../Images/pay.png" alt="pay">
                </div>
            </div>
        </div>
    </section>

<!-- custom js file link -->
<script src="js/script.js"></script>
</body>
</html>