<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $page_title = "Booking Confirmation";
    include('../includes/header.php');
    require ('../includes/connect_db.php');
    $errors = array();

    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['first_name'].' '.$_SESSION['last_name'];

    if ( empty($_POST['time']))
    {
        $errors[] = 'Select a Time';
    }
    else if ( empty($_POST['date']))
    {
        $errors[] = 'Select a Date';
    }
    else if (date("d-m-Y H:i",strtotime($_POST['date'].' '.$_POST['time'])) < date("d-m-Y H:i"))
    {
        $errors[] = "Invalid Booking Date/Time"; 
    } 
    else
    {
        $t = mysqli_real_escape_string( $dbc, trim($_POST['time']));
        $d = mysqli_real_escape_string( $dbc, trim($_POST['date']));
    }

    if ( empty($_POST['people']))
    {
        $errors[] = 'Input the Number of People';
    }
    else if ($_POST['people'] > 4 or $_POST['people'] < 0)
    {
        $errors[] = 'Invalid number of people';
    }
    else
    {
        $p = mysqli_real_escape_string( $dbc, trim($_POST['people']));
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
    
    if (empty($errors))
    {
        $q = "INSERT INTO tbl_bookings(user_id,name,time,date,people,location)
        VALUES ('$user_id','$name','$t','$d','$p','$l')";
        $r = mysqli_query($dbc,$q);
        $booking_id = mysqli_insert_id($dbc);
        echo'<div class = "standard-box"><div class = "centre-content">
        <h1 class = "standard-box-text">Thanks for your booking. Your booking number is #'.$booking_id.'</h1>
        </div></div>';    
    }
    else
    {
        echo "<div class = 'standard-box'><div class = 'centre-content'><p class = 'standard-box-text' id = 'err_msg'>Oops! There was a 
        problem <br>";
        foreach ($errors as $msg)
        {
            echo " - $msg<br>";
        }
        echo "Please <a href='booking.php' >Try Again</a></p></div></div>";
        }
    require('../includes/footer.html');
}
?>