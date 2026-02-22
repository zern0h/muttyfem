<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    
  if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Hamper Price Label | Muttyfem Supermarket ';
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
            <h3 class="title" style="margin-bottom: 30px">Print Batch Price Stickers Sales</h3>
           <div class="row">
              
               
                <div class="col-md-6">
                    <label class="form-label">Load Hamper By Name</label>
                </div>
               
                <div class="col-md-6">
                    <select name="product_name_search" id="product_name_search" class="form-control selectpicker"  required>';
                       <?php echo $LObject->loadHamper(); ?>
                    </select>
                </div>
               
               
               
              
                <div class="col-12">
                    <form action="hamper_batch_barcode_generator.php" method="POST" id="Individual Hamper Form">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Hamper Name</label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hamper Code</label>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hamper Price</label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">QTY</label>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="form-group col-md-5">
                                <input type="text" name="productName" id="productName" class="form-control disable" />
                                <input type="hidden" name="productId" id="productId" class="form-control" />  
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="hamperCode" id="hamperCode" class="form-control disable" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="hamperPrice" id="hamperPrice" class="form-control disable" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="quantity" id="quantity" class="form-control " />
                            </div>
        
                            <div class="col-md-1">
                                <input type="submit" name="add_more" id="add_more" class="btn btn-success btn-xs" value="Print">
                            </div>
                        </div>
                    </form>
                   

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
           let btn_action = 'fetch_for_print';
           let product_number = $(this).val();
           $.ajax({
                url:"hamper_creation_action.php",
                method:"POST",
                data:{product_number:product_number,btn_action:btn_action},
                dataType: "JSON",
                success:function(data){
                    $('#productName').val(data.hamper_name);
                    $('#productId').val(data.hamper_overview_id);
                    $('#hamperCode').val(data.hamper_code);
                    $('#hamperPrice').val(data.hamper_total_cost);
                    $('#product_name_search').prop('selectedIndex', 0);
                }

           });
        });

      
       
    </script>

</body>
</html>