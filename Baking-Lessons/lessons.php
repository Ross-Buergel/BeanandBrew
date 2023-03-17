<?php
//sets the page title and adds the header
$page_title = 'Lessons';
include('../includes/header.php');

//redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//connects to the database
require('../includes/connect_db.php');
?>

<div class="standard-box">
    <div class="centre-content">
        <br>
        <!-- Adds a header to the page -->
        <div class="divider"></div>
        <h1>Lessons</h1>
        <div class="divider"></div>

        <?php
        //gets all lessons from the table and runs the query
        $lessons_query = "SELECT * FROM tbl_lessons";
        $lessons = mysqli_query($dbc, $lessons_query);

        //checks if there were lessons in the returned query
        if (mysqli_num_rows($lessons) > 0) :
            //loops through each row
            while ($lessons_array = mysqli_fetch_array($lessons, MYSQLI_ASSOC)) :
        ?>
                <!-- outputs the lesson info for each lesson -->
                <p class="standard-box-text" style="margin-left:250px;margin-right:250px;">
                    <strong><?php echo $lessons_array['name'] ?></strong><br>
                    <?php echo $lessons_array['summary'] ?><br>

                    <!-- Outputs view more button -->
                    <button class="submit-button">
                        <a href="view-more.php?id='<?php echo $lessons_array['lesson_id'] ?>
                    '" class="standard-box-text" style="text-decoration:none;">View More</a>
                    </button>
                </p>

                <div class="divider"></div>
            <?php
            endwhile;
            //closes the database connection
            mysqli_close($dbc);
        else :
            ?>
            <!-- Outputs appropriat message -->
            <h2>There are currently no lessons available</h2>
        <?php
        endif;
        ?>
    </div>
</div>

<!-- Adds footer to the page -->
<?php include('../includes/footer.html');?>