<?php
//adds page title and includes header
$page_title = 'Shop';
include('../includes/header.php');

//redirects user to login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//connects to the database
require('../includes/connect_db.php');

?>
<div class="standard-box">
    <div class="centre-content">
        <br>
        <!-- Adds a heading to the page -->
        <div class="divider"></div>
        <h1>Shop</h1>
        <div class="divider"></div>
        <br>
        <!-- Adds item category filtering -->
        <form action="../Preorder/shop.php" method="POST">
            <input type="submit" value="Drinks" class="submit-button" name="category">
            <input type="submit" value="Food" class="submit-button" name="category">
        </form>

        <?php
        //filters items based on users input in above form
        if (!isset($_POST['category'])) {
            $category = "Drinks";
        } else {
            $category = $_POST['category'];
        }

        //selects items from table
        $q = "SELECT * FROM tbl_shop
        WHERE category = '$category'";

        //runs query
        $r = mysqli_query($dbc, $q);

        //checks that there are items in the table
        if (mysqli_num_rows($r) > 0) :
            //loops through each item
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) :
        ?>
                <!-- Outputs each item -->
                <p class="standard-box-text" style="margin-left:250px;margin-right:250px;">
                    <strong>
                        <?php echo $row['item_name'] ?><br>
                        <img src='<?php echo $row['item_img'] ?>'>
                    </strong><br>
                    <?php echo $row['item_desc'] ?><br>
                    <?php echo "£" . $row['item_price'] ?><br>
                    <button class="submit-button">
                        <a href="added.php?id=<?php echo $row['item_id'] ?>" class="standard-box-text" 
                        style="text-decoration:none;">Add to Cart</a>
                    </button>
                </p>
                <div class="divider"></div>
            <?php
            endwhile;
            //closes database connection
            mysqli_close($dbc);
        else :
            //outputs appropriate message
            ?>
            <p>There are currently no items in this category</p>
        <?php
        endif;
        ?>

        <!-- Adds view cart button -->
        <br><button class="submit-button">
            <a href="cart.php" class="standard-box-text" style="text-decoration:none">View Cart</a>
        </button><br><br>
    </div>
</div>

<!-- includes footer -->
<?php
include('../includes/footer.html');
?>