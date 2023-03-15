<?php
//sets the page title and checks if the user is logged in
//if not it redirects the user to the login page
$page_title = 'Booking';
include('../includes/header.php');
require('../includes/connect_db.php');
require("../includes/validation-functions.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    if ($error = check_presence("time", $_POST["time"])) {
        $errors[] = $error;
    }

    if ($error = check_presence("date", $_POST["date"])) {
        $errors[] = $error;
    }

    if (date("d-m-Y H:i", strtotime($_POST['date'] . ' ' . $_POST['time'])) < date("d-m-Y H:i")) {
        $errors[] = "Invalid Booking Date/Time";
    }


    if ($error = check_presence("people", $_POST["people"])) {
        $errors[] = $error;
    }

    if ($_POST['people'] > 4 or $_POST['people'] < 0) {
        $errors[] = 'Invalid number of people';
    }


    if ($error = check_presence("location", $_POST["location"])) {
        $errors[] = $error;
    }

    if ($_POST['location'] != "Leeds" and $_POST['location'] != "Knaresborough Castle" and $_POST['location'] != "Harrogate") {
        $errors[] = 'Invalid Location';
    }


    if (empty($errors)) {
        $name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];

        $booking_query = "INSERT INTO tbl_bookings(user_id,name,time,date,people,location)
        VALUES ('" . $user_id . "','" .
            $name . "','" .
            $_POST['time'] . "','" .
            $_POST['date'] . "','" .
            $_POST['people'] . "','" .
            $_POST['location'] . "')";

        $booking = mysqli_query($dbc, $booking_query);
    }
}
?>

<div class="standard-box">
    <div>
        <form class="centre-content" action="/Booking/booking.php" method="POST">
            <br>
            <div class="divider"></div>
            <h1>Booking</h1>
            <div class="divider"></div>

            <?php
            if (isset($errors) && !empty($errors)) {
                echo "<h2 class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem </h2><br>";
                foreach ($errors as $msg) {
                    echo "<p id = 'err-msg'> - $msg</p><br>";
                }
                echo "<h2>Please try again or <a href='register.php' >Register</a></h2>";
            }
            ?>

            <br>
            <label for="location">Location:</label><br>
            <select name="location">
                <option value="Harrogate">Harrogate</option>
                <option value="Leeds">Leeds</option>
                <option value="Knaresborough Castle">Knaresborough Castle</option>
            </select><br><br>

            <label for="time">Time:</label><br>
            <select name="time">
                <option value="09:00">9:00</option>
                <option value="09:30">9:30</option>
                <option value="10:00">10:00</option>
                <option value="10:30">10:30</option>
                <option value="11:00">11:00</option>
                <option value="11:30">11:30</option>
                <option value="12:00">12:00</option>
                <option value="12:30">12:30</option>
                <option value="13:00">13:00</option>
                <option value="13:30">13:30</option>
                <option value="14:00">14:00</option>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
                <option value="15:30">15:30</option>
                <option value="16:00">16:00</option>
            </select><br><br>


            <label for="date">Date:</label><br>
            <input name="date" type="date" style="text-align:center"><br><br>

            <label for="people">Number of People:</label><br>
            <select name="people">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select><br><br>

            <input type="submit" value="Submit" class="submit-button"><br><br>
            <div class="divider"></div>
        </form>
    </div>
</div>

<?php
include('../includes/footer.html');
?>