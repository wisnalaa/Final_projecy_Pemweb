<?php
require 'conf.php';
require_once 'component.php';

// SQL query to fetch all products
$sql = "SELECT * FROM cart";
$hasil = $conn->query($sql);

// function rupiah($price){
//     return "IDR " . number_format($price, 2, ',', '.');
// }
$total = 0;

if (!$hasil) {
    die("Error: " . $conn->error);
} elseif ($hasil->num_rows > 0) {
    while ($row = $hasil->fetch_assoc()) {
        $idproduct = $row['id_product'];
        $name = $row['name'];
        $image = $row['image'];
        $price = $row['price'];
        // $productqty = 1;

        // Get the quantity from the cart table
        $query = "SELECT quantity FROM cart WHERE id_product = '$idproduct'";
        $result = mysqli_query($db->conn, $query);
        if ($result) {
            $row_qty = mysqli_fetch_assoc($result);
            $productqty = $row_qty['quantity'];
        } else {
            $productqty = 1; // default quantity if not found in cart
        }

        // $total += $price;

        $total = 0;
        $query = "SELECT SUM(price * quantity) as total FROM cart";
        $result = mysqli_query($db->conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $total = $row['total'];
        }

        $_SESSION['total_price'] = $total;

        echo '
        <form action="cart.php?action=remove&id='. $idproduct .'" method="post">
        <tr class="cart-item">
            <td>
                
                <form action="cart.php" method="post" class="cart-items">
                    <button type="submit" class="remove" name="Remove">
                        <i class="circle-button"><span class="button-text">X</span></i>
                        
                    </button>
                        <input type = "hidden" name = "id_product" value = '. $idproduct .'>
                </form>
                </form>
            </td>
            <td><img src="'. $image .'" alt=""></td>
            <td>
                <div class="product-details">
                    <h4>'. $name .'</h4>
                    <p>Tosca</p>
                </div>
            </td>
            <td>'. rupiah($price) .'</td>
            <td>
                <form action="cart.php?action=update&id='. $idproduct .'" method="post" class="cart-items">
                    <input type="number" value="'. $productqty .'" name="quantity" min="1" max="10">
                    <button type="submit" name="update" class="btn-update">Update</button>
                </form>
            </td>
        </tr>
        </form>
        ';
    }
} else {
    echo "No products found.";
}
?>