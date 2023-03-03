<?php
$page_title = 'Shop';   
include('../includes/tall-header.php');

if (!isset($_SESSION['user_id']))
{
    header("Location: ../User-Accounts/login.php");
}

require('../includes/connect_db.php');


echo'<div class = "standard-box"><div class = "centre-content">
<br>
<div class = "divider"></div>
<h1 class = "standard-box-title">Shop</h1>
<div class = "divider"></div>
<br>
<form action = "../Preorder/shop.php" method = "POST">
<input type = "submit" value = "Drinks" class = "submit-button" name = "category">
<input type = "submit" value = "Food" class = "submit-button" name = "category">
</form>
';
if (!isset($_POST['category']))
{
    $category = "Drinks";
}
else
{
    $category = $_POST['category'];
}
$q = "SELECT * FROM tbl_shop
WHERE category = '$category'";
$r = mysqli_query($dbc,$q);
if(mysqli_num_rows($r) > 0)
{
    while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
    {
        echo '<p class = "standard-box-text" style = "margin-left:250px;margin-right:250px;"><strong>'.$row['item_name'].
        '<br><img src='.$row['item_img'].
        '></strong><br>'.$row['item_desc'].
        '<br>£'.$row['item_price'].
        '<br><button class = "submit-button"><a href="added.php?id='.$row['item_id'].
        '" class = "standard-box-text" style = "text-decoration:none;">Add to Cart</a></button></p>
        <div class = "divider"></div>';
    }
    mysqli_close($dbc);
}
else
{
    echo'<p>There are currently no items in this shop</p>';
}
echo'<br><button class = "submit-button"><a href="cart.php" class = "standard-box-text" 
style = "text-decoration:none">View Cart</a></button><br><br>';
echo'</div></div>';
include('../includes/footer.html');
?>