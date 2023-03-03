<?php
$page_title='Cart Addition';
include('../includes/header.php');

if (!isset($_SESSION['user_id']))
{
    header("Location: ../User-Accounts/login.php");
}

if(isset($_GET['id']))
{
    $id = $_GET['id'];
}

require('../includes/connect_db.php');
$q = "SELECT * FROM tbl_shop WHERE item_id = $id";
$r = mysqli_query($dbc,$q);
if (mysqli_num_rows($r) == 1)
{
    $row = mysqli_fetch_array($r,MYSQLI_ASSOC);
    if(isset($_SESSION['cart']['id']))
    {
        $_SESSION['cart']['id']['quantity'];
        echo'<div class = "standard-box"><div class = "centre-content">
        <h1 class = "standard-box-title">Another '.$row["item_name"].' has been added to your cart</h1>
        </div></div>';
    }
    else
    {
        $_SESSION['cart'][$id]=
        array('quantity' => 1, 'price' => $row['item_price']);
        echo'<div class = "standard-box"><div class = "centre-content">
        <h1 class = "standard-box-title">A '.$row["item_name"].' has been added to your cart</h1>';
    }
    echo'<button class = "submit-button"><a href = "../Preorder/shop.php" class = "standard-box-text" style = "text-decoration:none">
    Back to Shop</a></button><br><br><br>
    <button class = "submit-button"><a href = "../Preorder/cart.php" class = "standard-box-text" style = "text-decoration:none">
    Cart</a></button>
    </div></div>';
}

mysqli_close($dbc);
include('../includes/footer.html')
?>