<?php
$page_title = 'Booking Confirmation';
include('../includes/header.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

require('../includes/connect_db.php');
$booking_query = "INSERT INTO tbl_lesson_bookings(lesson_id,user_id)
VALUES (" . $id . "," . $_SESSION['user_id'] . ")";
$booking = mysqli_query($dbc, $booking_query);
?>

<div class="standard-box">
    <div class="centre-content">
        <h1>Booking Confirmed</h1>
    </div>
</div>';
<?php
include('../includes/footer.html');
?>