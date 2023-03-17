<?php
//adds title and header to page
$page_title = 'Checkout';
include('../includes/header.php');

//redirects user to login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//connects to the database and gets the functions for validation
require("../includes/connect_db.php");
require("../includes/validation-functions.php");

//checks if data is coming into the form, the cart and location are set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['cart']) && isset($_POST['location'])) :
    //adds total to a variable
    $total = $_POST['total'];

    //selects items information from table
    $cart_items_query = "SELECT * FROM tbl_shop WHERE item_id IN (";
    foreach ($_SESSION['cart'] as $id => $value) {
        $cart_items_query .= $id . ',';
    }
    $cart_items_query = substr($cart_items_query, 0, -1) . ') ORDER BY item_id ASC';

    //runs query
    $cart_items = mysqli_query($dbc, $cart_items_query);

    //creates array containing errors
    $errors = array();

    //checks that the date, time and location have been entered
    if ($error = check_presence("time", $_POST["time"])) {
        $errors[] = $error;
    }


    if ($error = check_presence("date", $_POST["date"])) {
        $errors[] = $error;
    }


    if ($error = check_presence("location", $_POST["location"])) {
        $errors[] = $error;
    }

    //checks the location is one of the 3 available options
    if ($_POST['location'] != "Leeds" and $_POST['location'] != "Knaresborough Castle" and $_POST['location'] != "Harrogate") {
        $errors[] = 'Invalid Location';
    }

    //checks if there were any errors
    if (empty($errors)) {
        //adds order to table
        $q = "INSERT INTO tbl_orders(user_id,total,order_date,collection_date,collection_time) VALUES('"
            . $_SESSION['user_id'] . "','" . $_POST['total'] . "',NOW(),'" . $_POST['date'] . "','" . $_POST['time'] . "')";
        $r = mysqli_query($dbc, $q);

        //gets id from query
        $order_id = mysqli_insert_id($dbc);

        //loops through each item in the cart
        while ($row = mysqli_fetch_array($cart_items, MYSQLI_ASSOC)) {
            //assigns collection to a variable
            $collection = $_POST['date'] . ", " . $_POST['time'];

            //inserts item into table
            $query = "INSERT INTO tbl_order_contents(order_id,item_id,user_id,quantity,price,location)
            VALUES ('" . $order_id . "','" .
                $row['item_id'] . "','" .
                $_SESSION['user_id'] . "','" .
                $_SESSION['cart'][$row['item_id']]['quantity'] . "','" .
                $_SESSION['cart'][$row['item_id']]['price'] . "','" .
                $_POST['location'] . "')";

                //runs query
            $result = mysqli_query($dbc, $query);
        }

        //updates latest order
        $q = "UPDATE tbl_orders
        SET hamper = ";

        //sets value to false if create hamper checkbox was not ticked
        if (!isset($_POST['hamper'])) {
            $q .= "'0'";
            
        //sets value to true if create hamper checkbox was ticked
        } elseif ($_POST['hamper'] == 'True') {
            $q .= "'1'";

            //inserts hamper details into table
            $q2 = "INSERT INTO tbl_hamper_messages(order_id,name,message)
            VALUES ('" . $order_id . "','" . $_POST['name'] . "','" . $_POST['text'] . "');";
            $r2 = mysqli_query($dbc, $q2);
        }
        $q .= " WHERE order_id = " . $order_id . ";";

        //runs query
        $r = mysqli_query($dbc, $q);

        //closes database connection
        mysqli_close($dbc);

        //outputs success message
        echo '<div class = "standard-box"><div class = "centre-content">
        <h1 class = "standard-box-text">Thanks for your order. Your order number is #' . $order_id . '</h1>
        </div></div>';
    } else {
        //outputs errors
        echo "<div class = 'standard-box'><div class = 'centre-content'><p class = 'standard-box-text' id = 'err_msg'>Oops! 
        There was a problem <br>";
        //loops through each error and outputs it
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo "Please <a href='cart.php'>Try Again</a></p></div></div>";
    }
    //clears cart
    $_SESSION['cart'] = NULL;
else :
    //checks if the user has items in their cart
    if (isset($_POST['total']) && ($_POST['total'] > 0) && (!empty($_SESSION['cart']))) : ?>
        <div class="standard-box">
            <div>
                <form class="centre-content" action="../Preorder/checkout.php" method="POST">
                    <!-- creates input box for time -->
                    <label for="time">Time:</label><br>
                    <select name="time">
                        <option value="09:00">9:00</option>
                        <option value="09:30">9:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                    </select><br><br>

                    <!-- Creates input box for date -->
                    <label for="date">Date:</label><br>
                    <input name="date" type="date" placeholder="DD-MM-YYYY" style="text-align:center"><br><br>

                    <!-- Creates input box for location -->
                    <label for="location">Location:</label><br>
                    <select name="location">
                        <option value="Harrogate">Harrogate</option>
                        <option value="Leeds">Leeds</option>
                        <option value="Knaresborough Castle">Knaresborough Castle</option>
                    </select><br><br>

                    <!-- Adds hidden input for total -->
                    <input type="hidden" name="total" value="<?php echo $_POST['total'] ?>">
                    <?php

                    //adds hamper value to variable if it is set
                    if (isset($_POST['hamper'])) {
                        $hamper = $_POST['hamper'];
                        echo '<input type = "hidden" name="hamper" value="' . $hamper . '">';
                    } else {
                        $hamper = "";
                    }

                    //outputs hamper input boxes if it is true
                    if ($hamper == "True") :
                    ?>
                    <!-- Outputs name box -->
                        <p class="standard-box-text">
                            Name<br>
                            <input name="name" type="text" placeholder="Name on card" style="text-align:center">
                        </p>

                        <!-- Outputs message box -->
                        <p class="standard-box-text">
                            Message <br>
                            <textarea name="text" rows="4" cols="50" placeholder="What is your message (max 250 characters)" style="resize: none;"></textarea>
                        </p>

                    <?php
                    endif;
                    ?>
                    <!-- Outputs submit button -->
                    <input type="submit" value="Submit" class="submit-button">
                </form>
            </div>
        </div>
    <?php
    else :
        //outputs appropriate message
    ?>
        <div class="standard-box">
            <div class="centre-content">
                <h1>There are no items in your cart</h1>
            </div>
        </div>
<?php
    endif;
endif;

//includes footer
include('../includes/footer.html');
?>