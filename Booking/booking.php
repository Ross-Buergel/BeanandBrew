<?php
//sets the page title and checks if the user is logged in
//if not it redirects the user to the login page
$page_title = 'Booking';
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) 
{
    header("Location: ../User-Accounts/login.php");
}

else
{
    //formats html and creates boxes needed to collect booking details
    echo '<div class = "standard-box">
    <div>
    <form class = "centre-content" action="../Booking/booking-action.php" method = "POST">
    <br>
    <div class = "divider"></div>
    <h1 class = "standard-box-title">Booking</h1>
    <div class = "divider"></div>
    <p class = "standard-box-text">
    Location <br><select name = "location">
    <option value = "Harrogate">Harrogate</option>
    <option value = "Leeds">Leeds</option>
    <option value = "Knaresborough Castle">Knaresborough Castle</option>
    </select>
    </p>
    <p class = "standard-box-text">
    Time <br> <select name = "time">
    <option value = "09:00">9:00</option>
    <option value = "09:30">9:30</option>
    <option value = "10:00">10:00</option>
    <option value = "10:30">10:30</option>
    <option value = "11:00">11:00</option>
    <option value = "11:30">11:30</option>
    <option value = "12:00">12:00</option>
    <option value = "12:30">12:30</option>
    <option value = "13:00">13:00</option>
    <option value = "13:30">13:30</option>
    <option value = "14:00">14:00</option>
    <option value = "14:30">14:30</option>
    <option value = "15:00">15:00</option>
    <option value = "15:30">15:30</option>
    <option value = "16:00">16:00</option>
    </select>
    </p>
    <p class = "standard-box-text">
    Date<br>
    <input name = "date" type = "date" style = "text-align:center"></input>
    </p>
    <p class = "standard-box-text">
    Number of People <br><select name = "people">
    <option value = "1">1</option>
    <option value = "2">2</option>
    <option value = "3">3</option>
    <option value = "4">4</option>
    </select>
    </p>
    <p>
    <input type = "submit" value = "Submit" class = "submit-button">
    </p>
    <div class = "divider"></div>
    </form>
    </div>
    </div>';
}

//includes footer
include('../includes/footer.html');
?>