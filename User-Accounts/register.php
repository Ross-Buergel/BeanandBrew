<!DOCTYPE html>
<?php
$page_title = 'Register';
include ('../includes/header.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    require ('../includes/connect_db.php');
    $errors = array();


    if ( empty($_POST['first_name']))
    {
        $errors[] = 'Enter your first name';
    }
    else
    {
        $fn = mysqli_real_escape_string( $dbc, trim($_POST['first_name']));
    }

    if ( empty($_POST['last_name']))
    {
        $errors[] = 'Enter your last name';
    }
    else
    {
        $ln = mysqli_real_escape_string( $dbc, trim($_POST['last_name']));
    }

    if ( empty($_POST['email']))
    {
        $errors[] = 'Enter your email';
    }
    else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $errors[] = 'Enter a valid email';
    }
    else
    {
        $e = mysqli_real_escape_string( $dbc, trim($_POST['email']));
    }

    if (!empty($_POST['password']))
    {
        if ($_POST['password'] != $_POST['confirm_password'])
        {
            $errors[] = 'Passwords do not match';
        }
        else
        {
            $p = mysqli_real_escape_string( $dbc, trim($_POST['password']));
        }
    }
    else
    {
        $errors[] = 'Enter your password';
    }
    
    if (empty($errors))
    {
        $q = "SELECT user_id FROM tbl_customers WHERE email = '$e'";
        $r = mysqli_query($dbc,$q);
        if (mysqli_num_rows($r) != 0)
        {
            $errors[] = "Email address already registered
            <a  href='login.php'>Login?</a>";
        }
    }

    if ( empty($errors))
    {
        $q = "INSERT INTO tbl_customers
        (first_name, last_name, email, pass, reg_date)
        VALUES ('$fn','$ln','$e',SHA2('$p', 256), NOW())";
        $r = mysqli_query ($dbc,$q);

        if ($r)
        {
            echo "<div class = 'standard-box'><div class = 'centre-content'><h1 class = 'standard-box-title'>Registered!</h1>
            <p class = 'standard-box-text'>You are now registered</p>
            <a href='login.php' >Login</a></div></div>";
        }

        mysqli_close($dbc);
        include ("../includes/footer.html");
        exit();
    }
    else
    {
        echo "
        <div class = 'error-standard-box'>
        <div>
        <h1 class = 'error-standard-box-text-red'>Error!</h1>
        <p id='err_msg'>The following error(s) occured:<br>";
        foreach ($errors as $msg)
        {
            echo " - $msg<br>";
        }
        echo "Please try again</p>
        </div>
        </div>";
        mysqli_close($dbc);
    }
}
?>

<div class = "standard-box">
    <div>
        <br>
        <div class = "divider"></div>
        <h1 class = "standard-box-title">Register</h1>
        <form class = "centre-content" action = "../User-Accounts/register.php" method = "POST">
            <div class = "divider"></div>
            <p class = "standard-box-text">
                First Name <br> <input type = "text" name = "first_name"
                value="<?php if (isset($_POST['first_name']))
                echo $_POST['first_name']?>">
            </p>
            <p class = standard-box-text>
                Last Name <br> <input type = "text" name = "last_name"
                value="<?php if (isset($_POST['last_name']))
                echo $_POST['last_name']?>">
            </p>
            <br>
            <p class = "standard-box-text">
            Email <br> <input type = "text" name = "email"
                value="<?php if (isset($_POST['email']))
                echo $_POST['email']?>">
            </p>
            <br>
            <p class = "standard-box-text">
                Password <br> <input type = "password" name = "password"
                value="<?php if (isset($_POST['password']))
                echo $_POST['password']?>">
            </p>
            <p class = "standard-box-text">
                Confirm Password <br> <input type = "password" name = "confirm_password"
                value="<?php if (isset($_POST['confirm_password']))
                echo $_POST['confirm_password']?>">
            </p>
            <p>
                <input class = "submit-button" type="submit" value="Register">
            </p>
            <div class = "divider"></div>
        </form>
    </div>
</div>
<?php include("../includes/footer.html");?>