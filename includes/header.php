<?php
//creates a session
session_start();
?>
<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>
    <!-- Adds title to page -->
    <?php echo $page_title; ?>
  </title>
  <!-- Includes stylesheet on page -->
  <link rel="stylesheet" href="/BeanandBrew/includes/style.css">
</head>

<body class="background-image">
  <header>
    <div class="container">
      <nav>
        <ul>
          <!-- Adds home, pre-order and reviews links to header -->
          <li><a class="create-line" href="/BeanandBrew/index.php">Home</a></li>
          <li><a class="create-line" href="/BeanandBrew/Preorder/shop.php">Pre-Order</a></li>
          <li><a class="create-line" href="/BeanandBrew/rate-my-cake/reviews.php">Reviews</a></li>

          <!-- Adds booking dropdown to header -->
          <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Booking</a>
            <div class="dropdown-content">
              <!-- Adds links to dropdown -->
              <a class="create-line-dropdown" href="/BeanandBrew/Booking/booking.php">Book a Space</a>
              <a class="create-line-dropdown" href="/BeanandBrew/Baking-Lessons/lessons.php">Baking Lesson</a>
            </div>
          </li>
          <!-- Adds accounts drop down -->
          <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Account</a>
            <div class="dropdown-content">
              <?php
              //adds create-accoutn and login page to dropcown if user is not logged in
              if (!isset($_SESSION['user_id'])) {
                echo '
              <a class = "create-line-dropdown" href="/BeanandBrew/User-Accounts/register.php">Create Account</a>
              <a class = "create-line-dropdown" href="/BeanandBrew/User-Accounts/login.php">Login</a>';
              } else {
                //adds logout if user is logged in
                echo '
              <a class = "create-line-dropdown" href="/BeanandBrew/User-Accounts/logout.php">Logout</a>';
                if ($_SESSION['user_id'] == "1") {
                  //adds view orders if user is logged into the staff account
                  echo '
                <a class = "create-line-dropdown" href="/BeanandBrew/Preorder/view-orders.php">View Orders</a>';
                }
              } ?>
            </div>
          </li>
        </ul>
      </nav>
    </div>
  </header>