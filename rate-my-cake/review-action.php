<?php 
$page_title = "Review Error";
include("../includes/header.php");
require('../User-Accounts/login_tools.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    require ('../includes/connect_db.php');
    $errors = array();

    $user_id = $_SESSION['user_id'];

    if ( empty($_POST['Location']))
    {
        $errors[] = 'Select a Location';
    }
    else if ($_POST['Location'] != "Leeds" and $_POST['Location'] != "Knaresborough Castle" and $_POST['Location'] != "Harrogate")
    {
        $errors[] = 'Invalid Location';
    }
    else
    {
        $l = mysqli_real_escape_string( $dbc, trim($_POST['Location']));
    }

    if ( empty($_POST['Cake']))
    {
        $errors[] = 'Select a Cake';
    }
    else
    {
        $q = "SELECT * FROM tbl_shop
        WHERE category = 'Food'";
        $r = mysqli_query($dbc,$q);
        $cake_check = False;

        if(mysqli_num_rows($r) > 0)
        {
            while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
            {
                if ($row['item_name'] = $_POST['Cake'])
                {
                    $cake_check = True;
                }
            }
        }
        if ($cake_check)
        {
            $c = mysqli_real_escape_string( $dbc, trim($_POST['Cake']));
        }
        else
        {
            $errors[] = 'Invalid Cake';
        }
    }

    if ( empty($_POST['text']))
    {
        $errors[] = 'Write a Review';
    }
    else
    {
        $t = mysqli_real_escape_string( $dbc, trim($_POST['text']));
    }

    if ( empty($_POST['rating']))
    {
        $errors[] = 'Select a Rating';
    }
    else if ($_POST['rating'] > 5 or $_POST['rating'] < 1)
    {
        $errors[] = 'Invalid Rating';
    }
    else
    {
        $r = mysqli_real_escape_string( $dbc, trim($_POST['rating']));
    }
    
    if (empty($errors))
    {
        $q = "INSERT INTO tbl_reviews(user_id,location,cake,text,rating)
        VALUES ('$user_id','$l','$c','$t','$r')";
        $r = mysqli_query($dbc,$q);
        header("Location: /reviews.php");
    }
    else
    {
        echo "<div class = 'standard-box'><div class = 'centre-content'><p class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem <br>";
        foreach ($errors as $msg)
        {
            echo " - $msg<br>";
        }
        echo "Please <a href='reviews.php' >Try Again</a></p></div></div>";
        }
    require('../includes/footer.html');
}
?>