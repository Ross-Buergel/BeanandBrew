<?php
$page_title = 'Checkout';
include('../includes/header.php');
if (isset($_GET['id'])) 
{
    $id = $_GET['id'];
}

require('../includes/connect_db.php');
$q = "INSERT INTO tbl_lesson_bookings(lesson_id,user_id)
VALUES (".$id.",".$_SESSION['user_id'].")";
$r = mysqli_query($dbc,$q);
echo'<div class = "standard-box"><div class = "centre-content"><h1 class = "standard-box-title">
Booking Confirmed</h1></div></div>';
include('../includes/footer.html');
?>