<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Article Doc List | Muttyfem Supermarket ';
    include 'partials/header.php';

    
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
        <div class="wrapper" style="margin-left: 30px; margin-top: 30px;">
            <h3 class="title" style="margin-bottom: 30px"> Low Stock Report</h3>
            <div class="row">
                <div class="col-12">
                   
                    <hr />
                    
                        <span id="span_product_details">
                            <table class="table table-responsive table-stripped">
                                <thead>
                                    <th>Product Name</th>
                                    <th>Current Qty</th>
                                    
                                </thead>
                                <tbody >
                                <?php
                                    $query = "
                                        SELECT * FROM products WHERE recorded_level <= 5
                                    ";
                                    $result = $DBobject->select($query);
                                    $count = $DBobject->table_row_count($query);
                                    if($count > 0)
                                    {
                                        foreach( $result as $product){
                                            echo '<tr>
                                                    <td>'.$product["product_name"].'</td>
                                                    <td>'.$product["recorded_level"].'</td>
                                                </tr>';
                                        }
                                    }
                                    else{
                                            echo '<tr>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>';
                                    }
                                ?> 
                                </tbody>
                            </table>
                        </span>
                    <hr />
                    
                    <div class="col-md-6">
                        <a href="printLowStockReport.php" target="_blank" id="print_receipt" class="btn btn-success">Generate Low Stock Report</a>
                        
                    </div>
                        
                </div>
            </div>
       
        </div>
    <!-- End Content -->

    <!-- Scripts-->
    <?php include 'partials/scripts.php'; ?>
    <script>

        

    </script>

</body>
</html>