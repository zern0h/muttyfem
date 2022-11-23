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
            <h3 class="title" style="margin-bottom: 30px"> Article Doc List</h3>
           <div class="row">
           
                <div class="col-md-6">
                    <label class="form-label">Search by Product Name</label>
                </div>
              
                <div class="col-md-5">
                    <select name="product_name_search" id="product_name_search" class="form-control selectpicker"  required>';
                        <?php echo $LObject->loadProduct(); ?>
                    </select>
                </div>

                <div class="col-12">
                   
                            <hr />
                            
                                <span id="span_product_details">
                                   <table class="table table-responsive table-stripped">
                                       <thead>
                                           <th>Product Name</th>
                                           <th>Action</th>
                                           <th>Former Qty</th>
                                           <th>Current Qty</th>
                                           <th>Action By</th>
                                           <th>Occured At</th>
                                       </thead>
                                       <tbody id="table_data">
                                           
                                       </tbody>
                                   </table>
                                </span>
                            <hr />
                            
                            
                            <div class="col-md-6">
                                <a href="" target="_blank" id="print_receipt" class="btn btn-success">Generate Article Report</a>
                              
                            </div>
                        </div>
                   
            

                </div>
           </div>
       
        </div>
    <!-- End Content -->

    <!-- Scripts-->
    <?php include 'partials/scripts.php'; ?>
    <script>

        //fetch product with drop down
        $(document).on('change', '#product_name_search',function(){
           let btn_action = 'fetch_product';
           let product_number = $(this).val();
           $.ajax({
                url:"article_doc_list_action.php",
                method:"POST",
                data:{product_number:product_number,btn_action:btn_action},
                success:function(data)
                {
                    $('#table_data').html(data);
                      $('#print_receipt').attr("href",'printArticleHistory.php?productNumber='+product_number);
                }
           });
        });

    </script>

</body>
</html>