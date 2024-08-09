<?php
    function component($productname, $productprice, $productimg, $idproduct){
        $element = '
            <form action="Wishlist.php" method="post">
                <div class="befo-card">
                            <div class="befo-img">
                                <a href="#"><ion-icon name="heart"></ion-icon></a>
                                <img src="'. $productimg .'">
                            </div>
    
                            <div class="befo-content">
                                <p> '. $productname .' </p>
                                <h3>'. rupiah($productprice) .'</h3>
                            </div>
                            
                            <div class="befo-add">
                                <button type="submit" class="trash" name="Trash">
                                    <a href="#"><ion-icon name="trash-outline"></ion-icon></a>
                                </button>
                                <button type="submit" class="add" name="add_cart"> Add To Cart</button>
                                
                            </div>
                        <input type = "hidden" name = "id_product" value = '. $idproduct .'>
                        <input type = "hidden" name = "productname" value = '. $productname .'>
                        <input type = "hidden" name = "productprice" value = '. $productprice .'>
                        
                    </div>
            </form>
                
        ';
        echo $element;
    }

    function rupiah($price){
        $hasil_rupiah = "IDR " . number_format($price,2,',','.');
        return $hasil_rupiah;
    }
    
    function cartElement($productimg, $productname, $productprice, $idproduct,  $productqty){
        $element = '
             <tr class="cart-item">
            <td>
                <form action="cart.php" method="post" class="cart-items">
                    <button type="submit" class="remove" name="Remove">
                        <i class="circle-button"><span class="button-text">X</span></i>
                        
                    </button>
                        <input type = "hidden" name = "id_product" value = '. $idproduct .'>
                        <input type = "hidden" name = "productname" value = '. $productname .'>
                        <input type = "hidden" name = "productprice" value = '. $productprice .'>
                </form>
            </td>
            <td><img src="'. $productimg .'" alt=""></td>
            <td>
                <div class="product-details">
                    <h4>'. $productname .'</h4>
                    <p>Tosca</p>
                </div>
            </td>
            <td>'. rupiah($productprice) .'</td>
            <td>
                <form action="cart.php?action=update&id='. $idproduct .'" method="post" class="cart-items">
                    <input type="number" value="'. $productqty .'" name="quantity" min="1" max="10">
                    <button type="submit" name="update" class="btn-update">Update</button>
                </form>
            </td>
        </tr>
        ';
        echo $element;
    }    

    
?>
