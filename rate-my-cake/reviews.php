<?php
//sets the page title, adds the header and connects to the database
$page_title = "View Reviews";
include("../includes/header.php");
require("../includes/connect_db.php");

//gets all food products from toe table so they can be added as options to the select box later in the code
$food_products_query = "SELECT * FROM tbl_shop
WHERE category = 'Food'";
$food_products = mysqli_query($dbc, $food_products_query);

//checks if there is data coming into the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //gets the validation functions
    require("../includes/validation-functions.php");

    //creates the array containing errors
    $errors = array();

    //checks if the location is anything other than the 3 store locations
    if ($_POST['location'] != "Leeds" and $_POST['Location'] != "Knaresborough Castle" and $_POST['Location'] != "Harrogate") {
        $errors[] = 'Invalid Location';
    }

    //checks if the cake is a real product Bean and Brew sells
    $cake_check = False;

    while ($food_products_array = mysqli_fetch_array($food_products, MYSQLI_ASSOC)) {
        if ($food_products_array['item_name'] = $_POST['Cake']) {
            $cake_check = True;
        }
    }

    //adds an error if it is not a real product
    if (!$cake_check) {
        $errors[] = 'Invalid Cake';
    }

    //checks that the review has been entered
    if ($error = check_presence("review", $_POST["review"])) {
        $errors[] = $error;
    }

    //checks that the rating has been entered and is within the accepted values
    if ($error = check_presence("rating", $_POST["rating"])) {
        $errors[] = $error;
    }

    if ($_POST['rating'] > 5 or $_POST['rating'] < 1) {
        $errors[] = 'Invalid Rating';
    }

    //checks if there were any errors
    if (empty($errors)) {
        //adds review to table if there were no errors
        $leave_review_query = "INSERT INTO tbl_reviews(user_id,location,cake,text,rating)
        VALUES ('" . $_SESSION['user_id'] . "','" .
            $_POST['location'] . "','" .
            $_POST['cake'] . "','" .
            $_POST['review'] . "','" .
            $_POST['rating'] . "')";

        //runs query
        $leave_review = mysqli_query($dbc, $leave_review_query);

        //reloads page so that review will display
        header("Location: /reviews.php");
    }
}
?>

<div class="standard-box">
    <div class="centre-content">
        <!-- Adds heading to the page -->
        <br>
        <div class="divider"></div>
        <h1>Reviews</h1><br>
        <div class="divider"></div>

        <!-- Outputs errors -->
        <?php 
        //checks that the user is logged in
        if (isset($_SESSION["user_id"])) :
            //checks if there were errors
            if (isset($errors) && !empty($errors)) {
                //outputs heading saying there were errors
                echo "<h2 class = 'standard-box-title' id = 'err_msg'>Oops! There was a problem </h2><br>";
                //loops through and outputs each error
                foreach ($errors as $msg) {
                    echo "<p id = 'err-msg'> - $msg</p><br>";
                }
            }
        ?>
            <form class="centre-content" action="/rate-my-cake/reviews.php" method="POST">
                <!-- Creates select box for store location and adds label -->
                <label for="location">Location:</label> <br>
                <select name="location">
                    <option value="Leeds">Leeds</option>
                    <option value="Knaresborough Castle">Knaresborough Castle</option>
                    <option value="Harrogate">Harrogate</option>
                </select><br>

                <!-- Creates select box for product and adds label -->
                <label for="cake">Cake:</label><br>
                <select name="cake">
                    <option value="N/A">N/A</option>';
                    <?php
                    //loops through each product adding it as an option
                    while ($food_products_array = mysqli_fetch_array($food_products, MYSQLI_ASSOC)) {
                        echo "<option value = '" .
                            $food_products_array["item_name"] . "'>" .
                            $food_products_array["item_name"] .
                            "</option>";
                    }
                    ?>
                </select><br>
                
                <!-- Creates input box for review and adds lavel to it -->
                <label for="review">Review:</label> <br>
                <textarea name="review" rows="4" cols="50" placeholder="What is your review (max 250 characters)" 
                style="resize: none;"></textarea><br>

                <!-- Creates select box for rating and adds label to it -->
                <label for="rating">Rating:</label><br>
                <select name="rating">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select><br>

                <!-- Creates submit button -->
                <input type="submit" value="Submit" class="submit-button">
            </form>
            <!-- Outputs error if user is not logged in -->
        <?php else : ?>
            <h2>You must be logged in to post a review</h2>
        <?php endif; 

        //gets all reviews
        $reviews_query = "SELECT * FROM tbl_reviews";
        $reviews = mysqli_query($dbc, $reviews_query);
        
        //checks if there are any reviews
        if (mysqli_num_rows($reviews) > 0) {
            //loops through each review outputting it
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
            //outputs appropriate message
            echo "<p>There are currently no reviews</p>";
        }
        //closes database connection
        mysqli_close($dbc);
        ?>
        <br>
    </div>
</div>
<!-- Adds footer -->
<?php include("../includes/footer.html");?>