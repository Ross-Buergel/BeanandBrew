<?php
session_start();

if(!isset($__SESSION['user_id']))
{
    header("Location: ../User-Accounts/login.php");
}
$page_title = 'Checkout';
include('../includes/header.php');

if(isset($_GET['total'])&&($_GET['total']>0)&&(!empty($_SESSION['cart'])))
{
    require('../includes/connect_db.php');
    $q= "INSERT INTO tbl_orders(user_id,total,order_date) VALUES("
    .$_SESSION['user_id'].",".$_GET['total'].",NOW())";
    $r = mysqli_query($dbc,$q);

    $order_id = mysqli_insert_id($dbc);

    while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
    {
        $query = "INSERT INTO tbl_order_contents(order_id,item_id,quantity,price)
        VALUES ($order_id,".$row['item_id'].",".
        $_SESSION['cart'][$row['item_id']]['quantity'].",".
        $_SESSION['cart'][$row['item_id']]['price'].")";

        $result = mysqli_query($dbc,$query);
    }

    mysqli_close($dbc);
    echo"<p>Thanks for your order. Your order number is #".$order_id."</p>";
    $_SESSION['cart'] = NULL;
}
else
{
    echo'<p>There are no items in your cart</p>';
}
include('../includes/footer.html')
?>