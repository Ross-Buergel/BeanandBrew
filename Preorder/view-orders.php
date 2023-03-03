<?php
$page_title='View Orders';
include('../includes/header.php');

require('../includes/connect_db.php');

if ($_SESSION['user_id'] != "1")
{
    header("Location: ../User-Accounts/login.php");
}

echo "<div class = 'standard-box'><div class = 'centre-content'>";

$query = "SELECT * FROM tbl_orders";
$order = mysqli_query($dbc,$query);

$graph_x_data = [];
$graph_y_data = [];
$number_of_collections = 0;
$previous_date = "";

while ($order_array = mysqli_fetch_array($order, MYSQLI_ASSOC)) {
    if ($previous_date != ""){
        if ($previous_date == $order_array['collection_date']){
            $number_of_collections += 1;
        }
        else
        {
            $graph_x_data[] = $previous_date;
            $graph_y_data[] = $number_of_collections;
            $number_of_collections = 0;
        }
    }
    else
    {
        $number_of_collections += 1;
    }
    $previous_date = $order_array['collection_date'];
}

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'line',
    data: {
    labels: [<?php foreach ($graph_x_data as $label) {
        echo "'" . $label . "',";
    } ?>],
    datasets: [{
    label: 'Score',
    data: [<?php foreach ($graph_y_data as $data) {
        echo "'" . $data . "',";
    } ?>],
    borderWidth: 3,
    borderColor: "#00643C"
}]
                    },
    options: {
    scales: {
    y: {
        beginAtZero: true
    }
    }
}
                });
</script>
<?php

echo "<p>";
while ($order_array = mysqli_fetch_array($order, MYSQLI_ASSOC)) {

    $query = "SELECT * FROM tbl_order_contents
    WHERE order_id = '".$order_array['order_id']."'";
    $order_contents = mysqli_query($dbc, $query);

    echo "Order ID : ".$order_array['order_id'];

    while ($row = mysqli_fetch_array($order_contents, MYSQLI_ASSOC)) {
        $query = "SELECT * FROM tbl_shop
        WHERE item_id = '".$row['item_id']."'";
        $product = mysqli_query($dbc, $query);


        while ($product_array = mysqli_fetch_array($product, MYSQLI_ASSOC)) {
            echo "<br>Item : ".$product_array["item_name"];
        }

    }

    echo "<br>Collection : ".$order_array['collection_date']." ".$order_array['collection_time'];
}

echo "</p></div></div>";

include("../includes/footer.html");
?>