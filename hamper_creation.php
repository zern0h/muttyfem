<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] === "cashier"){
        header("location:login.php");
    }
    
    $title = 'Delivery | Muttyfem';
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
            <h3 class="title" style="margin-bottom: 30px">Hamper Creation</h3>
                
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Search by Product Name</label>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Product Code or Manufacturer Barcode</label>
                </div>
                <div class="col-md-5">
                    <select name="product_name_search" id="product_name_search" class="form-control selectpicker"  required>';
                        <?php echo $LObject->loadProduct(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="barcode_scan" id="barcode_scan" class="form-control"  placeholder="Scan Barcode" />
                </div>
              
               
              
                <div class="col-12">
                    <form action="" id="posForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Product Name</label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit</label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantity</label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit Price</label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Cost</label>
                            </div>     
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <input type="text" name="productName" id="productName" class="form-control disable" />
                                <input type="hidden" name="itemId" id="itemId" class="form-control" />  
                            </div>
                            <div class="col-md-2">
                            <input type="text" name="productUnit" id="productUnit" class="form-control disable" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="product_quantity" id="product_quantity" class="form-control"  placeholder="Input Quantity" />
                            </div>
        
                            <div class="col-md-2">
                                <input type="text" name="unitPrice" id="unitPrice" class="form-control disable"  placeholder="" />
                            </div>

                            <div class="col-md-2">
                                <input type="text" name="totalPrice" id="totalPrice"class="form-control disable"  placeholder="" disabled />
                            </div>

                            <div class="col-md-1">
                                <button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>
                            </div>
                        </div>
                    </form>
                    <form action="" id="checkOutForm">  
                         <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">HAMPER  PRICE </label>
                                <input type="text" name="hamper_price" id="hamper_price" class="
                                form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">HAMPER  QUANTITY </label>
                                <input type="text" name="hamper_qty" id="hamper_qty" class="
                                form-control">
                            </div>
                            <div class="col-md-4"> 
                                <label class="form-label">SUGGESTED HAMPER PRICE</label>
                                <input type="text" name="total_hamper_cost" id="total_hamper_cost" class="form-control disable">
                            </div> 
                        </div>
                          
                        <hr />
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Product Name</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Unit Price</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Cost</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total</label>
                                </div>
                            </div>
                            <span id="span_product_details">
                                
                            </span>
                        <hr />
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">HAMPER  NAME </label>
                                    <input type="text" name="hamper_name" id="hamper_name" class="
                                    form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION["user_id"]; ?>">
                                    <input type="hidden" name="btn_action" id="btn_action" class="btn btn-lg btn-info" value="Add" />
                                    <input style="margin-top: 30px" type="button" name="action" id="action" class="btn btn-info" value="Create Hamper" />   
                                </div>
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
                product_id: $('#itemId').val(),
                productName: $('#productName').val(),
                productUnit: $('#productUnit').val(),
                price : $('#unitPrice').val(),
                quantity : $('#product_quantity').val(),
                total : $('#totalPrice').val(),
            };

            add_product_row(count, product);
        });

        //Function to generate dropdowns and new option for product
        let total = 0;
        function add_product_row(count = '', product)
		{
            if( product.product_id == '' || product.quantity == '' || product.price == '' || product.total == '' ){
                swal({
                    title: "Danger!",
                    text: "Try to check for empty fields",
                    icon: "warning",
                });   
            }
            else{
                var html = '';
            
                html += '<span id="row'+count+'"><div class="row">';
                
                html +='<div class="form-group col-md-2">';
                    html += '<input type="text" name="product_name[]" id="product_name'+count+'" class="form-control disable"   value="'+product.productName+'">';
                    html += '<input type="hidden" name="product_id[]" id="product_id'+count+'" class="form-control disable" value="'+product.product_id+'">';
                html += '</div>';

                html += '<div class="col-md-2">';
                    html += '<input type="text" name="quantity[]" id="quantity'+count+'" class="form-control disable" value="'+product.quantity+'" " />';
                html += '</div>';

                html += '<div class="col-md-2">';
                    html += '<input type="text" name="unit[]" id="unit'+count+'" class="form-control disable" value="'+product.productUnit+'" " />';
                html += '</div>';
                
                html += '<div class="col-md-2">';
                    html += '<input type="text" name="pricePerUnit[]" id="pricePerUnit'+count+'" class="form-control disable" value="'+product.price+'" />';
                html += '</div>';

                html += '<div class="col-md-2">';
                    html += '<input type="text" name="itemTotalPrice[]" id="itemTotalPrice'+count+'"class="form-control disable" value="'+product.total+'" />';
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
                total += Number(product.total);
                $('#total_hamper_cost').val(total.toFixed(2));
                $("#checkOutValue").val(total.toFixed(2));
                $('#posForm')[0].reset();
            }	
		}

        var count = 0;
        
        // function to remove rows
        $(document).on('click', '.remove', function(){
            var row_no = $(this).attr("id");
            let  product_total_cost = $(this).data('total');
            total -= product_total_cost;
            $('#total_hamper_cost').val(total.toFixed(2));  
            $("#checkOutValue").val(total.toFixed(2));
            $('#row'+row_no).remove();
        });

        //fetch product with drop down
        $(document).on('change', '#product_name_search',function(){
           let btn_action = 'fetch_product';
           let product_number = $(this).val();
           $.ajax({
                url:"hamper_creation_action.php",
                method:"POST",
                data:{product_number:product_number,btn_action:btn_action},
                dataType: "JSON",
                success:function(data){
                    $('#itemId').val(data.product_id);
                    $('#unitPrice').val(data.product_cost);
                    $('#productName').val(data.product_name);
                    $('#productUnit').val(data.product_unit);
                    $('#product_name_search').prop('selectedIndex', 0);
                }

           });
        });

        $('#barcode_scan').on('keyup', function(){
            let btn_action = 'fetch_by_barcode';
            let product_number = $('#barcode_scan').val();
            $.ajax({
                url:"hamper_creation_action.php",
                method:"POST",
                data:{product_number:product_number,btn_action:btn_action},
                dataType: "JSON",
                success:function(data){
                    $('#itemId').val(data.product_id);
                    $('#unitPrice').val(data.product_cost);
                    $('#productName').val(data.product_name);
                    $('#productUnit').val(data.product_unit);
                    $('#barcode_scan').val('');
                }

           });
        });

        //Calculate Total based on prices
        $(function(){
            $("#unitPrice, #product_quantity").on("keydown keyup", qty);
            function qty(){
                let sum = (
                    Number($("#unitPrice").val()) * Number($("#product_quantity").val())
                );
               $('#totalPrice').val(sum.toFixed(2));
            }
        });

        //Submit Checkout Form
        $('#action').click(function(event){
			event.preventDefault();
            
            form_data = $("#checkOutForm").serialize(); 
		    total = 0;
            if($('#hamper_price').val() == '' || $('#hamper_qty').val() == '' || $('#total_hamper_cost').val() == '' || $('#hamper_name').val() == ''){
                 swal({
                    title: "Danger!",
                    text: "Hamper Price or Hamper Quantity or Hamper Name Cannot be empty",
                    icon: "warning",
                });   
            }else{
                $.ajax({
                    url:"hamper_creation_action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"JSON",
                    success:function(data){
                        $('#checkOutForm')[0].reset();
                        $('#span_product_details').empty();
                        $('#total_hamper_cost').val(total.toFixed(2));
                        $("#checkOutValue").val(total.toFixed(2));
                        swal({
                            title: "Success!",
                            text: data.success,
                            icon: "success",
                        });                      
                    }
                });
            }  	
		});

        //Reset Form
        $('#reset').click(function(){
            total = 0;
            $('#checkOutForm')[0].reset();
            $('#span_product_details').empty();
            $('#total_hamper_cost').val(total.toFixed(2));
         
            $("#checkOutValue").val(total.toFixed(2));
            $('#print_receipt').addClass("hidden-button"); 
        });
    </script>

</body>
</html>