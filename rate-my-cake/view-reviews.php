<?php
$page_title = "View Reviews";
include('../includes/header.php');
require('../includes/connect_db.php');


$q1 = "SELECT * FROM tbl_reviews";
$r1 = mysqli_query($dbc, $q1);

echo'<div class = "standard-box"><div class = "centre-content">
<br><div class = "divider"></div>
<h1 class = "standard-box-title">View Reviews</h1><br>
<div class = "divider"></div>';



if (mysqli_num_rows($r1) > 0) {
    while ($row1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)) 
    {
        $user_id = $row1['user_id'];
        $q2 = "SELECT first_name,last_name FROM tbl_customers
        WHERE user_id = '$user_id'";
        $r2 = mysqli_query($dbc, $q2);
        $row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC);
        $name = $row2['first_name'] . ' ' . $row2['last_name'];
        echo '
            <p class = "standard-box-text"><strong>' . $name .
            '</strong><br>' . 
            $row1['location'].
            '<br>'.$row1['cake'] .
            '<br>' . $row1['text'] .
            '<br>' . $row1['rating'] . '/5'.'<br><br></p>
            <div class = "divider"></div>';
    }
    mysqli_close($dbc);
}
else {
    echo '<p>There are currently no reviews</p>';
}
echo'<br></div></div>'; 
include('../includes/footer.html')
?>