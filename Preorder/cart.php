<?php
//adds title and header to page
$page_title = 'Cart';
include('../includes/header.php');

//redirects user to login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//checks if data is coming into the file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //loops through each item in the cart and updates it based on user input further in code
    foreach ($_POST['qty'] as $item_id => $item_qty) {
        //assigns values to variables
        $id = (int) $item_id;
        $qty = (int)$item_qty;

        //checks if the quantity is 0 and if so, removes the item from the cart
        //otherwise it will update the quantity
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
        //defines necessary variables
        $total = 0;

        //checks that the cart is not empty
        if (!empty($_SESSION['cart'])) :
            //connects to the database
            require('../includes/connect_db.php');

            //selects details of all items in the cart
            $q = "SELECT * FROM tbl_shop WHERE item_id IN (";
            foreach ($_SESSION['cart'] as $id => $value) {
                $q .= $id . ",";
            }
            $q = substr($q, 0, -1) . ") ORDER BY item_id ASC";

            //runs query
            $r = mysqli_query($dbc, $q);
        ?>

            <form class="centre-content" action="../Preorder/cart.php" method="POST"><br>
                <h1>Cart</h1>
                <!-- Creates cart table -->
                <table style="margin-left:auto;margin-right:auto;">
                    <!-- Creates table header -->
                    <tr>
                        <th colspan="5" class="standard-box-text">Items in your cart</th>
                    </tr>


                    <?php
                    //loops through each item
                    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) :
                        //creates a subtotal for each item and adds it to the total
                        $subtotal = $_SESSION['cart'][$row['item_id']]['quantity']
                            * $_SESSION['cart'][$row['item_id']]['price'];
                        $total += $subtotal;
                    ?>
                        <!-- Outputs details of each item as a row in the table -->
                        <tr>
                            <td class='standard-box-text'><?php echo $row['item_id'] ?></td>
                            <td class='standard-box-text'><?php echo $row['item_name'] ?></td>
                            <td class='standard-box-text'>
                                <!-- Creates input box allowing user to change quantity -->
                                <input type="text" size="3" name="qty[<?php echo $row['item_id'] ?>]" 
                                value="<?php echo $_SESSION['cart'][$row['item_id']]['quantity'] ?>">
                            </td>
                            <td class='standard-box-text'><?php echo $row['item_price'] ?> = </td>
                            <td class='standard-box-text'><?php echo number_format($subtotal, 2) ?></td>
                        </tr>
                    <?php
                    endwhile;
                    ?>
                    <!-- Outputs total -->
                    <tr>
                        <td colspan="5" class="standard-box-text">Total = <?php echo number_format($total, 2) ?></td>
                    </tr>
                </table>
                <br>
                <!-- Creates submit button -->
                <input type="submit" value="Update My Cart" class="submit-button"><br><br>
            </form>

        <?php
            //closes database connection
            mysqli_close($dbc);
        else :
        ?>
            <!-- Outputs an appropriate message -->
            <h2>Your cart is currently empty</h2>
        <?php
        endif;
        ?>

        <!-- creates back to shop button -->
        <button class="submit-button">
            <a href="../Preorder/shop.php" class="standard-box-text" style="text-decoration:none">
                Back to Shop</a>
        </button><br><br>

        <!-- Creates form for hampers -->
        <form action="checkout.php" method="POST">
            <label for="hamper">Create Hamper?</label><br>
            <input type="checkbox" id="hamper" name="hamper" value="True"><br>
            <p>Please note that drinks cannot be included in a hamper. Any drinks will be treated as a seperate order.</p>

            <!-- Creates hidden input for total to be sent to file and submit button -->
            <input type="hidden" name="total" value="<?php echo $total ?>">
            <input type="submit" value="Checkout" class="submit-button"><br><br>
        </form>
    </div>
</div>

<!-- Adds footer to page -->
<?php include('../includes/footer.html'); ?>