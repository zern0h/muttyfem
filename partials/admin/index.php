<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    /*if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }*/
    
    $title = 'Dashboard | Clover Cuties';
    include 'partials/header.php'
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
<?php
    if($_SESSION['type'] === "Super_Admin"){
        
?>
        <div class="wrapper" style="margin-top: 20px; margin-left: 10px;">
            <div class="row">
                
                <div class="col-md-3 col-sm-6">
                    <div class="counter products bg-warning">
                        <p>
                            <i class="fas fa-trademark"></i>
                        </p>
                        <h3></h3>
                        <p>Total Products</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="counter suppliers bg-success">
                        <p>
                            <i class="fas fa-truck-loading"></i>
                        </p>
                        <h3></h3>
                        <p>Total Suppliers</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="counter categories  bg-danger">
                        <p>
                            <i class="fas fa-bars"></i>
                        </p>
                        <h3></h3>
                        <p>Categories</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="counter sub-categories  bg-primary">
                        <p>
                            <i class="fas fa-sort-down"></i>
                        </p>
                        <h3></h3>
                        <p>Sub Categories</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-header">
                            <h3>
                                Recent Sales 
                            </h3>
                          
                        </div>
                        <div class="card-content invoice-content">
                            
                        </div>
                        <a href="orders.php" class="btn btn-success">View All</a>
                    </div>
                </div>

                <div class="col-sm-3 row">
                    <div class="col-md-12">
                        <div class="counter sales  bg-danger">
                            <p>
                                <i class="fas fa-coins"></i>
                            </p>
                            <h3></h3>
                            <p>Total Sales</p>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="counter daily-sales bg-success">
                            <p>
                                <i class="fas fa-receipt"></i>
                            </p>
                            <h3></h3>
                            <p>Daily Sales</p>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="counter monthly-sales bg-primary">
                            <p>
                                <i class="fas fa-chart-line"></i>
                            </p>
                            <h3></h3>
                            <p>Monthly Sales</p>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-header">
                            <h3>
                                Recent Purchase Order 
                            </h3>
                          
                        </div>
                        <div class="card-content table-content">
                            
                        </div>
                        <a href="pod.php" class="btn btn-success">View All</a>
                    </div>
                </div>

                <div class="col-sm-3 row">
                    <div class="col-md-12 ">
                        <div class="counter total-pod bg-primary">
                            <p>
                                <i class="fas fa-money-bill"></i>
                            </p>
                            <h3></h3>
                            <p>Total Delivery</p>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="counter total-paid-delivery bg-success">
                            <p>
                                <i class="fas fa-money-check"></i>
                            </p>
                            <h3></h3>
                            <p>Total Paid Order</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="counter outstanding bg-danger">
                            <p>
                                <i class="fas fa-credit-card"></i>
                            </p>
                            <h3></h3>
                            <p>Total Outstanding</p>
                        </div>
                    </div>
                </div>
            </div>


          
        </div>
    <!-- End Content -->

<?php } ?>
   <?php include 'partials/scripts.php'; ?>
   
   
    <script>
        $(document).ready(function(){
            checkProducts();
            checkSuppliers();
            checkCategories();
            checkSubCategories();
            checkTotalTransaction();
            checkDailySales();
            checkMonthlySales();
            checkTotalDelivery();
            checkTotalPaidProofOfDelivery();
            checkOutstanding();
            getrecentSales();
            getRecentPurchaseOrder();
        });
        setInterval(function(){
            checkProducts();
            checkSuppliers();
            checkCategories();
            checkSubCategories();
            checkTotalTransaction();
            checkDailySales();
            checkMonthlySales();
            checkTotalDelivery();
            checkTotalPaidProofOfDelivery();
            checkOutstanding();
            getrecentSales();
            getRecentPurchaseOrder();
        }
        ,10* 60 * 1000); //
        
        function checkSuppliers(){
            
            let  btn_action = 'check suppliers';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.suppliers h3').html(data); 
                 }
             }); 
        }

        function checkProducts(){
            
            let  btn_action = 'check products';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.products h3').html(data); 
                 }
             }); 
        }

        function checkCategories(){
            
            let  btn_action = 'check categories';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.categories h3').html(data); 
                 }
             }); 
        }

        function checkSubCategories(){
            
            let  btn_action = 'check sub_categories';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.sub-categories h3').html(data); 
                 }
             }); 
        }

        function checkTotalTransaction(){
            
            let  btn_action = 'check total transaction';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.sales h3').html(data); 
                 }
             }); 
        } 

        function checkDailySales(){
            
            let  btn_action = 'check daily sales';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.daily-sales h3').html(data); 
                 }
             }); 
        }

        function checkMonthlySales(){
            
            let  btn_action = 'check monthly sales';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.monthly-sales h3').html(data); 
                 }
             }); 
        }

        function checkTotalDelivery(){
            
            let  btn_action = 'check total pod';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.total-pod h3').html(data); 
                 }
             }); 
        }

        function checkTotalPaidProofOfDelivery(){
            
            let  btn_action = 'check total paid delivery';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.total-paid-delivery h3').html(data); 
                 }
             }); 
        }
        
        function checkOutstanding(){
            
            let  btn_action = 'check outstanding';
            $.ajax({
                 method: "POST",
                 url: "dashboard_action.php",
                 data: {btn_action:btn_action},
                 success:function(data){
                    $('.outstanding h3').html(data); 
                 }
             }); 
        }

        function getRecentPurchaseOrder(){
            let btn_action = 'get pod';
            $.ajax({
                method: "POST",
                url: "dashboard_action.php",
                data:{btn_action:btn_action},
                success:function(data){
                    
                    $('.table-content').html(data);
                    
                }
            });
        }

        function getrecentSales(){
            let btn_action = 'get sales';
            $.ajax({
                method: "POST",
                url: "dashboard_action.php",
                data:{btn_action:btn_action},
                success:function(data){
            
                    $('.invoice-content').html(data);
                  
                }
            });
        }
    </script>

</body>
</html>