<!DOCTYPE html>
<?php
//sets the page title and includes the header
$page_title = 'Register';
include('../includes/header.php');

//checks if data is coming into the file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //gets the functions for validation and connects to the database
    require('../includes/connect_db.php');
    require("../includes/validation-functions.php");

    //creates the array containing errors
    $errors = array();

    //checks if the first name has been entered, is not too long for the database and does not contain an integer
    if ($error = check_presence("first name", $_POST["first_name"])) {
        $errors[] = $error;
    }

    if ($error = check_length("first name", $_POST["first_name"], 2, 30)) {
        $errors[] = $error;
    }

    if ($error = check_contains_integer("first name", $_POST["first_name"])) {
        $errors[] = $error;
    }

    //checks if the last name has been entered, is not too long for the database and does not contain an integer
    if ($error = check_presence("last name", $_POST["last_name"])) {
        $errors[] = $error;
    }

    if ($error = check_length("last name", $_POST["last_name"], 2, 30)) {
        $errors[] = $error;
    }

    if ($error = check_contains_integer("last name", $_POST["last_name"])) {
        $errors[] = $error;
    }

    //checks if the email has been entered, is not too long for the database and is valid (contains an @ + .something)
    if ($error = check_presence("email", $_POST["email"])) {
        $errors[] = $error;
    }

    if ($error = check_length("email", $_POST["email"], 5, 48)) {
        $errors[] = $error;
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email';
    }

    //checks if the password has been entered and is not too long for the database
    if ($error = check_presence("password", $_POST["password"])) {
        $errors[] = $error;
    }

    if ($error = check_length("password", $_POST["password"], 4, 255)) {
        $errors[] = $error;
    }

    //checks that the password confirmation has been entered and matches the password
    if ($error = check_presence("password confirmation", $_POST["confirm_password"])) {
        $errors[] = $error;
    }


    if ($_POST['password'] != $_POST['confirm_password']) {
        $errors[] = 'Passwords do not match';
    }


    //checks if the email is in the database
    $email_registered_query = "SELECT user_id FROM tbl_customers WHERE email = '" . $_POST['email'] . "'";
    $email_registered = mysqli_query($dbc, $email_registered_query);

    //if the email is already entered adds an error that prompts the user to login
    if (mysqli_num_rows($email_registered) != 0) {
        $errors[] = "Email address already registered
        <a href='login.php'>Login?</a>";
    }

    //checks if any errors occured
    if (empty($errors)) {
        //creates an account if there were no errors
        $account_creation_query = "INSERT INTO tbl_customers
        (first_name, last_name, email, pass, reg_date)
        VALUES ('" . $_POST['first_name'] . "','" .
            $_POST['last_name'] . "','" .
            $_POST['email'] . "',SHA2('" .
            $_POST['password'] .
            "', 256), NOW())";

        //runs query
        $account_creation = mysqli_query($dbc, $account_creation_query);
    }
    //closes database connection
    mysqli_close($dbc);
}
?>

<div class="standard-box">
    <div>
        <!-- Adds a heading to the page -->
        <br>
        <div class="divider"></div>
        <h1>Register</h1>

        <!-- Outputs errors -->
        <?php
        //checks if the errors array exists and contains errors
        if (isset($errors) && !empty($errors)) {
            //outputs message saying there were errors
            echo "<h2 class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem </h2><br>";
            //loops through and outputs each error
            foreach ($errors as $msg) {
                echo "<p id = 'err-msg'> - $msg</p><br>";
            }
        }
        ?>
        <!-- Creates form to send data into file -->
        <form class="centre-content" action="../User-Accounts/register.php" method="POST">
            <div class="divider"></div>

            <!-- Creates input box for first name and adds a label to it -->
            <label for="first_name">First Name:</label><br>
            <input type="text" name="first_name" value="<?php if (isset($_POST['first_name']))
                                                            echo $_POST['first_name'] ?>">
            
            <!-- Creates input box for last name and adds a label to it -->
            <label for="last_name">Last Name:</label><br>
            <input type="text" name="last_name" value="<?php if (isset($_POST['last_name']))
                                                            echo $_POST['last_name'] ?>">
            <br>

            <!-- Creates input box for email and adds a label to it -->
            <label for="email">Email:</label><br>
            <input type="text" name="email" value="<?php if (isset($_POST['email']))
                                                        echo $_POST['email'] ?>">
            <br>

            <!-- Creates input box for password and adds a label to it -->
            <label for="password">Password:</label><br>
            <input type="password" name="password" value="<?php if (isset($_POST['password']))
                                                                echo $_POST['password'] ?>">

            <!-- Creates input box for password confirmation and adds a label to it -->
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" name="confirm_password" value="<?php if (isset($_POST['confirm_password']))
                                                                        echo $_POST['confirm_password'] ?>">

            <!-- Creates submit button -->
            <input class="submit-button" type="submit" value="Register">
            <div class="divider"></div>
        </form>
    </div>
</div>
<!-- Includes header -->
<?php include("../includes/footer.html"); ?>