<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Report | MUTTYFEM SUPERMARKET';
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
            <?php include 'partials/report_nav.php'; ?>
            <div class="row">
                <div id="piechart-amount" style="width: 640px; height: 500px;"></div>
                <div id="piechart-qty" style="width: 640px; height: 500px;"></div>
            </div>

         
        </div>
    <!-- End Content -->

    <?php include 'partials/scripts.php';?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script>
        google.charts.load('current', {
            'packages': ['corechart'],
            'mapsApiKey': ''   // here you can put you google map key
        });
       

        //google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Product', 'Sales'],
                    <?php
                       $sql = "SELECT inventory_price,inventory_product_id, SUM(inventory_total_price) as TptalSum, products.product_name as product_name from inventory_order_product LEFT JOIN products ON products.product_id = inventory_order_product.inventory_product_id WHERE date(inventory_order_date) = CURRENT_DATE() GROUP BY inventory_product_id ORDER BY TptalSum DESC ";
                        $result = $Qobject->select($sql);
                        $count = $Qobject->table_row_count($sql);
                        if ($count > 0){
                            foreach ($result as $row){
                                echo "['".$row['product_name']."',".$row['TptalSum']."],";
                            }
                        }
                  
                    ?>
            ]);
            var options = {
            title: 'Daily Sales in Naira'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart-amount'));

            chart.draw(data, options);
        }

        //google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartQty);

        function drawChartQty() {

            var data = google.visualization.arrayToDataTable([
                ['Product', 'Quantity'],
                    <?php
                       $sql = "SELECT inventory_price,inventory_product_id,  SUM(inventory_quantity) as ToalQty, products.product_name as product_name from inventory_order_product LEFT JOIN products ON products.product_id = inventory_order_product.inventory_product_id WHERE date(inventory_order_date) = CURRENT_DATE() GROUP BY inventory_product_id ORDER BY ToalQty DESC ";
                        $result = $Qobject->select($sql);
                        $count = $Qobject->table_row_count($sql);
                        if ($count > 0){
                            foreach ($result as $row){
                                echo "['".$row['product_name']."',".$row['ToalQty']."],";
                            }
                        }
                  
                    ?>
            ]);
            var options = {
            title: 'Daily Sales Quantity'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart-qty'));

            chart.draw(data, options);
        }

    </script>
</body>
</html>