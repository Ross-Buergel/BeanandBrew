<!DOCTYPE html>
<?php
//sets the page title then gets the header
$page_title = "Login";
include("../includes/header.php");


//checks if there is data  coming into the file
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    //gets the functions for validation and connects to the database
    include("../includes/validation-functions.php");
    include("../includes/connect_db.php");

    //creates the array containing errors
    $errors = [];

    //checks that the email has been entered and is not too long for the database
    if ($error = check_presence("email", $_POST["email"])) {
        $errors[] = $error;
    }


    if ($error = check_length("email", $_POST["email"], 4, 255)) {
        $errors[] = $error;
    }

    //checks that the user has entered a valid email (containing @ + .something)
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email';
    }


    //checks that the password has been entered and is not too long for the database
    if ($error = check_presence("pass", $_POST["pass"])) {
        $errors[] = $error;
    }

    if ($error = check_length("pass", $_POST["pass"], 5, 48)) {
        $errors[] = $error;
    }

    //checks if there were any errors
    if (empty($errors)) {
        //assigns values to a variable
        $email = $_POST["email"];
        $password = $_POST["pass"];

        //runs sql query
        $query = "SELECT user_id, first_name, last_name FROM tbl_customers
        WHERE email = '$email'
        AND pass = SHA2('$password',256)";
        $result = mysqli_query($dbc, $query);

        //checks if a result was returned
        if (mysqli_num_rows($result) == 1) {
            //starts a session
            session_start();

            //turns the returned data into an array
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

            //assigns values to session
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['first_name'] = $data['first_name'];
            $_SESSION['last_name'] = $data['last_name'];

            //redirects user to the home page
            header("Location: ../index.php");
        } else {
            //adds an error to the array
            $errors[] = "No account found with the provided details";
        }
    }
    //closes the database connection
    mysqli_close($dbc);
}

?>
<div class="standard-box">
    <div>
        <form class="centre-content" action="login.php" method="POST">
            <!-- Adds a heading to the page -->
            <br>
            <div class="divider"></div>
            <h1>Login</h1>
            <div class="divider"></div><br>

            <!-- Outputs all errors if there were any -->
            <?php
            //checks if there were errors
            if (isset($errors) && !empty($errors)) {
                //outputs title saying there were errors
                echo "<h2 class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem </h2><br>";

                //loops through and outputs each error
                foreach ($errors as $msg) {
                    echo "<p id = 'err-msg'> - $msg</p><br>";
                }
                //outputs link to register page
                echo "<h2>Please try again or <a href='register.php' >Register</a></h2>";
            }
            ?>

            <!-- Creates input box for email and adds label to it -->
            <label class="standard-box-text" for="email">Email Address: <br> </label>
            <input type="text" name="email"> <br><br>

            <!-- Creates input box for password and adds label to it -->
            <label class="standard-box-text" for="pass">Password: <br> </label>
            <input type="password" name="pass"> <br><br>

            <!-- Creates submit button -->
            <input type="submit" value="Login" class="submit-button"><br><br>

            <!-- Adds link to register page -->
            <div class="divider"></div>
            <p class="standard-text">Don't have an account?<br>Create one<a href="register.php">Here</a>
            <div class="divider"></div>
        </form>
    </div>
</div>
<!-- Adds the footer -->
<?php include("../includes/footer.html"); ?>