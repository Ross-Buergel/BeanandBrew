<?php
//sets the page title and adds header to the page
$page_title = 'Cart Addition';
include('../includes/header.php');

//sends user to login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//gets id if one is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//connects to the database
require('../includes/connect_db.php');

//gets item details form the table
$q = "SELECT * FROM tbl_shop WHERE item_id = ".$id;

//runs query
$r = mysqli_query($dbc, $q);
?>

<div class="standard-box">
    <div class="centre-content">
        <?php
        //checks if the item was found
        if (mysqli_num_rows($r) == 1){
            //turns returned query into array
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

            //checks if the item is already in the cart and if so outputs an appropriate message
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity']++;
        ?>
                <h1>Another <?php $row["item_name"] ?> has been added to your cart</h1>

            <?php
            //adds item to cart and outputs appropriate message
            }else{
                $_SESSION['cart'][$id] = array('quantity' => 1, 'price' => $row['item_price']);
            ?>
                <h1>A <?php echo $row["item_name"] ?> has been added to your cart</h1>
            <?php
            }
            ?>
            <!-- Adds back to shop button -->
            <button class="submit-button">
                <a href="../Preorder/shop.php" class="standard-box-text" style="text-decoration:none">Back to Shop</a>
            </button><br><br><br>

            <!-- Adds add to cart button -->
            <button class="submit-button">
                <a href="../Preorder/cart.php" class="standard-box-text" style="text-decoration:none">Cart</a>
            </button>
        <?php
        }
        ?>
    </div>
</div>
<?php
//closes database connection and includes footer
mysqli_close($dbc);
include('../includes/footer.html')
?>