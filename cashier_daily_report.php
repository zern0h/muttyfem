<?php
session_start();
if (!isset($_SESSION['type'])) {
    header("location:login.php");
}
if ($_SESSION['type'] !== "Super_Admin") {
    header("location:login.php");
}
$title = 'Cashier Daily Report | MUTTYFEM SUPERMARKET';
include 'partials/header.php';
$Qobject = new Query;

?>



<body class="overlay-scrollbar">

    <div class="loading-screen">
        <div class="loading">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Navbar -->
    <?php include 'partials/nav.php' ?>
    <!-- End Navbar -->

    <!-- Sidebar -->
    <?php include 'partials/sidebar.php' ?>
    <!-- End Sidebar -->

    <!-- Content -->
    <div class="wrapper">

        <div class="row mt-5">
            <div id="piechart-amount" style="width: 640px; height: 500px;"></div>
            <div id="piechart-qty" style="width: 640px; height: 500px;">
                <table class="table table-responsive">
                    <thead>
                        <td>Cashier Name</td>
                        <td>Amount</td>
                    </thead>
                    <tbody>
                         <?php
                            $sql = "SELECT sum(inventory_order_total) as total, user_name from inventory_overview INNER JOIN users on users.user_id = inventory_overview.cashier_id WHERE date(inventory_order_created_date) =  CURRENT_DATE  GROUP BY cashier_id ORDER BY total;
                            ";
                            $result = $Qobject->select($sql);
                            $count = $Qobject->table_row_count($sql);
                            if ($count > 0) {
                                foreach ($result as $row) {
                                    echo '<tr> 
                                        <td>'.$row['user_name'].'</td>
                                        <td>'.$row['total'].'</td>
                                    </tr>';
                                }
                            }
                            else
                            {
                                echo '<tr>
                                    <td>None</td>
                                    <td>None</td>
                                </tr>';
                            }

                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>


    </div>
    <!-- End Content -->

    <?php include 'partials/scripts.php'; ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        google.charts.load('current', {
            'packages': ['corechart'],
            'mapsApiKey': '' // here you can put you google map key
        });


        //google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Cashier', 'Sales'],
                <?php
                $sql = "SELECT sum(inventory_order_total) as total, user_name from inventory_overview INNER JOIN users on users.user_id = inventory_overview.cashier_id WHERE date(inventory_order_created_date) =    CURRENT_DATE GROUP BY cashier_id ORDER BY total;
 ";
                $result = $Qobject->select($sql);
                $count = $Qobject->table_row_count($sql);
                if ($count > 0) {
                    foreach ($result as $row) {
                        echo "['" . $row['user_name'] . "'," . $row['total'] . "],";
                    }
                }

                ?>
            ]);
            var options = {
                title: 'Daily Sales by Cashiers Naira'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart-amount'));

            chart.draw(data, options);
        }
    </script>
</body>

</html>