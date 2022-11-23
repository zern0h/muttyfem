<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    
  if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Batch Price Label | Muttyfem Supermarket ';
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
                    <label class="form-label">Add by Product Name</label>
                </div>
                <div class="col-md-6">
                    <label class="form-label"> Add By Product Code or Manufacturer Barcode</label>
                </div>
                <div class="col-md-6">
                    <select name="product_name_search" id="product_name_search" class="form-control selectpicker"  required>';
                        <?php echo $LObject->loadProduct(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="barcode_scan" id="barcode_scan" class="form-control"  placeholder="Scan Barcode" />
                </div>
               
               
              
                <div class="col-12">
                    <form action="" id="posForm">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Product Code</label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Price</label>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="text" name="productName" id="productName" class="form-control disable" />
                                <input type="hidden" name="itemId" id="itemId" class="form-control" />  
                            </div>
                            <div class="col-md-3">
                            <input type="text" name="productBarcode" id="productBarcode" class="form-control disable" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="productPrice" id="productPrice" class="form-control disable" />
                            </div>
        
                            <div class="col-md-1">
                                <button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>
                            </div>
                        </div>
                    </form>
                    <form action="printBatchPriceSticker.php" method="POST" id="checkOutForm">  
                            <hr />
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Product Name</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Product Code</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Price </label>
                                </div>
                              
                            </div>
                                <span id="span_product_details">
                                   
                                </span>
                            <hr />
                           
                            <div class="col-md-3">
                                
                                 <input type="hidden" name="btn_action" id="btn_action" class="btn btn-lg btn-info" value="Add" />
                                <input type="submit" name="action" id="action" class="btn btn-info" value="Check Out" />
                              
                                
                            </div>
                            </form>

                            <div class="col-md-6">
                              
                                <button class="btn btn-danger" id="reset">Reset</button>
                            </div>
                        </div>
                   
            

                </div>
           </div>
       
        </div>
    <!-- End Content -->

    <!-- Scripts-->
    <?php include 'partials/scripts.php'; ?>
    <script>

         //function to add more rows
         $(document).on('click', '#add_more', function(){
            count = count + 1;
            let product = {
                productName: $('#productName').val(),
                productBarcode: $('#productBarcode').val(),
                productPrice : $('#productPrice').val(),
            };

            add_product_row(count, product);
        });

        //Function to generate dropdowns and new option for product
        let total = 0;
        function add_product_row(count = '', product)
		{
            if( product.productName == '' || product.productBarcode == '' || product.productPrice == '' ){
                swal({
                    title: "Danger!",
                    text: "Try to check for empty fields",
                    icon: "warning",
                });   
            }
            else{
                var html = '';
            
                html += '<span id="row'+count+'"><div class="row">';
                
                html +='<div class="form-group col-md-6">';
                    html += '<input type="text" name="product_name[]" id="product_name'+count+'" class="form-control disable"   value="'+product.productName+'">';
                html += '</div>';

                html += '<div class="col-md-3">';
                    html += '<input type="text" name="product_barcode[]" id="product_barcode'+count+'" class="form-control disable" value="'+product.productBarcode+'" " />';
                html += '</div>';

                html += '<div class="col-md-2">';
                    html += '<input type="text" name="product_price[]" id="product_price'+count+'" class="form-control disable" value="'+product.productPrice+'" " />';
                html += '</div>';
                
              

                html += '<div class="col-md-1">';
                if(count == '')
                {
                    html += '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
                }
                else
                {
                    html += '<button type="button" name="remove" data-total="'+product.total+'" id="'+count+'" class="btn btn-danger btn-xs remove">-</button>';
                }
                html += '</div>';
                html += '</div><br /></span>';
                $('#span_product_details').append(html);
               
                $('#posForm')[0].reset();
            }	
		}

        var count = 0;
        
        // function to remove rows
        $(document).on('click', '.remove', function(){
            var row_no = $(this).attr("id");
            $('#row'+row_no).remove();
        });

        //fetch product with drop down
        $(document).on('change', '#product_name_search',function(){
           let btn_action = 'fetch_product';
           let product_number = $(this).val();
           $.ajax({
                url:"batch_price_sticker_action.php",
                method:"POST",
                data:{product_number:product_number,btn_action:btn_action},
                dataType: "JSON",
                success:function(data){
                    $('#productPrice').val(data.product_cost);
                    $('#productName').val(data.product_name);
                    $('#productBarcode').val(data.product_barcode);
                    $('#product_name_search').prop('selectedIndex', 0);
                }

           });
        });

        //fetch with barcode scanner
        $('#barcode_scan').on('keyup', function(){
            let btn_action = 'fetch_by_barcode';
            let product_number = $('#barcode_scan').val();
            $.ajax({
                url:"batch_price_sticker_action.php",
                method:"POST",
                data:{product_number:product_number,btn_action:btn_action},
                dataType: "JSON",
                success:function(data){
                    $('#productPrice').val(data.product_cost);
                    $('#productName').val(data.product_name);
                    $('#productBarcode').val(data.product_barcode);
                    $('#barcode_scan').val('');
                }

           });
        });

       
        //Submit Checkout Form
       /* $('#action').click(function(event){
			event.preventDefault();
            
            form_data = $("#checkOutForm").serialize(); 
		    total = 0;
			$.ajax({
				url:"batch_price_sticker_action.php",
				method:"POST",
				data:form_data,
                dataType:"JSON",
				success:function(data){
                        $('#checkOutForm')[0].reset();
                        $('#span_product_details').empty();
                       
                        swal({
                            title: "Success!",
                            text: data.success,
                            icon: "success",
                        });
                       
                        $('#print_receipt').removeClass("hidden-button");    
				}
			});
		});*/

      

        //Reset Form
        $('#reset').click(function(){
            total = 0;
            $('#checkOutForm')[0].reset();
            $('#span_product_details').empty();
            $('#total_cost').val(total.toFixed(2));
            $('#cash_received').val(total.toFixed(2));
            $('#customer_change').val(total.toFixed(2));
            $("#checkOutValue").val(total.toFixed(2));
            $('#print_receipt').addClass("hidden-button"); 
        });
    </script>

</body>
</html>