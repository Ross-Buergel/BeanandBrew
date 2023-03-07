<?php
$page_title = 'View More';
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

require('../includes/connect_db.php');
$q = "SELECT * FROM tbl_lessons WHERE lesson_id = $id";
$r = mysqli_query($dbc, $q);
$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
?>

<div class="standard-box">
    <div class="centre-content">
        <h1 class="standard-box-title"><?php echo $row['name'] ?></h1>
        <p class="standard-box-text"><br><?php echo $row['text'] ?></p>
        <p class="standard-box-text"><br><?php echo $row['date'] . ' at ' . $row['time'] ?></p>

        <button class="submit-button">
            <a href="./lessons.php" class="standard-box-text" style="text-decoration:none">Back to Lessons</a>
        </button><br><br>

        <button class="submit-button">
            <a href="booking.php?id=<?php echo $row['lesson_id'] ?>" class="standard-box-text" style="text-decoration:none;">Book</a>
        </button>
    </div>
</div>

<?php
mysqli_close($dbc);
include('../includes/footer.html')
?>