<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Yearly Report | MUTTYFEM SUPERMARKET';
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
                    <label for="tab_one">Yearly Sales Report</label>
                   
                    <div class="tabs">
                        
                        <div>
                            <h2>Yearly Sales Report</h2>
                            <div class="year-select col-4 col-m-12 col-sm-12">
                                <select name="year" id="year">
                                    <option  value="">Choose Year</option>
                                    <option  value="2020">2020</option>
                                    <option  value="2021">2021</option>
                                    <option  value="2022">2022</option>
                                    <option  value="2023">2023</option> 
                                </select>
                            </div> 

                            <a href="" target="_blank" class="btn btn-success btn-xs genYearlySales" id="genYearlySales" >Generate Yearly Sales Report</a>

                            
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
      
        $(document).on('click', '.genYearlySales', function(){
            let url ="yearlySalesReport.php?year=";
            url += $('#year').val();
            $(this).attr("href", url);
        });

    </script>
</body>
</html>