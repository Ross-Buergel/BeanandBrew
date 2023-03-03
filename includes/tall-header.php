<?php 
  session_start();
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> <?php echo $page_title;?></title>
    <link rel = "stylesheet" href = "/BeanandBrew/includes/style.css">
</head>

<body class="tall-background-image">
  <header>
    <div class="container">
      <nav>
        <ul>
          <li><a class = "create-line" href="/BeanandBrew/index.php">Home</a></li>
          <li><a class = "create-line" href="/BeanandBrew/Preorder/shop.php">Pre-Order</a></li>
          <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Reviews</a>
            <div class="dropdown-content">
            <a class = "create-line-dropdown" href="/BeanandBrew/rate-my-cake/view-reviews.php">View Reviews</a>
            <a class = "create-line-dropdown" href="/BeanandBrew/rate-my-cake/reviews.php">Leave a Review</a>
            </div>
          </li>
          <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Booking</a>
            <div class="dropdown-content">
            <a class = "create-line-dropdown" href="/BeanandBrew/Booking/booking.php">Book a Space</a>
            <a class = "create-line-dropdown" href="/BeanandBrew/Baking-Lessons/lessons.php">Baking Lesson</a>
            </div>
          </li>
          <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Account</a>
            <div class="dropdown-content">
            <?php 
            if (!isset($_SESSION['user_id']))
            {
              echo'
              <a class = "create-line-dropdown" href="/BeanandBrew/User-Accounts/register.php">Create Account</a>
              <a class = "create-line-dropdown" href="/BeanandBrew/User-Accounts/login.php">Login</a>';
            }
            else
            {
              echo'
              <a class = "create-line-dropdown" href="/BeanandBrew/User-Accounts/logout.php">Logout</a>';
            }?>
          </div>
          </li>
        </ul>
      </nav>
    </div>
  </header>