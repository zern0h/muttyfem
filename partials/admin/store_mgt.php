<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    
    if($_SESSION['type'] === "Cashier" || $_SESSION['type'] === "Accountant"){
        header("location:login.php");
    }
    $title = 'Store MGT | Muttyfem Supermarket ';
    include 'partials/header.php';

    include 'partials/Loader.php';
    $LObject = new Loader;
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
            <h3 class="title">Store Management</h3>
            <!-- Store_nav -->
                <?php include 'partials/store_mgt_nav.php'; ?>
            <!-- End Store_nav -->

            <!-- Info -->
            <div class="row inst">
                <h3 class="text-center">To avoid embarking on the wrong operation read the instrunction below</h3>
                <ul class="instruction">
                    <li><span>Purchase Order:</span> This page is meant for raising purchase order and invoice for items newly purchased into the store </li>
                    <li><span>Shelf Product:</span> This page is meant for creating new items to be added to the shelf on the supermarket floor</li>
                    <li><span>Stock Product:</span> This page is used in creating new product lines for the supermarket</li>
                    <li><span>Stock Management:</span> This page handles taking out products from the store to the supermarket floor</li>
                    <li><span>Suppliers:</span> This page handles the creation of new suppliers to the supermarket</li> 
                </ul>
            </div>
            <!-- Info End -->
        </div>
    <!-- End Content -->

    
    

    <!-- Scripts-->
    <?php include 'partials/scripts.php'; ?>
    
</body>
</html>