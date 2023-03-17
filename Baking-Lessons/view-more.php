<?php
//sets the page title and gets the header
$page_title = 'View More';
include('../includes/header.php');

//redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//gets the id of the lesson if it is being sent to the file
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//connects to the database
require('../includes/connect_db.php');

//gets the lesson info from the table in the database
$q = "SELECT * FROM tbl_lessons WHERE lesson_id = $id";

//runs the query
$r = mysqli_query($dbc, $q);

//turns the returned result into an array
$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
?>

<div class="standard-box">
    <div class="centre-content">
        <!-- outputs the lesson info -->
        <h1><?php echo $row['name'] ?></h1>
        <p class="standard-box-text"><br><?php echo $row['text'] ?></p>
        <p class="standard-box-text"><br><?php echo $row['date'] . ' at ' . $row['time'] ?></p>

        <!-- creates button that takes user back to all lessons -->
        <button class="submit-button">
            <a href="./lessons.php" class="standard-box-text" style="text-decoration:none">Back to Lessons</a>
        </button><br><br>

        <!-- creates button that takes user to booking page -->
        <button class="submit-button">
            <a href="booking.php?id=<?php echo $row['lesson_id'] ?>" class="standard-box-text" style="text-decoration:none;">Book</a>
        </button>
    </div>
</div>

<?php
//closes the database connection and adds the footer
mysqli_close($dbc);
include('../includes/footer.html')
?>