<?php
//include "config.php";

$last_id = 'SUP0000001';
$num = substr($last_id, 3);
echo $num;

$srtring = "<li class='sidebar-nav-item'>
<a href='brands sphp' class='sidebar-nav-link'>
    <div>
        <i class='fas fa-box'></i>
    </div>
    <span>Brand</span>
</a>
</li>";
?>
<!--html>
    <head>
        <title>Generate Line Chart in PHP</title>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <style>
            body{
                background: #ccc;
                
            }
            
            #my-chart{
                background: #fff;
                padding: 20px;
            }
            
        </style>
    </head>
    <body>

        <div id="my-chart" style="width: 100%; height: 400px;"></div>
        <script type="text/javascript">
           /* google.charts.load('current', {
                'packages': ['corechart'],
                'mapsApiKey': ''   // here you can put you google map key
            });
            google.charts.setOnLoadCallback(drawRegionsMap);

            function drawRegionsMap() {
                var data = google.visualization.arrayToDataTable([
                    ['Year', 'Sales', 'Expenses'],
                     <?php
                     /*$chartQuery = "SELECT * FROM my_chart";
                     $chartQueryRecords = mysqli_query($con, $chartQuery);
                        while($row = mysqli_fetch_assoc($chartQueryRecords)){
                            echo "['".$row['year']."',".$row['sales'].",".$row['expenses']."],";
                        }*/
                     ?>
                ]);

                var options = {
                   
                };

                var chart = new google.visualization.LineChart(document.getElementById('my-chart'));
                chart.draw(data, options);
            }


            google.charts.setOnLoadCallback(drawRegionsMap);

function drawRegionsMap() {
    var data = google.visualization.arrayToDataTable([
        ['Element', 'Density', { role: 'style' }],
            <?php
                /*$sql = "SELECT * FROM my_chart";
                $result = $Qobject->select($sql);
                $count = $Qobject->table_row_count($sql);
                if ($count > 0){
                    foreach ($result as $row){
                        echo "['".$row['year']."',".$row['sales'].",".$row['expenses']."],";
                    }
                }
                $query = "SELECT SUM(inventory_total_price) as sales FROM inventory_order_product WHERE date(inventory_order_date) = '2020-12-12'";
                $result = $Qobject->select($query);
                $count = $Qobject->table_row_count($query);
               
                $total = 0;
                foreach ($result as $row => $inventory) {
                    $total += $inventory["sales"];
                   
                }
                echo "['Sales',".$total.",'green'],";
                $query2 = "SELECT SUM(procurement_amount) as procure FROM procurements WHERE date(procurement_date) = '2021-07-12'";
                $result2 = $Qobject->select($query2);
                $count2 = $Qobject->table_row_count($query2);
                $total_expen = 0;
                $total_pro = 0;

                if($count2 > 0){
                    foreach ($result2 as $row => $procurement) {
                        $total_pro += $procurement["procure"];
                    }
                }

                $query3 = "SELECT SUM(`purchase_order_overview_total`) as purchase_total FROM purchase_order_overview WHERE date(`purchase_order_creation_time`) = '2021-07-19'";
                $result3 = $Qobject->select($query3);
                $count3 = $Qobject->table_row_count($query3);
              
                $total_purchase = 0;

                if($count3 > 0){
                    foreach ($result3 as $row => $procurement) {
                        $total_purchase += $procurement["purchase_total"];
                    }
                }
                $total_expen = $total_pro + $total_purchase;
                $profit =   $total - $total_expen ;
                echo "['".$total."',".$total_expen.",".$profit."],";
            */?>
    ]);

    /*var options = {
        
    };

    var chart = new google.visualization.BarChart(document.getElementById('my-chart'));
    chart.draw(data, options);
   
   
}  */
        <--/script>
    </head>
</body>
</html-->
