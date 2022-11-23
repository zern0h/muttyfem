<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    
    if($_SESSION['type'] === "Cashier" || $_SESSION['type'] === "Accountant"){
        header("location:login.php");
    }
    $title = 'Stock Management | Muttyfem Supermarket ';
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
            <h3 class="title">Stock Management</h3>
            <input type="hidden" name="storekeeper_id" id="storekeeper_id" value="<?= $_SESSION['user_id'] ?>">
            <div class="row table-area">
                <div class="col-12 col-m-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                        <div class="row">
                                <div class="col-lg-8">
                                    <h3>
                                        Stock Management Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#stockManagementModal" id="stockManagementInput">
                                        Stock Shelf <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-content">
                            <table id="shelf_management_table"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Store Keeper</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                               
                            </table>
                        </div>
                    </div>
                </div>
             
            </div>
       
        </div>
    <!-- End Content -->

    
    <!-- stock Management Modal -->
    <div class="modal fade" id="stockManagementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stockManagementModalLabel" aria-hidden="true">
        <div class=" modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockManagementModalLabel">Stock Management Modal</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <h3>Enter Product To Be Added to Shelf</h3>
                          
                        </div>

                        <div class="col-12">
                            <form action="" id="posForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Select Product</label>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Product Name</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Unit</label>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Quantity</label>
                                    </div>
                                
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <select name="product" id="product" class="form-control"  required>';
                                          
                                            <?php echo $LObject->stockProducts(); ?>
                                        </select>
                                        <input type="hidden" name="quantity" id="quantity" class="form-control disable" />
                                    </div>

                                    <div class="col-md-3">
                                        <input type="text" name="productName" id="productName" class="form-control disable" />
                                    </div>

                                    <div class="col-md-2">
                                        <input type="text" name="productUnit" id="productUnit" class="form-control disable"  />
                                    </div>

                                    <div class="col-md-2">
                                        <input type="text" name="product_quantity" id="product_quantity" class="form-control"  placeholder="Input Quantity" />
                                    </div>

                                    
                                    <div class="col-md-2">
                                        <button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">Add Item</button>
                                    </div>
                                </div>
                            </form>
                            <form action="" id="checkOutForm">  
                                <hr />
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Product Name</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Product Unit</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Quantity</label>
                                        </div>
                                    </div>
                                    <span id="span_product_details">
                                    
                                    </span>
                                <hr />
                                    
                                    <div class="col-md-12">
                                        <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION["user_id"]; ?>">
                                        <input type="hidden" name="btn_action" id="btn_action" class="btn btn-lg btn-info" value="Add" />
                                      
                                    </div>
                               
                            
                        </div>
                    </div>   
                </div>
                <div class="modal-footer"> 
                        <input type="submit" name="action" id="action" class="btn btn-lg btn-info" value="Check Out" />
                    </form>
                    <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Stock Management Modal -->

    <!-- Scripts-->
    <?php include 'partials/scripts.php'; ?>
    <script>

        //load table 
        $(document).ready(function(){
            
            let btn_action = 'load_table';
            let user_id = $('#storekeeper_id').val()
            shelf_management_table = $('#shelf_management_table').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"stock_management_action.php",
                    type:"POST",
                    data:{user_id:user_id, btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });
        //closing modal and flushing form
        $('#stockManagementInput, .close').click(function(){
            $('#checkOutForm')[0].reset();
            $('#span_product_details').empty();
        })
         //function to add more rows
         $(document).on('click', '#add_more', function(){
            count = count + 1;
            let product = {
                product_id: $('#product').val(),
                overallQuantity: $('#quantity').val(),
                productName: $('#productName').val(),
                productUnit: $('#productUnit').val(),
                quantity : $('#product_quantity').val(),
            };

            add_product_row(count, product);
        });

        //Function to generate dropdowns and new option for product
        function add_product_row(count = '', product)
		{
            if( product.product_id == '' || product.quantity == '' ){
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
                    html += '<input type="hidden" name="overallQuantity[]" id="overallQuantity'+count+'" class="form-control disable" value="'+product.overallQuantity+'">';
                html += '</div>';

                html += '<div class="col-md-2">';
                    html += '<input type="text" name="unit[]" id="unit'+count+'" class="form-control disable" value="'+product.productUnit+'" " />';
                html += '</div>';

                html += '<div class="col-md-2">';
                    html += '<input type="text" name="quantity[]" id="quantity'+count+'" class="form-control disable" value="'+product.quantity+'" " />';
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

        //fetch product details
        $(document).on('change', '#product',function(){
           let btn_action = 'fetch_product';
           let product_number = $('#product').val();
           $.ajax({
                url:"stock_management_action.php",
                method:"POST",
                data:{product_number:product_number,btn_action:btn_action},
                dataType: "JSON",
                success:function(data){
                    
                    $('#productName').val(data.stock_product_name);
                    $('#productUnit').val(data.stock_product_unit);
                    $('#quantity').val(data.stock_product_quantity);
                }

           });
        });

        //Submit Checkout Form
          
        $(document).on('submit', '#checkOutForm', function(event){
			event.preventDefault();
            
            form_data = $("#checkOutForm").serialize(); 
			$.ajax({
				url:"stock_management_action.php",
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

				}
			});
		});
    </script>
</body>
</html>