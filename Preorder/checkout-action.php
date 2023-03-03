<?php
$page_title = "Order Confirmation";
include("../includes/header.php");

require("../includes/connect_db.php");

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['cart'])) {
    $total = $_POST['total'];

    $q="SELECT * FROM tbl_shop WHERE item_id IN (";
    foreach ($_SESSION['cart'] as $id => $value)
    {
        $q .= $id.',';
    }
    $q = substr($q,0,-1) .') ORDER BY item_id ASC';
    $r1 = mysqli_query($dbc,$q);

    $errors = array();
    if ( empty($_POST['time']))
    {
        $errors[] = 'Select a Time';
    }
    else if ( empty($_POST['date']))
    {
        $errors[] = 'Select a Date';
    }
    else
    {
        $t = mysqli_real_escape_string( $dbc, trim($_POST['time']));
        $d = mysqli_real_escape_string( $dbc, trim($_POST['date']));
    }

    if ( empty($_POST['location']))
    {
        $errors[] = 'Input your Desired Location';
    }
    else if ($_POST['location'] != "Leeds" and $_POST['location'] != "Knaresborough Castle" and $_POST['location'] != "Harrogate")
    {
        $errors[] = 'Invalid Location';
    }
    else
    {
        $l = mysqli_real_escape_string( $dbc, trim($_POST['location']));
    }
    
    if(empty($errors))
    {
        while($row = mysqli_fetch_array($r1,MYSQLI_ASSOC))
        {
            $q= "INSERT INTO tbl_orders(user_id,total,order_date,collection_date,collection_time) VALUES('"
            .$_SESSION['user_id']."','".$_POST['total']."',NOW(),'".$_POST['date']."','".$_POST['time']."')";
            $r = mysqli_query($dbc,$q);
        
            $order_id = mysqli_insert_id($dbc);

            $collection = $_POST['date'].", ".$_POST['time'];
            $query = "INSERT INTO tbl_order_contents(order_id,item_id,user_id,quantity,price,location)
            VALUES ('".$order_id."','".
            $row['item_id']."','".
            $_SESSION['user_id']."','".
            $_SESSION['cart'][$row['item_id']]['quantity']."','".
            $_SESSION['cart'][$row['item_id']]['price']."','".
            $l."')";

            $result = mysqli_query($dbc,$query);
        }

        $q = "UPDATE tbl_orders
        SET hamper = ";

        if(!isset($_POST['hamper']))
        {
            $q .= "'0'";
        }
        elseif ($_POST['hamper'] == 'True')
        {
            $q .= "'1'";
            $q2 = "INSERT INTO tbl_hamper_messages(order_id,name,message)
            VALUES ('".$order_id."','".$_POST['name']."','".$_POST['text']."');";
            $r2 = mysqli_query($dbc,$q2);
        }

        $q .= " WHERE order_id = ".$order_id.";";
        $r = mysqli_query($dbc,$q);

        mysqli_close($dbc);

        echo'<div class = "standard-box"><div class = "centre-content">
        <h1 class = "standard-box-text">Thanks for your order. Your order number is #'.$order_id.'</h1>
        </div></div>';
    }
    else
    {
        echo "<div class = 'standard-box'><div class = 'centre-content'><p class = 'standard-box-text' id = 'err_msg'>Oops! 
        There was a problem <br>";
        foreach ($errors as $msg)
        {
            echo " - $msg<br>";
        }
        echo "Please <a href='cart.php' >Try Again</a></p></div></div>";
    }
    $_SESSION['cart'] = NULL;
}
else
{
    echo"<div class = 'standard-box'><div class = 'centre-content'>
    <h1 class = 'standard-box-title'>An error has occured<br>
    Please <a href='cart.php' style = 'text-decoration:none;'>Try Again</a></h1></div></div>";
}
include("../includes/footer.html");
