<?php

session_start();

include "conf.php";

require_once('createDb.php');
require_once('component.php');

$db = new createDb("dbmebel", "cart");

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// if (isset($_POST['add'])){
//     if (isset($_SESSION['cart'])){
//         $item_array_id = array_column($_SESSION['cart'], "id_product");

//         if (in_array($_POST['product_id'], $item_array_id)){
//             echo "<script>alert('Product is already added in the cart..!')</script>";
//             echo "<script>window.location = 'cart.php'</script>";
//         } else {
//             $count = count($_SESSION['cart']);
//             $item_array = array(
//                 'id_product' => $_POST['product_id'],
//                 'quantity' => 1 // Default quantity
//             );

//             $_SESSION['cart'][$count] = $item_array;
//         }
//     } else {
//         $item_array = array(
//             'id_product' => $_POST['product_id'],
//             'quantity' => 1 // Default quantity
//         );

//         // Create new session variable
//         $_SESSION['cart'][0] = $item_array;
//     }
// }


// if(isset($_POST['remove'])){
//     // if($_GET['action'] == 'remove'){
//         foreach($_SESSION['cart'] as $key=> $value){
//             if($value['id_product']==$_GET['id']){
//                 $id = $value['id_product'];
//                 $sql = "DELETE FROM cart WHERE id_product = '$id'";
//                 if (mysqli_query($db->conn, $sql)) { // Bagian yang diubah: Cek apakah query berhasil
//                     unset($_SESSION['cart'][$key]);
//                     $_SESSION['cart'] = array_values($_SESSION['cart']);
//                     echo "<script>alert('Product has been Removed...!')</script>";
//                     echo "<script>window.location = 'cart.php'</script>";
//                 } else {
//                     echo "<script>alert('Failed to remove product from database')</script>";
//                 }
//                 // unset($_SESSION['cart'][$key]);
//                 // $_SESSION['cart'] = array_values($_SESSION['cart']);
//                 // echo "<script>alert('Product has been Removed...!')</script>";
//                 // echo "<script>window.location = 'cart.php'</script>";
//             }
//         }
//     // }
// }

if (isset($_POST['Remove'])){
    $id_product = $_POST['id_product'];
    $deleteQuery = "DELETE FROM cart WHERE id_product='$id_product'";
    if(mysqli_query($conn, $deleteQuery)){
        echo "<script>alert('Product has been Removed from Cart...!')</script>";
        echo "<script>window.location = 'cart.php'</script>";
    } else{
        echo "Error:". $deleteQuery . "<br>" . mysqli_error( $conn );
    }
}

// if (isset($_POST['update'])){
//     if ($_GET['action'] == 'update'){
//         $id_product = $_POST['id_product'];
//         $updateQuery = "UPDATE cart set quantity = '$value'  WHERE id_product='$id_product'";
//         foreach ($_SESSION['cart'] as $key => $value){
//             if ($value['id_product'] == $_GET['id']){
//                 $_SESSION['cart'][$key]['quantity'] = $_POST['quantity'];
//                 echo "<script>alert('Quantity has been updated...!')</script>";
//                 echo "<script>window.location = 'cart.php'</script>";
//             }
//         }
//     }
// }

if (isset($_POST['update'])) {
    // Pastikan ada action update pada URL
    if (isset($_GET['action']) && $_GET['action'] == 'update') {
        $id_product = $_GET['id'];
        $new_quantity = $_POST['quantity'];

        // Update quantity di sesi
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id_product'] == $id_product) {
                $_SESSION['cart'][$key]['quantity'] = $new_quantity;
                break;
            }
        }

        // Update quantity di database
        $id_product_safe = mysqli_real_escape_string($db->conn, $id_product);
        $new_quantity_safe = mysqli_real_escape_string($db->conn, $new_quantity);

        $sql_update = "UPDATE cart SET quantity = '$new_quantity_safe' WHERE id_product = '$id_product_safe'";

        if ($db->conn->query($sql_update) === TRUE) {
            // Jika berhasil diupdate
            echo "<script>alert('Quantity has been updated...!')</script>";
            echo "<script>window.location = 'cart.php'</script>";
            exit;
        } else {
            // Jika gagal update
            echo "<script>alert('Failed to update quantity in database')</script>";
            echo "<script>window.location = 'cart.php'</script>";
            exit;
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../css/cart.css">

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
                    <a href="#" class="fa-solid fa-cart-plus"></a>
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

    <section id="cart" >
        <table width="100%">
            <thead>
                <tr>
                    <td>REMOVE</td>
                    <td>IMAGE</td>
                    <td>PRODUCT</td>
                    <td>PRICE</td>
                    <td>QUANTITY</td>

                </tr>
            </thead>
            <tbody>
                    <?php
                    require 'getDataCart.php';
                    // $total = 0;
                    // if (isset($_SESSION['cart'])){
                    //     $id_product = array_column($_SESSION['cart'], "id_product");

                    //     $result=$db->getData();
                    //     while($row = mysqli_fetch_assoc($result)) {
                    //         foreach($id_product as $id) {
                    //             if($row['id_product']==$id) {
                    //                 $quantity = 1; // Default quantity
                    //                 foreach ($_SESSION['cart'] as $item){
                    //                     if ($item['id_product'] == $id){
                    //                         if (!isset($item['quantity'])) {
                    //                             $item['quantity'] = 1;
                    //                         }
                    //                         $quantity = $item['quantity'];
                    //                         break;
                    //                     }
                    //                 }
                    //                 echo '<tr>';
                    //                 echo cartElement($row['image'], $row['name'], $row['price'], $row['id_product'], $quantity);
                    //                 echo '</tr>';
                    //                 $total = $total + (int)$row['price'] * $quantity;
                    //             }
                    //         }
                    //     }
                    // }
                    ?>
            </tbody>
        </table>
        <!-- <button class="btn-Proceed-checkout">Proceed to Checkout</button> -->
    </section>

    <section id="cart-add">
    <div id="subtotal" class="section-p1">
        <h2>Cart Total</h2>
        <table>
            <tr>
                <td>Shipping</td>
                <td>Free</td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong><?php echo rupiah($total);?></strong></td>
            </tr>
        </table>
        <button type="submit" class="normal" onclick="window.location.href='payment.php'">Proceed to Checkout</button>
    </div>
</section>


    <!-- footer -->
    <div class="footer">
        <div class="footer-left">
            <div class="footer-logo">
                <img src="../Images/logo.png" alt="Logo">
            </div>
            <div>
                <p>Email: example@example.com</p>
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

<!-- custom js file link -->
<script src="js/script.js"></script>
</body>
</html>