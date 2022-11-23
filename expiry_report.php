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
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Current Qty</th>
                                    <th>POD Date</th>
                                    <th>EXP Date</th>
                                    <th>EXP - POD </th>
                                    <th>Day to Expiry Date</th>
                                </thead>
                                <tbody >
                                <?php
                                    $query = "
                                       SELECT product_name, pod_product_quantity, date(pod_date) as podd, exp_date, DATEDIFF(exp_date, date(pod_date) ) as date_diff, DATEDIFF(exp_date, CURRENT_DATE) as date_to_exp FROM `proof_of_delivery_products` INNER JOIN products on products.product_id = proof_of_delivery_products.pod_rec_prod_id WHERE DATEDIFF(exp_date, CURRENT_DATE) <= 30
                                    ";
                                    $result = $DBobject->select($query);
                                    $count = $DBobject->table_row_count($query);
                                    if($count > 0)
                                    {
                                        $id = 1;
                                        foreach( $result as $product){
                                            $date = '';
                                            if($product["date_to_exp"] >= 30  || $product["date_to_exp"] > 15){
                                                $date = '<span class="text text-warning">'.$product["date_to_exp"].'</span>';
                                            }
                                            else if($product["date_to_exp"] <= 15 ){
                                                $date = '<span class="text text-danger">'.$product["date_to_exp"].'</span>';
                                            }

                                            echo '<tr>
                                                    <td>'.$id++.'</td>
                                                    <td>'.$product["product_name"].'</td>
                                                    <td>'.$product["pod_product_quantity"].'</td>
                                                    <td>'.$product["podd"].'</td>
                                                    <td>'.$product["exp_date"].'</td>
                                                    <td>'.$product["date_diff"].'</td>
                                                    <td>'.$date.'</td>
                                                </tr>';
                                        }
                                    }
                                    else{
                                            echo '<tr>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td></td>
                                                    <td>-</td>
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
                        <a href="printExpiryReport.php" target="_blank" id="print_receipt" class="btn btn-success">Generate Expiry Date Report</a>
                        
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