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
                            <form action="dailySalesReport.php"method="post">
                                 <input type="date" placeholder="yyyy-mm-dd" name="dailySalesDate" id="dailySalesDate">
                                 <input type="hidden" name="generatedBy" id="generatedBy" value=" <?= $_SESSION["user_name"]; ?>" >
                                <input type="submit" class="btn btn-success btn-xs" value="Generate Daily Sales Report">
                               
                            </form>
                           
                           
                        </div>
   
                    </div>
                </div>	
                
                
            </div>

         
        </div>
    <!-- End Content -->

    <script src="js/jquery-3.5.1.min.js"></script>
  
    <script src="js/main.js"></script>
    <script>

       
           
    </script>
</body>
</html>