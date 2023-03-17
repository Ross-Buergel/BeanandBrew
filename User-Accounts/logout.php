<?php
//creates a session on the file
session_start();

//checks if account details are stored in the session
if(isset($_SESSION['user_id']))
{
    //clears and destroys the session
    $_SESSION=array();
    session_destroy();
}
//redirects the user to the home page
header("Location: ../index.php");
?>