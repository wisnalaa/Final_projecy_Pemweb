<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    uploadData();
}

function uploadData() {
    // Mendapatkan nilai dari form
    $name = $_POST['name'];
    $credit = $_POST['credit'];
    $cvc = $_POST['cvc'];
    $payment_date = $_POST['tanggal']; // Mengambil nilai tanggal dari input

    // Koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dbmebel"; // sesuaikan dengan nama database Anda

    $conn = mysqli_connect($servername, $username, $password, $database);

    // Memeriksa koneksi
    if (!$conn) {
        die('Koneksi gagal: ' . mysqli_connect_error());
    }

    // Menghindari SQL injection dengan menggunakan mysqli_real_escape_string
    $name = mysqli_real_escape_string($conn, $name);
    $credit = mysqli_real_escape_string($conn, $credit);
    $cvc = mysqli_real_escape_string($conn, $cvc);
    $payment_date = mysqli_real_escape_string($conn, $payment_date);

    // Query untuk menyimpan data ke dalam tabel payment
    $sql = "INSERT INTO payment (name, credit, cvc, payment_date) VALUES ('$name', '$credit', '$cvc', '$payment_date')";

    // Eksekusi query
    $result = mysqli_query($conn, $sql);

    // Menangani hasil dari eksekusi query
    if ($result) {
        echo "<script>alert('Pembayaran telah direkam!')</script>";
        echo "<script>window.location = 'invoice.php'</script>"; // Redirect ke halaman payment.php jika berhasil
    } else {
        echo "<script>alert('Pembayaran gagal direkam!')</script>";
        echo "<script>window.location = 'payment.php'</script>"; // Redirect ke halaman payment.php jika gagal
    }

    // Menutup koneksi database
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../css/payment.css">

    <!-- Link untuk menggunakan ikon -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- Link untuk menggunakan font-awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Link untuk menggunakan font Google -->
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Bagian header -->
    <section id="header">
        <div class="header header">
            <div class="header-1">
                <a href="Homepage.html" class="logo">
                    <img src="../Images/logo.png" alt="Logo" width="100">
                </a>

                <!-- Form pencarian -->
                <form action="" class="search-form">
                    <input type="search" name="" placeholder="search here..." id="search-box">
                    <label for="search-box" class="fas fa-search"></label>
                </form>

                <!-- Ikons -->
                <div class="icons">
                    <div id="search-btn" class="fas fa-search"></div>
                    <a href="Wishlist.php" class="fa-regular fa-heart"></a>
                    <a href="cart.php" class="fa-solid fa-cart-plus"></a>
                    <a href="../../Account.php" class="fa-regular fa-user"></a>
                </div>
            </div>

            <!-- Navigasi -->
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

    <!-- Bagian form pembayaran -->
    <section class="contact">
        <div class="content">
            <h2>Payment</h2>
        </div>
        <div class="container">
            <div class="contactForm">
                <form method="POST" action="">
                    <h2>Payment Details</h2>
                    <div class="inputBox">
                        <input type="text" name="name" id="name" required="required">
                        <span>Name</span>
                    </div>
                    <div class="inputBox">
                        <input type="text" name="credit" id="credit" required="required">
                        <span>Credit Card No</span>
                    </div>
                    <div class="inputBox">
                        <input type="number" name="cvc" id="cvc" required="required" max="999" min="0">
                        <span>CVC</span>
                    </div>
                    <div class="inputBox">
                        <label for="tanggal">Pilih Tanggal:</label><br>
                        <input type="date" id="tanggal" name="tanggal"><br><br>
                    </div>
                    <!-- <div class="inputBox">
                        <input type="submit" name="send" value="Send" href="invoice.php">
                    </div> -->
                    <div class="inputBox">
                        <form action="invoice.php" method="post">
                            <input type="submit" name="send" value="Send">
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Bagian footer -->
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
</body>
</html>
