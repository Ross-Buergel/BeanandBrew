<?php
$page_title = 'View Orders';
include('../includes/tall-header.php');

require('../includes/connect_db.php');

if ($_SESSION['user_id'] != "1") {
    header("Location: ../User-Accounts/login.php");
}
?>
<div class='standard-box'>
    <div class='centre-content'>

        <?php
        $graph_x_data = array();
        $graph_y_data = array();
        $x_data_map = array();


        $sql = 'SELECT * FROM tbl_orders ORDER BY collection_date';
        $result = $dbc->query($sql);

        $index = 0;
        $count = 0;

        while ($order = $result->fetch_assoc()) {
            $index++;

            // check if the date is already present in the $graph_x_data array
            if (isset($x_data_map[$order['collection_date']])) {
                $key = $x_data_map[$order['collection_date']];
                $graph_y_data[$key] += 1;
            } else {
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
        <h1 class="standard-box-title">View Orders</h1>
        <h2 class="standard-box-title">Number of Orders for Collection per Day</h2>
        <div style="padding-left:300px;padding-right:300px;">
            <canvas id="myChart" style="width:50%;"></canvas>
        </div>
        <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [<?php foreach ($graph_x_data as $label) {
                                    echo "'" . $label . "',";
                                } ?>],
                    datasets: [{
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
                    plugins: {
                        datalabels: {
                            color: '#36A2EB'
                        }
                    }
                }
            });
        </script>

        <h2 class="standard-box-title">Most Popular Items</h2>

        <?php
        $popular_drinks_query = "SELECT item_id, COUNT(*) AS c FROM tbl_order_contents GROUP BY item_id ORDER BY c DESC";
        $popular_drinks = mysqli_query($dbc, $popular_drinks_query);

        while ($popular_drinks_array = mysqli_fetch_array($popular_drinks, MYSQLI_ASSOC)) {
            $product_query = "SELECT * FROM tbl_shop
            WHERE item_id = '" . $popular_drinks_array["item_id"] . "'";
            $product = mysqli_query($dbc, $product_query);

            echo '<p>';
            while ($product_array = mysqli_fetch_array($product, MYSQLI_ASSOC)) {
                echo $product_array['item_name'] . "<br>" .
                    "<img src = './" . $product_array['item_img'] . "' style = 'width:10%;height:10%;'><br>";
            }

            echo $popular_drinks_array['c'] . '</p>';
        }
        ?>
        <h2 class="standard-box-title">Amount Earnt Over Time</h2>

        <?php
        $date = date("Y-m-d");

        $day_query = "SELECT * FROM tbl_orders
            WHERE order_date >= NOW() - INTERVAL 1 DAY";
        $day = mysqli_query($dbc, $day_query);

        $day_total = 0;

        while ($day_array = mysqli_fetch_array($day, MYSQLI_ASSOC)) {
            $day_total += $day_array['total'];
        }


        $seven_days_query = "SELECT * FROM tbl_orders
        WHERE order_date >= DATE(NOW()) - INTERVAL 7 DAY";
        $seven_days = mysqli_query($dbc, $seven_days_query);

        $seven_days_total = 0;

        while ($seven_days_array = mysqli_fetch_array($seven_days, MYSQLI_ASSOC)) {
            $seven_days_total += $seven_days_array['total'];
        }
        ?>

        <p>Past 24 Hours - £<?php echo $day_total; ?></p>
        <p>Past 7 Days - £<?php echo $seven_days_total; ?></p>

        <h2 class="standard-box-title">Orders</h2>

        <?php
        $query = "SELECT * FROM tbl_orders ORDER BY collection_date";
        $order = mysqli_query($dbc, $query);

        while ($order_array = mysqli_fetch_array($order, MYSQLI_ASSOC)) {

            $query = "SELECT * FROM tbl_order_contents
                WHERE order_id = '" . $order_array['order_id'] . "'";
            $order_contents = mysqli_query($dbc, $query);

            echo "<p>Order ID : " . $order_array['order_id'];

            while ($row = mysqli_fetch_array($order_contents, MYSQLI_ASSOC)) {
                $query = "SELECT * FROM tbl_shop
                    WHERE item_id = '" . $row['item_id'] . "'";
                $product = mysqli_query($dbc, $query);


                while ($product_array = mysqli_fetch_array($product, MYSQLI_ASSOC)) {
                    echo "<br>Item : " . $product_array["item_name"];
                }
            }

            echo "<br>Collection : " . $order_array['collection_date'] . " " . $order_array['collection_time'] . "<br></p>";
        }
        ?>

    </div>
</div>
<?php
include("../includes/footer.html");
?>