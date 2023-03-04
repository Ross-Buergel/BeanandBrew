<?php
$page_title = "View Reviews";
include("../includes/header.php");
require("../includes/connect_db.php");
require("../includes/validation-functions.php");

$food_products_query = "SELECT * FROM tbl_shop
WHERE category = 'Food'";
$food_products = mysqli_query($dbc, $food_products_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    if ($_POST['location'] != "Leeds" and $_POST['Location'] != "Knaresborough Castle" and $_POST['Location'] != "Harrogate") {
        $errors[] = 'Invalid Location';
    }


    $cake_check = False;

    while ($food_products_array = mysqli_fetch_array($food_products, MYSQLI_ASSOC)) {
        if ($food_products_array['item_name'] = $_POST['Cake']) {
            $cake_check = True;
        }
    }

    if (!$cake_check) {
        $errors[] = 'Invalid Cake';
    }


    if ($error = check_presence("review", $_POST["review"])) {
        $errors[] = $error;
    }


    if ($error = check_presence("rating", $_POST["rating"])) {
        $errors[] = $error;
    }
    
    if ($_POST['rating'] > 5 or $_POST['rating'] < 1) {
        $errors[] = 'Invalid Rating';
    }

    if (empty($errors)) {
        $leave_review_query = "INSERT INTO tbl_reviews(user_id,location,cake,text,rating)
        VALUES ('".$_SESSION['user_id']."','".
        $_POST['location']."','".
        $_POST['cake']."','".
        $_POST['review']."','".
        $_POST['rating']."')";

        $leave_review = mysqli_query($dbc, $leave_review_query);
        header("Location: /reviews.php");
    }
}
?>

<div class="standard-box">
    <div class="centre-content">
        <br>
        <div class="divider"></div>


        <h1 class="standard-box-title">Reviews</h1><br>
        <div class="divider"></div>

        <?php if (isset($_SESSION["user_id"])) : 
            
            if (isset($errors) && !empty($errors)) {
                echo "<h2 class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem </h2><br>";
                foreach ($errors as $msg) {
                    echo "<p id = 'err-msg'> - $msg</p><br>";
                }
                echo "<h2>Please try again or <a href='register.php' >Register</a></h2>";
            }
            ?>
            <form class="centre-content" action="/rate-my-cake/reviews.php" method="POST">

                <label for="location">Location:</label> <br>
                <select name="location">
                    <option value="Leeds">Leeds</option>
                    <option value="Knaresborough Castle">Knaresborough Castle</option>
                    <option value="Harrogate">Harrogate</option>
                </select>

                <label for="cake">Cake:</label><br>
                <select name="cake">
                    <option value="N/A">N/A</option>';
                    <?php
                    while ($food_products_array = mysqli_fetch_array($food_products, MYSQLI_ASSOC)) {
                        echo "<option value = '" .
                            $food_products_array["item_name"] . "'>" .
                            $food_products_array["item_name"] .
                            "</option>";
                    }
                    ?>
                </select>

                <label for="review">Review:</label> <br>
                <textarea name="review" rows="4" cols="50" placeholder="What is your review (max 250 characters)" style="resize: none;"></textarea>

                <label for="rating">Rating:</label><br>
                <select name="rating">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <input type="submit" value="Submit" class="submit-button">
            </form>
        <?php else : ?>
            <h2>You must be logged in to post a review</h2>
        <?php endif ?>
        <?php
        $reviews_query = "SELECT * FROM tbl_reviews";
        $reviews = mysqli_query($dbc, $reviews_query);

        if (mysqli_num_rows($reviews) > 0) {
            while ($reviews_array = mysqli_fetch_array($reviews, MYSQLI_ASSOC)) {
                $name = $_SESSION["first_name"] . " " . $_SESSION["last_name"];
                echo "
                    <p><strong>" . $name .
                    "</strong><br>" .
                    $reviews_array["location"] .
                    "<br>" . $reviews_array["cake"] .
                    "<br>" . $reviews_array["text"] .
                    "<br>" . $reviews_array["rating"] . "/5" .
                    "<br><br></p>
                    <div class = 'divider'></div>";
            }
        } else {
            echo "<p>There are currently no reviews</p>";
        }
        mysqli_close($dbc);
        ?>
        <br>
    </div>
</div>

<?php
include("../includes/footer.html");
?>