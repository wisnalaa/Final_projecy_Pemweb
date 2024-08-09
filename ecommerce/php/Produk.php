<?php
    // Include database connection
    require 'conf.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Overpass&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/Produk.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <section id="header">
        <div class="header header">
            <div class="header-1">
                <a href="../Homepage.html" class="logo">
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
                    <a href="../Login.html" class="fa-regular fa-user"></a>
                </div>
            </div>
            <div class="header-2">
                <nav class="navbar">
                    <a href="../Homepage.html">HOME</a> 
                    <a href="Produk.php">SHOP</a>  
                    <a href="../AboutUs.html">ABOUT US</a>
                    <a href="contact.php">CONTACT</a> 
                </nav>
            </div>
        </div>

        <nav class="bottom-navbar">
            <a href="../Homepage.html" class="fas fa-home"></a>
            <a href="#" class="fas fa-bag-shopping"></a>
            <a href="#" class="fas fa-chair"></a>
            <a href="../contact.html" class="fas fa-comments"></a>
        </nav>
    </section>

    <div class="container">
        <aside class="sidebar"> 
            <h3>Product Categories</h3>
            <ul class="category-list">
                <li class="category-item active">All</li>       <!--active menandakan kategori yg dipilih-->
                <li class="category-item">Furnitures</li>
                <li class="category-item">Chairs</li>
                <li class="category-item">Sofas</li>
                <li class="category-item">Lighting</li>
            </ul>
            <h3>Home decor</h3>
            <ul class="category-list">
                <li class="category-item">Living Room</li>
                <li class="category-item">Dining Room</li>
                <li class="category-item">Entry Way</li>
                <li class="category-item">Outdoor</li>
            </ul>
        </aside>
        <main class="product-list">    
            <?php require 'getDataProduk.php'; ?>
        </main>
    </div>

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
         <!--Test Footer di tengah-->
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
              <!--Footer Logo pembayaran-->
        <div class="footer-right">
            <div class="payment-methods">
                <h4>Payments Method</h4>
                <img src="../Images/pay.png" alt="pay">
            </div>
        </div>
    </div>

</body>
</html>