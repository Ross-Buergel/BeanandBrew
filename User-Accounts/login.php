<!DOCTYPE html>
<?php
$page_title = "Login";
include("../includes/header.php");
include("../includes/validation-functions.php");
include("../includes/connect_db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $errors = [];

    if ($var = check_presence("email", $_POST["email"])) {
        $errors[] = $var;
    }

    if ($var = check_presence("pass", $_POST["pass"])) {
        $errors[] = $var;
    }


    if ($var = check_length("pass", $_POST["pass"], 5, 48)) {
        $errors[] = $var;
    }

    if ($var = check_length("email", $_POST["email"], 4, 255)) {
        $errors[] = $var;
    }



    if (!isset($errors) || empty($errors) || $errors == null) {
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

            //assigns values to session
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['first_name'] = $data['first_name'];
            $_SESSION['last_name'] = $data['last_name'];

            //redirects user to the home page
            header("Location: ../index.php");
        } else {
            $contains_error = True;
            $errors[] = "No account found with the provided details";
        }
    }
}

?>
<div class="standard-box">
    <div>
        <form class="centre-content" action="login.php" method="POST">
            <br>
            <div class="divider"></div>
            <h1 class="standard-box-title">Login</h1>
            <div class="divider"></div><br>

            <?php
            if (isset($errors) && !empty($errors)) {
                echo "<h2 class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem </h2><br>";
                foreach ($errors as $msg) {
                    echo "<p> - $msg</p><br>";
                }
                echo "<h2>Please try again or <a href='register.php' >Register</a></h2>";
            }
            ?>

            <label class="standard-box-text" for="email">Email Address: <br> </label>
            <input type="text" name="email"> <br><br>

            <label class="standard-box-text" for="pass">Password: <br> </label>
            <input type="password" name="pass"> <br><br>

            <input type="submit" value="Login" class="submit-button"><br><br>

            <div class="divider"></div>
            <p class="standard-text">Don't have an account?<br>Create one<a href="register.php">Here</a>
            <div class="divider"></div>
        </form>
    </div>
</div>
<?php include("../includes/footer.html"); ?>