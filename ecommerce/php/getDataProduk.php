<?php
require 'conf.php';

// SQL query to fetch all products
$sql = "SELECT * FROM product";
$hasil = $conn->query($sql);

function rupiah($price){
    return "IDR " . number_format($price, 2, ',', '.');
}

if (!$hasil) {
    die("Error: " . $conn->error);
} elseif ($hasil->num_rows > 0) {
    while ($row = $hasil->fetch_assoc()) {
        $idproduct = $row['id_product'];
        $name = $row['name'];
        $image = $row['image'];
        $price = $row['price'];

        echo '
        <div class="product-item">
            <a href="detailProduk.php?id_product=' . $idproduct . '">
                <img src="' . $image . '" alt="' . $name . '">
                <h3>' . $name . '</h3>
                <p>' . rupiah($price) . '</p>
            </a>
        </div>';
    }
} else {
    echo "No products found.";
}
?>