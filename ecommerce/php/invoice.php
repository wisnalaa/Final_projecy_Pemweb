<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$database = "dbmebel"; // Sesuaikan dengan nama database Anda

$conn = mysqli_connect($servername, $username, $password, $database);

// Memeriksa koneksi
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

// Ambil data dari tabel payment
$sql = "SELECT * FROM payment";
$result = mysqli_query($conn, $sql);

// Cek apakah ada data yang diambil
if (mysqli_num_rows($result) > 0) {
    // Ambil data pertama (jika hanya ada satu baris saja)
    $row = mysqli_fetch_assoc($result);
    
    // Simpan data ke dalam session
    $_SESSION['name'] = $row['name'];
    $_SESSION['credit'] = $row['credit'];
    $_SESSION['cvc'] = $row['cvc'];
    $_SESSION['tanggal'] = $row['payment_date'];
    if (isset($_SESSION['total_price'])) {
        $total_price = $_SESSION['total_price'];
        $hasil_rupiah = "IDR " . number_format($total_price,2,',','.');
    } else {
        $total_price = 0; // Nilai default jika total_price tidak ditemukan
    }
    // $_SESSION['total_price'] = $row['total_price'];
} else {
    echo "Tidak ada data ditemukan dalam tabel payment.";
}

if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

$sql_delete_cart = "DELETE FROM cart";
if ($conn->query($sql_delete_cart) === TRUE) {
    // echo "Cart has been cleared from the database.";
} else {
    echo "Error clearing cart from the database: " . $conn->error;
}

// Tutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="../css/invoice.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="left">
            <h2>Amount to pay</h2>
            <h1><?php echo htmlspecialchars($hasil_rupiah); ?></h1>
            <h2>Payment for</h2>

            <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            <p><strong>Credit Card:</strong> <?php echo htmlspecialchars($_SESSION['credit']); ?></p>
            <p><strong>CVC:</strong> <?php echo htmlspecialchars($_SESSION['cvc']); ?></p>
            <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($_SESSION['tanggal']); ?></p>

            <!-- Contoh tabel yang bisa digunakan untuk menampilkan detail pembayaran -->
            <!-- <table>
                <tr>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
                <tr>
                    <td>Sofas</td>
                    <td>1</td>
                    <td>IDR 550.00</td>
                </tr>
                <tr>
                    <td>Chair</td>
                    <td>1</td>
                    <td>IDR 1,250.00</td>
                </tr>
            </table> -->

            <div class="total"><?php echo htmlspecialchars($hasil_rupiah); ?></div>
        </div>

        <div class="right">
            <!-- Konten kanan di sini -->
            <img src="tick.png" alt="FurniturQyu">
            <h1>Payment Received!</h1>
            
            <!-- Tombol Print -->
            <button id="printButton" onclick="window.location.href='../Homepage.html'">OK</button>
        </div>
    </div>

    <script src="print.js"></script> <!-- Sertakan file JavaScript di sini -->
</body>
</html>
