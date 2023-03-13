<?php

$page_title = 'Home';
include('../BeanandBrew/includes/header.php');
if (!isset($_SESSION['user_id']))
{

    echo'
    <div class = "standard-box">
    <div class = "centre-content">
    <br><div class = "divider"></div>
    <h1 class = "standard-box-title">Home</h1>
    <div class = "divider"></div><br>
    <a href="User-Accounts/login.php">Login</a><br>
    <a  href="User-Accounts/register.php">Register</a><br><br>
    <div class = "divider"></div>
    </div>
    </div>';
}
else
{
    echo "
    <div class = 'standard-box'>
    <div class = 'centre-content'>
    <br><div class = 'box'></div>
    <h1 class = 'standard-box-title'>Home</h1>
    <div class = 'box'></div>
    <p class = 'standard-box-text'>You are now logged in,
    {$_SESSION['first_name']} {$_SESSION['last_name']} 
    
    <a  href = '/BeanandBrew/User-Accounts/logout.php'>Logout?</a>
    </p>
    ";


    echo'<p class = "standard-box-text">
    <a href="../BeanandBrew/rate-my-cake/reviews.php" >Posts</a><br>
    <a href="../BeanandBrew/Booking/booking.php" >Book a Space</a>
    <a href="../BeanandBrew/Preorder/shop.php" >Pre-Order</a>
    <div class = "divider"></div>
    </div>
    </div>';
}
include('../BeanandBrew/includes/footer.html')
?>