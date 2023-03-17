<?php
//sets page title and adds header to the page
$page_title = 'Booking Confirmation';
include('../includes/header.php');

//gets the id if one is being sent to the file
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//connects to the database
require('../includes/connect_db.php');

//adds booking to booking table
$booking_query = "INSERT INTO tbl_lesson_bookings(lesson_id,user_id)
VALUES (" . $id . "," . $_SESSION['user_id'] . ")";
$booking = mysqli_query($dbc, $booking_query);
?>
<!-- Outputs appropriate message -->
<div class="standard-box">
    <div class="centre-content">
        <h1>Booking Confirmed</h1>
    </div>
</div>';

<!-- Adds footer to the page -->
<?php include('../includes/footer.html');?>