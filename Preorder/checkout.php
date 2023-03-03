<?php
$page_title = 'Checkout';
include('../includes/header.php');

if(!isset($_SESSION['user_id']))
{
    header("Location: ../User-Accounts/login.php");
}

if(isset($_POST['total'])&&($_POST['total']>0)&&(!empty($_SESSION['cart'])))
{
    echo'<div class = "standard-box"><div><form class = "centre-content" action = "../Preorder/checkout-action.php" method = "POST">
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
    <input name = "date" type = "date" placeholder = "DD-MM-YYYY" style = "text-align:center"></input>
    </p>
    <p class = "standard-box-text">
    Location <br><select name = "location">
    <option value = "Harrogate">Harrogate</option>
    <option value = "Leeds">Leeds</option>
    <option value = "Knaresborough Castle">Knaresborough Castle</option>
    </select>
    <input type = "hidden" name = "total" value = "'.$_POST['total'].'">';

    if (isset($_POST['hamper']))
    {
        $hamper = $_POST['hamper'];
        echo'<input type = "hidden" name="hamper" value="'.$hamper.'">';
    }
    else
    {
        $hamper = "";
    }
    
    if ($hamper == "True")
    {
        echo'
        <p class = "standard-box-text">
        Name<br>
        <input name = "name" type = "text" placeholder = "Name on card" style = "text-align:center"></input>
        </p>
        <p class = "standard-box-text">
        Message <br> 
        <textarea name = "text" rows = "4" cols = "50" placeholder = "What is your message (max 250 characters)" style = 
        "resize: none;"></textarea>
        </p>
        <input type = "submit" value = "Submit" class = "submit-button">
        </form></div></div>';
    }
    else
    {
        echo'<input type = "submit" value = "Submit" class = "submit-button">
        </form></div></div>';
    }
}  
else
{
    echo'<div class = "standard-box"><div class = "centre-content">
    <h1 class = "standard-box-title">There are no items in your cart</h1></div></div>';
}
include('../includes/footer.html');
?>