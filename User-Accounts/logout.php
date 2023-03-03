<?php
$page_title='Logout';

include("../includes/header.php");

if(!isset($_SESSION['user_id']))
{
    echo'
    <div class = "standard-box">
    <div>
    <div class = "centre-content">
    <p class = "standard-box-text">You are not logged in</p>
    </div>
    </div>
    </div>';
}
else
{
    $_SESSION=array();
    session_destroy();

    echo'
    <div class = "standard-box">
    <div>
    <div class = "centre-content">
    <p class = "standard-box-text">You are now logged out</p>
    </div>
    </div>
    </div>';

}

include("../includes/footer.html");
?>