<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Daily Report | MUTTYFEM SUPERMARKET';
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
        <div class="wrapper">
            <?php include 'partials/report_nav.php'; ?>
            <div class="row">
               
                
                <div class="tabbed" >
                    <input type="radio" name="tabs" id="tab_one" checked>
                    <label for="tab_one">Daily Sales Report</label>

                    <div class="tabs">
                        <div>
                            <h2>Daily Sales Report</h2>
                            <input type="date" placeholder="yyyy-mm-dd" name="dailySalesDate" id="dailySalesDate">

                            <a href="" target="_blank" class="btn btn-success btn-xs dailySalesReport" id="dailySalesReport" >Generate Sales Report</a>
                           
                        </div>
   
                    </div>
                </div>	
                
                
            </div>

         
        </div>
    <!-- End Content -->

    <script src="js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    
    <script src="js/main.js"></script>
    <script>

        $(document).on('click', '.dailySalesReport', function(e){
            let url ="dailySalesReport.php?date=";
            url += $('#dailySalesDate').val();
            $(this).attr("href", url);
        });
           
    </script>
</body>
</html>