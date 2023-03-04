<!DOCTYPE html>
<?php
$page_title = 'Register';
include('../includes/header.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('../includes/connect_db.php');
    require("../includes/validation-functions.php");
    $errors = array();

    if ($error = check_presence("first name", $_POST["first_name"])) {
        $errors[] = $error;
    }

    if ($error = check_length("first name", $_POST["first_name"], 2, 30)) {
        $errors[] = $error;
    }

    if ($error = check_contains_integer("first name", $_POST["first_name"])) {
        $errors[] = $error;
    }


    if ($error = check_presence("last name", $_POST["last_name"])) {
        $errors[] = $error;
    }

    if ($error = check_length("last name", $_POST["last_name"], 2, 30)) {
        $errors[] = $error;
    }

    if ($error = check_contains_integer("last name", $_POST["last_name"])) {
        $errors[] = $error;
    }


    if ($error = check_presence("email", $_POST["email"])) {
        $errors[] = $error;
    }

    if ($error = check_length("email", $_POST["email"], 5, 48)) {
        $errors[] = $error;
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email';
    }


    if ($error = check_presence("password", $_POST["password"])) {
        $errors[] = $error;
    }

    if ($error = check_length("password", $_POST["password"], 4, 255)) {
        $errors[] = $error;
    }


    if ($error = check_presence("password confirmation", $_POST["confirm_password"])) {
        $errors[] = $error;
    }

    if ($error = check_length("password confirmation", $_POST["confirm_password"], 4, 255)) {
        $errors[] = $error;
    }


    if ($_POST['password'] != $_POST['confirm_password']) {
        $errors[] = 'Passwords do not match';
    }


    $email_registered_query = "SELECT user_id FROM tbl_customers WHERE email = '" . $_POST['email'] . "'";
    $email_registered = mysqli_query($dbc, $email_registered_query);
    if (mysqli_num_rows($email_registered) != 0) {
        $errors[] = "Email address already registered
        <a href='login.php'>Login?</a>";
    }

    if (empty($errors)) {
        $account_creation_query = "INSERT INTO tbl_customers
        (first_name, last_name, email, pass, reg_date)
        VALUES ('" . $_POST['first_name'] . "','" .
            $_POST['last_name'] . "','" .
            $_POST['email'] . "',SHA2('" .
            $_POST['password'] .
            "', 256), NOW())";

        $account_creation = mysqli_query($dbc, $account_creation_query);
    }
    mysqli_close($dbc);
}
?>

<div class="standard-box">
    <div>
        <br>
        <div class="divider"></div>
        <h1 class="standard-box-title">Register</h1>
        <form class="centre-content" action="../User-Accounts/register.php" method="POST">
            <div class="divider"></div>
            <p class="standard-box-text">
                First Name <br> <input type="text" name="first_name" value="<?php if (isset($_POST['first_name']))
                                                                                echo $_POST['first_name'] ?>">
            </p>
            <p class=standard-box-text>
                Last Name <br> <input type="text" name="last_name" value="<?php if (isset($_POST['last_name']))
                                                                                echo $_POST['last_name'] ?>">
            </p>
            <br>
            <p class="standard-box-text">
                Email <br> <input type="text" name="email" value="<?php if (isset($_POST['email']))
                                                                        echo $_POST['email'] ?>">
            </p>
            <br>
            <p class="standard-box-text">
                Password <br> <input type="password" name="password" value="<?php if (isset($_POST['password']))
                                                                                echo $_POST['password'] ?>">
            </p>
            <p class="standard-box-text">
                Confirm Password <br> <input type="password" name="confirm_password" value="<?php if (isset($_POST['confirm_password']))
                                                                                                echo $_POST['confirm_password'] ?>">
            </p>
            <p>
                <input class="submit-button" type="submit" value="Register">
            </p>
            <div class="divider"></div>
        </form>
    </div>
</div>
<?php include("../includes/footer.html"); ?>