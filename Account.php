<?php
session_start();
require 'dbconnect.php';
require "Login_function.php";

if (!isset($_SESSION['log'])) {
    header('location: Login.php');
    exit;
}

// Fetch user data from database
$user_id = $_SESSION['iduser'];
$query = "SELECT * FROM login WHERE iduser = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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
    <link rel="stylesheet" href="css/Account.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<!--CSS dropdown -->
<style>
    .icons {
        display: flex;
        align-items: center;
    }
    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        min-width: 160px;
    }
    .dropdown-menu a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .dropdown-menu a:hover {
        background-color: #f1f1f1;
    }
    .dropdown:hover .dropdown-menu {
        display: block;
    }
    .fa-search, .fa-heart, .fa-cart-plus, .fa-user {
        margin: 0 10px;
        cursor: pointer;
    }

    
</style>
</head>
<body>

    <section id="header">
        <div class="header header">
            <div class="header-1">
                <a href="Homepage.html" class="logo">
                    <img src="Images/logo.png" alt="Logo" width="100">
                </a>

                <form action="" class="search-form">
                    <input type="search" name="" placeholder="search here..." id="search-box">
                    <label for="search-box" class="fas fa-search"></label>
                </form>

                <div class="icons">
                    <div id="search-btn" class="fas fa-search"></div>
                    <a href="Wishlist.php" class="fa-regular fa-heart"></a>
                    <a href="cart.php" class="fa-solid fa-cart-plus"></a>
                    <div class="dropdown">
                        <i class="fa-regular fa-user" id="userDropdown" ></i>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="Account.php">Account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="Logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-2">
                <nav class="navbar">
                    <a href="ecommerce/Homepage.html">Home </a> 
                    <a href="php/shop.php">Shop</a>  
                    <a href="ecommerce/AboutUs.html">About Us </a>
                    <a href="ecommerce/contact.html">Contacts </a> 
                </nav>
            </div>
        </div>

        <nav class="bottom-navbar">
            <a href="ecommerce/Homepage.html" class="fas fa-home"></a>
            <a href="shop.html" class="fas fa-bag-shopping"></a>
            <a href="ecommerce/Produk.html" class="fas fa-chair"></a>
            <a href="ecommerce/contact.html" class="fas fa-comments"></a>
        </nav>
    </section>

    <form id="profileForm" action="save_user.php" method="post">
        <div class="container">
            <h4 class="title">Account settings</h4>
            <div class="card">
                <div class="row">
                    <div class="col-3">
                    </div>
                    <div class="col-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="general">
                                <div class="card-body">
                                    <img src="Images/profile.png" alt="profile" class="profile-img">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($user['password']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm password</label>
                                        <input type="password" class="form-control" name="confirm_password" value="<?php echo htmlspecialchars($user['password']); ?>">
                                    </div>
                                    <div class="form-group" style="display: flex; justify-content: flex-end; margin-top: 20px;">
                                        <button class="btn-update" type="submit" onclick="return confirm('Are you sure you want to update your data?');" style="background-color: blue; color: white; border: none; padding: 10px 20px; text-decoration: none; cursor: pointer; margin-right: 10px;">
                                            Update
                                        </button>
                                        <a href="delete_user.php" class="btn-delete" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');" style="background-color: red; color: white; border: none; padding: 10px 20px; text-decoration: none; cursor: pointer;">
                                            Delete Account
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="footer">
        <div class="footer-left">
            <div class="footer-logo">
                <img src="Images/logo.png" alt="Logo">
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
                <img src="Images/pay.png" alt="pay">
            </div>
        </div>
    </div>

    <!-- custom js file link -->
<script src="js/script.js"></script>
</body>
</html>
