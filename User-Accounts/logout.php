<?php
$page_title='Logout';

include("../includes/header.php");

if(isset($_SESSION['user_id']))
{
    $_SESSION=array();
    session_destroy();
}
header("Location: ../index.php");
?>