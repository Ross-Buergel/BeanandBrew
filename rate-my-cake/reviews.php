<?php
$page_title = 'Rate my Cake';
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) 
{
    header("Location: ../User-Accounts/login.php");
}

else
{
    require('../includes/connect_db.php');

    $msg = '<div class = "standard-box">
    <div>
    <form class = "centre-content" action="../rate-my-cake/review-action.php" method = "POST">
    <br>
    <div class = "divider"></div>
    <h1 class = "standard-box-title">Reviews</h1>
    <div class = "divider"></div>
    <p class = "standard-box-text">
    Location: <br> <select name = "Location">
    <option value = "Leeds">Leeds</option>
    <option value = "Knaresborough Castle">Knaresborough Castle</option>
    <option value = "Harrogate">Harrogate</option>
    </select>
    </p>
    <p class = "standard-box-text">
    Cake: <br> <select name = "Cake">
    <option value = "N/A">N/A</option>';
    $q = "SELECT * FROM tbl_shop
    WHERE category = 'Food'";
    $r = mysqli_query($dbc,$q);
    if(mysqli_num_rows($r) > 0)
    {
        while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
        {
            $msg .= '<option value = "'.$row['item_name'].'">'.$row['item_name'].'</option>';
        }
    }
    $msg .= '</select>
    </p>
    <p class = "standard-box-text">
    Review: <br> <textarea name = "text" rows = "4" cols = "50" placeholder = "What is your review (max 250 characters)"style = 
    "resize: none;"></textarea>
    </p>
    <p class = "standard-box-text">
    Rating <select name = "rating">
    <option value = "1">1</option>
    <option value = "2">2</option>
    <option value = "3">3</option>
    <option value = "4">4</option>
    <option value = "5">5</option>
    </select>
    </p>
    <p>
    <input type = "submit" value = "Submit" class = "submit-button">
    </p>
    <div class = "divider"></div>
    <p class = "standard-text">Want to view the reviews?<br> Click <a href = "../rate-my-cake/view-reviews.php" 
    >Here</a></p><div class = "divider"></div>
    </form></div></div>';
    echo $msg;
    include('../includes/footer.html');
}

?>