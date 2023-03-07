<?php
$page_title = 'Cart';
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['qty'] as $item_id => $item_qty) {
        $id = (int)$item_id;
        $qty = (int)$item_qty;

        if ($qty == 0) {
            unset($_SESSION['cart'][$id]);
        } elseif ($qty > 0) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
}

?>
<div class="standard-box">
    <div class="centre-content">
        <?php
        $total = 0;

        if (!empty($_SESSION['cart'])) :
            require('../includes/connect_db.php');
            $q = "SELECT * FROM tbl_shop WHERE item_id IN (";
            foreach ($_SESSION['cart'] as $id => $value) {
                $q .= $id . ',';
            }
            $q = substr($q, 0, -1) . ") ORDER BY item_id ASC";
            $r = mysqli_query($dbc, $q);
        ?>

            <form class="centre-content" action="../Preorder/cart.php" method="POST"><br>
                <h1 class="standard-box-title">Cart</h1>
                <table style="margin-left:auto;margin-right:auto;">
                    <tr>
                        <th colspan="5" class="standard-box-text">Items in your cart</th>
                    </tr>
                    <tr>

                        <?php
                        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) :
                            $subtotal = $_SESSION['cart'][$row['item_id']]['quantity']
                                * $_SESSION['cart'][$row['item_id']]['price'];
                            $total += $subtotal;

                        ?>
                            <div class='centre-content'>
                    <tr>
                        <td class='standard-box-text'><?php echo $row['item_id'] ?></td>
                        <td class='standard-box-text'><?php echo $row['item_name'] ?></td>
                        <td class='standard-box-text'>
                            <input type="text" size="3" name="qty[<?php echo $row['item_id'] ?>]" 
                            value="<?php echo $_SESSION['cart'][$row['item_id']]['quantity'] ?>">
                        </td>
                        <td class='standard-box-text'><?php echo $row['item_price'] ?> = </td>
                        <td class='standard-box-text'><?php echo number_format($subtotal, 2) ?></td>
                    </tr>
                <?php
                        endwhile;
                ?>
                <tr>
                    <td colspan="5" class="standard-box-text">Total = <?php echo number_format($total, 2) ?></td>
                </tr>
                </table>
                <br>
                <input type="submit" value="Update My Cart" class="submit-button"><br><br>
            </form>

        <?php
            mysqli_close($dbc);
        else :
        ?>
            <h2 class="standard-box-title">Your cart is currently empty</h2>
        <?php
        endif;
        ?>

        <button class="submit-button">
            <a href="../Preorder/shop.php" class="standard-box-text" style="text-decoration:none">
                Back to Shop</a>
        </button><br><br>
        <form action="checkout.php" method="POST">
            <label for="hamper">Create Hamper?</label><br>
            <input type="checkbox" id="hamper" name="hamper" value="True"><br>
            <p>Please note that drinks cannot be included in a hamper. Any drinks will be treated as a seperate order.</p>

            <input type="hidden" name="total" value="<?php echo $total ?>">
            <input type="submit" value="Checkout" class="submit-button"><br><br>
        </form>
    </div>
</div>


<?php
include('../includes/footer.html');
?>