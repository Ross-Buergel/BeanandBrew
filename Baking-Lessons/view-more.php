<?php
$page_title='View More';
include('../includes/header.php');

if (!isset($_SESSION['user_id']))
{
    header("Location: ../User-Accounts/login.php");
}

if(isset($_GET['id']))
{
    $id = $_GET['id'];
}

require('../includes/connect_db.php');
$q = "SELECT * FROM tbl_lessons WHERE lesson_id = $id";
$r = mysqli_query($dbc,$q);
$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

echo '<div class = "standard-box"><div class = "centre-content"><h1 class = "standard-box-title">'.$row['name'].
'</h1><p class = "standard-box-text"><br>'.$row['text'].
'</p><p class = "standard-box-text"><br>'.$row['date'].' at '.$row['time'].'</p>';



echo'<button class = "submit-button"><a href = "../Baking-Lessons/lessons.php" class = "standard-box-text" style = 
"text-decoration:none">Back to Lessons</a>
</button><br><br><button class = "submit-button"><a href="booking.php?id='.$row['lesson_id'].
'" class = "standard-box-text" style = "text-decoration:none;">Book</a></button>
</div></div>';

mysqli_close($dbc);
include('../includes/footer.html')
?>