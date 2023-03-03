<?php
$page_title = 'Shop';   
include('../includes/header.php');

if (!isset($_SESSION['user_id']))
{
    header("Location: ../User-Accounts/login.php");
}

require('../includes/connect_db.php');

$q = "SELECT * FROM tbl_shop";
$r = mysqli_query($dbc,$q);
if(mysqli_num_rows($r) > 0)
{
    echo'<table><tr>';
    while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
    {
        echo '<td><strong>'.$row['item_name'].
        '</strong><br>'.$row['item_desc'].
        '<br><img src='.$row['item_img'].
        '><br>$'.$row['item_price'].
        '<br><a href="added.php?id='.$row['item_id'].
        '">Add To Cart</a></td>';
    }
    echo'</tr></table>';
    mysqli_close($dbc);
}
else
{
    echo'<p>There are currently no items in this shop</p>';
}

echo'<p><a href="cart.php">View Cart</a>|
<a href="forum.php">Forum</a>|
<a href="home.php">Home</a>
<a href="../User-Accounts/logout.php">Logout</a></p>';

include('../includes/footer.html');
?>