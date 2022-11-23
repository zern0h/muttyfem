<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Periodic Report | MUTTYFEM SUPERMARKET';
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
                    <label for="tab_one">Periodic Sales Report</label>
                   
                    <div class="tabs">

                        <div>
                            <h2>Periodic Sales Report</h2>
                            <form action="periodicSalesReport.php" method="POST">
                                 <label for="firstDate">First date</label>
                                <input type="date" name="firstDate" id="firstDate">
                                <label for="lastDate">Last date</label>
                                <input type="date" name="lastDate" id="lastDate">
                                 <input type="hidden" name="generatedBy" id="generatedBy" value=" <?= $_SESSION["user_name"]; ?>" >
                                <input type="submit" class="btn btn-success btn-xs" value="Generate Periodic Sales Report">
                                
                            </form>
                           
                            
                           
                          
                        </div>

                       
                    
                    </div>
                </div>	
                
                <div class="table">
                   
                </div>
            </div>

         
        </div>
    <!-- End Content -->

    <script src="js/jquery-3.5.1.min.js"></script>
    
    
    <script src="js/main.js"></script>
  
</body>
</html>