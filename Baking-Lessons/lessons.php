<?php
$page_title = 'Lessons';
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

require('../includes/connect_db.php');
?>

<div class="standard-box">
    <div class="centre-content">
        <br>
        <div class="divider"></div>
        <h1>Lessons</h1>
        <div class="divider"></div>

        <?php
        $lessons_query = "SELECT * FROM tbl_lessons";
        $lessons = mysqli_query($dbc, $lessons_query);

        if (mysqli_num_rows($lessons) > 0) :
            while ($lessons_array = mysqli_fetch_array($lessons, MYSQLI_ASSOC)) :
        ?>
                <p class="standard-box-text" style="margin-left:250px;margin-right:250px;">
                    <strong><?php echo $lessons_array['name'] ?></strong><br>
                    <?php echo $lessons_array['summary'] ?><br>

                    <button class="submit-button">
                        <a href="view-more.php?id='<?php echo $lessons_array['lesson_id'] ?>
                    '" class="standard-box-text" style="text-decoration:none;">View More</a>
                    </button>
                </p>

                <div class="divider"></div>
            <?php
            endwhile;
            mysqli_close($dbc);
        else :
            ?>
            <h2>There are currently no lessons available</h2>
        <?php
        endif;
        ?>
    </div>
</div>

<?php
include('../includes/footer.html');
?>