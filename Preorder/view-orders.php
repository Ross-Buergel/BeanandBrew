<?php
//includes the header
$page_title = 'View Orders';
include('../includes/header.php');

//connects to the database
require('../includes/connect_db.php');

//checks if the user is logged in and if not redirects them to the login page
if ($_SESSION['user_id'] != "1") {
    header("Location: ../User-Accounts/login.php");
}

?>
<div class='standard-box'>
    <div class='centre-content'>
        <br>
        <div class="divider"></div>
        <h1>View Orders</h1>
        <div class="divider"></div>
        <?php
        //creates necessary arrays
        $graph_x_data = array();
        $graph_y_data = array();
        $x_data_map = array();

        //gets data using sql query
        $sql = 'SELECT * FROM tbl_orders ORDER BY collection_date';
        $result = $dbc->query($sql);

        //sets necessary variables
        $index = 0;
        $count = 0;

        //loops through result turning each row into an array
        while ($order = $result->fetch_assoc()) {
            $index++;

            // checks if the date is already present in the $graph_x_data array
            if (isset($x_data_map[$order['collection_date']])) {
                //adds one to the number stored with the collection_date
                $key = $x_data_map[$order['collection_date']];
                $graph_y_data[$key] += 1;
                //if date is not present in $graph_x_data array
            } else {
                //adds one to count
                $count++;

                // add the date to $graph_x_data and update the map
                $graph_x_data[] = $order['collection_date'];
                $x_data_map[$order['collection_date']] = count($graph_x_data) - 1;

                // add the count to $graph_y_data
                $graph_y_data[] = $count;

                $count = 0;
            }
        }

        ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <h2>Number of Orders for Collection per Day</h2>
        <!-- presents the graph -->
        <div style="padding-left:300px;padding-right:300px;">
            <canvas id="myChart" style="width:50%;"></canvas>
        </div>
        <script>
            /*-*/
            //gets the graph element
            const ctx = document.getElementById('myChart');

            //creates the chart and sets the type of chart
            new Chart(ctx, {
                type: 'line',
                data: {
                    //sets the x axis data
                    labels: [<?php foreach ($graph_x_data as $label) {
                                    echo "'" . $label . "',";
                                } ?>],
                    datasets: [{
                        //sets the y axis data
                        label: 'Number of Orders for Collection',
                        data: [<?php foreach ($graph_y_data as $data) {
                                    echo "'" . $data . "',";
                                } ?>],
                        borderWidth: 3,
                        borderColor: "#33cc33"
                    }]
                },
                options: {
                    color: 'white',
                    scales: {
                        //changes the axis colours
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: '#8c8c8c'
                            },
                        },
                        x: {
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: '#8c8c8c'
                            },
                        }
                    },
                    //changes the y label colour
                    plugins: {
                        datalabels: {
                            color: '#36A2EB'
                        }
                    }
                }
            });
        </script>
        <br>
        <div class="divider"></div>
        <h2>Most Popular Items</h2>

        <?php
        /* - */ /*n*/

        //selects the most popular items over the last 7 days
        $popular_drinks_query = "SELECT item_id, COUNT(*) AS number_ordered FROM tbl_order_contents GROUP BY item_id 
        ORDER BY number_ordered DESC LIMIT 5";
        $popular_drinks = mysqli_query($dbc, $popular_drinks_query);

        //outputs each item
        while ($popular_drinks_array = mysqli_fetch_array($popular_drinks, MYSQLI_ASSOC)) {
            //selects item from table
            $product_query = "SELECT * FROM tbl_shop
            WHERE item_id = '" . $popular_drinks_array["item_id"] . "'";
            $product = mysqli_query($dbc, $product_query);

            //outputs items details
            echo '<p>';
            while ($product_array = mysqli_fetch_array($product, MYSQLI_ASSOC)) {
                echo $product_array['item_name'] . "<br>" .
                    "<img src = './" . $product_array['item_img'] . "' style = 'width:10%;height:10%;'><br>";
            }

            echo $popular_drinks_array['number_ordered'] . '</p>';
        }
        ?>
        <div class="divider"></div>
        <h2>Amount Earnt Over Time</h2>

        <?php
        //gets all orders from the last day
        $day_query = "SELECT * FROM tbl_orders
            WHERE order_date >= NOW() - INTERVAL 1 DAY";
        $day = mysqli_query($dbc, $day_query);

        //defines necessary variables
        $day_total = 0;

        //adds up all values
        while ($day_array = mysqli_fetch_array($day, MYSQLI_ASSOC)) {
            $day_total += $day_array['total'];
        }

        //gets the orders from the last 7 days
        $seven_days_query = "SELECT * FROM tbl_orders
        WHERE order_date >= DATE(NOW()) - INTERVAL 7 DAY";
        $seven_days = mysqli_query($dbc, $seven_days_query);

        //defines necessary variables
        $seven_days_total = 0;

        //adds up all the values
        while ($seven_days_array = mysqli_fetch_array($seven_days, MYSQLI_ASSOC)) {
            $seven_days_total += $seven_days_array['total'];
        }
        ?>

        <!-- Outputs values -->
        <p>Past 24 Hours - £<?php echo $day_total; ?></p>
        <p>Past 7 Days - £<?php echo $seven_days_total; ?></p>

        <div class="divider"></div>
        <h2>Orders</h2>

        <?php
        //selects all orders and orders them by the collection date
        $query = "SELECT * FROM tbl_orders ORDER BY collection_date";
        $order = mysqli_query($dbc, $query);

        //loops through each returned row
        while ($order_array = mysqli_fetch_array($order, MYSQLI_ASSOC)) {
            //selects order contents for each order
            $query = "SELECT * FROM tbl_order_contents
                WHERE order_id = '" . $order_array['order_id'] . "'";
            $order_contents = mysqli_query($dbc, $query);

            //outputs order id
            echo "<p>Order ID : " . $order_array['order_id'];

            //loops through each item
            while ($row = mysqli_fetch_array($order_contents, MYSQLI_ASSOC)) {
                //selects item
                $query = "SELECT * FROM tbl_shop
                    WHERE item_id = '" . $row['item_id'] . "'";
                $product = mysqli_query($dbc, $query);

                //outputs item name
                while ($product_array = mysqli_fetch_array($product, MYSQLI_ASSOC)) {
                    echo "<br>Item : " . $product_array["item_name"];
                }
            }
            //outputs collection date
            echo "<br>Collection : " . $order_array['collection_date'] . " " . $order_array['collection_time'] . "<br></p>";
        }
        ?>
        <div class="divider"></div>
        <h2>Total Orders for Each Product Over Last 7 Days</h2>
        <div class="divider"></div>
        <?php
        $total_7_days_query = "SELECT order_id FROM tbl_orders WHERE order_date >= DATE(NOW()) - INTERVAL 7 DAY";
        $total_7_days = mysqli_query($dbc, $total_7_days_query);

        $total_ordered_query = "SELECT item_id, COUNT(*) AS number_ordered FROM tbl_order_contents GROUP BY item_id 
        ORDER BY number_ordered DESC";
        $total_ordered = mysqli_query($dbc, $total_ordered_query);

        //outputs each item
        while ($total_ordered_array = mysqli_fetch_array($total_ordered, MYSQLI_ASSOC)) {
            //selects item from table
            $products_query = "SELECT * FROM tbl_shop
                    WHERE item_id = '" . $total_ordered_array["item_id"] . "'";
            $products = mysqli_query($dbc, $products_query);

            //outputs items details
            echo '<p>';
            while ($products_array = mysqli_fetch_array($products, MYSQLI_ASSOC)) {
                echo $products_array['item_name'] . "<br>" .
                    "<img src = './" . $products_array['item_img'] . "' style = 'width:10%;height:10%;'><br>";
            }

            echo $total_ordered_array['number_ordered'] . '</p>';
        }

        ?>
        <br>
    </div>
</div>
<?php
//includes footer
include("../includes/footer.html");
?>