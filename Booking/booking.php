<?php
//sets the page title and gets the header
$page_title = 'Booking';
include('../includes/header.php');

//if user is not logged in it redirects them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//checks if there is data coming into the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //connects to database and includes validation functions
    require('../includes/connect_db.php');
    require("../includes/validation-functions.php");

    //creates array containing errors
    $errors = array();

    //checks that the date and time have been entered
    if ($error = check_presence("time", $_POST["time"])) {
        $errors[] = $error;
    }

    if ($error = check_presence("date", $_POST["date"])) {
        $errors[] = $error;
    }

    //checks the date and time are not in the past
    if (date("d-m-Y H:i", strtotime($_POST['date'] . ' ' . $_POST['time'])) < date("d-m-Y H:i")) {
        $errors[] = "Invalid Booking Date/Time";
    }

    //checks that the number of people has been entered and is an appropriate size
    if ($error = check_presence("people", $_POST["people"])) {
        $errors[] = $error;
    }

    if ($_POST['people'] > 4 or $_POST['people'] < 0) {
        $errors[] = 'Invalid number of people';
    }

    //checks that the location has been entered and is a valid location
    if ($error = check_presence("location", $_POST["location"])) {
        $errors[] = $error;
    }

    if ($_POST['location'] != "Leeds" and $_POST['location'] != "Knaresborough Castle" and $_POST['location'] != "Harrogate") {
        $errors[] = 'Invalid Location';
    }

    //checks that there were no errors
    if (empty($errors)) {
        //adds the name to a variable
        $name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];

        //inserts the booking into the table
        $booking_query = "INSERT INTO tbl_bookings(user_id,name,time,date,people,location)
        VALUES ('" . $user_id . "','" .
            $name . "','" .
            $_POST['time'] . "','" .
            $_POST['date'] . "','" .
            $_POST['people'] . "','" .
            $_POST['location'] . "')";

        //runs the query
        $booking = mysqli_query($dbc, $booking_query);
    }
    //closes the database connection
    mysqli_close($dbc);
}
?>

<div class="standard-box">
    <div>
        <form class="centre-content" action="/Booking/booking.php" method="POST">
            <!-- Adds a heading to the page -->
            <br>
            <div class="divider"></div>
            <h1>Booking</h1>
            <div class="divider"></div>

            <!-- Outputs errors -->
            <?php
            //checks if there were errors
            if (isset($errors) && !empty($errors)) {
                //outputs message saying there were errors
                echo "<h2 class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem </h2><br>";

                //loops through each error and outputs it
                foreach ($errors as $msg) {
                    echo "<p id = 'err-msg'> - $msg</p><br>";
                }
            }
            ?>

            <!-- Creates input box for location and adds label to it -->
            <br>
            <label for="location">Location:</label><br>
            <select name="location">
                <option value="Harrogate">Harrogate</option>
                <option value="Leeds">Leeds</option>
                <option value="Knaresborough Castle">Knaresborough Castle</option>
            </select><br><br>

            <!-- Creates input box for time and adds label to it -->
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

            <!-- Creates input box for date and adds label to it -->
            <label for="date">Date:</label><br>
            <input name="date" type="date" style="text-align:center"><br><br>

            <!-- Creates input box for the number of people and adds label to it -->
            <label for="people">Number of People:</label><br>
            <select name="people">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select><br><br>

            <!-- Creates the submit button -->
            <input type="submit" value="Submit" class="submit-button"><br><br>
            <div class="divider"></div>
        </form>
    </div>
</div>
<!-- Adds footer to page -->
<?php include('../includes/footer.html');?>