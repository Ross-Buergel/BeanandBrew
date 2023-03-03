<?php
$page_title = 'Lessons';   
include('../includes/tall-header.php');

if (!isset($_SESSION['user_id']))
{
    header("Location: ../User-Accounts/login.php");
}

require('../includes/connect_db.php');


echo'<div class = "standard-box"><div class = "centre-content">
<br>
<div class = "divider"></div>
<h1 class = "standard-box-title">Lessons</h1>
<div class = "divider"></div>
';

$q = "SELECT * FROM tbl_lessons";
$r = mysqli_query($dbc,$q);
if(mysqli_num_rows($r) > 0)
{
    while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
    {
        echo '<p class = "standard-box-text" style = "margin-left:250px;margin-right:250px;"><strong>'.$row['name'].
        '</strong><br>'.$row['summary'].
        '<br><button class = "submit-button"><a href="view-more.php?id='.$row['lesson_id'].
        '" class = "standard-box-text" style = "text-decoration:none;">View More</a></button></p>
        <div class = "divider"></div>';
    }
    mysqli_close($dbc);
}
else
{
    echo'<h1 class = "standard-box-title">There are currently no lessons available</h1>';
}
echo'</div></div>';
include('../includes/footer.html');
?>