<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Products | Clover Cuties';
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
        <div class="wrapper">
            <div class="row table-area">
                <div class="col-12 col-m-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                        <div class="row">
                                <div class="col-lg-8">
                                    <h3>
                                        Product Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productModal" id="productInput">
                                        Add Product <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-content">
                            <table id="product_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Vendor</th>
                                        <th>Recorded Level</th>
                                        <th>Selling Price</th>
                                        <th>Created On</th>
                                        <th>Status</th>
                                        <th>View</th>
                                        <th>Barcode</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                               
                            </table>
                        </div>
                    </div>
                </div>
             
            </div>
        </div>
    <!-- End Content -->
        <!--Add and Edit Modal -->
        <div class="modal fade" id="productModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Product Registration</h5>
                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="productForm" class="row g-3">
                            <h4 class="text text-danger">Ensure All Fields Are Filled</h4>
                            <div  id="gen-valid">
                                
                            </div>
                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Product Name</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-trademark"></i></span>
                                <input type="text"name="product_name" id="product_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Product Name">
                            </div>

                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Manufacturers Barcode</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-barcode"></i></span>
                                <input type="text"name="manufacturer_barcode" id="manufacturer_barcode" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Scan or Input Manufacturer's barcode">
                            </div>
                           
                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Category</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-bars"></i></span>
                                <select name="category" id="category" aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                <?php echo $LObject->loadCategories(); ?>
                                </select>
                            </div>
                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Sub Category</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-sort-down"></i></span>
                                <select name="sub_cat" id="sub_cat" aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                
                                </select>
                            </div>
                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Supplier/vendor</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-truck-loading"></i></span>
                                <select name="vendor" id="vendor" aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                    <?php echo $LObject->loadVendors(); ?>   
                                </select>
                            </div>

                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Product Unit</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-boxes"></i></span>
                                <select name="product_unit" id="product_unit" aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                    <option  disabled selected>Choose unit</option>
        
                                    <option  value="Bottles(s)">BOTTLE(s)</option>
                                    <option  value="Can(s)">CAN(s)</option>
                                    <option  value="Pack(s)">PACK(s)</option>
                                    <option  value="Pair(s)">PAIR(s)</option>
                                    <option  value="Piece(s)">PIECE(s)</option>   
                                </select>
                            </div>

                            <!--div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Pack  Size</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-box"></i></span>
                                <input type="text" name="pack_size" id="pack_size" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Pack Size">
                            </div-->

                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Unit Cost Price</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-tag"></i></span>
                                <input type="text" name="product_cost" id="product_cost" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Selling Price">
                            </div>

                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Percentage Markup</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-percent"></i></span>
                                <input type="text"name="product_markup" id="product_markup" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Percentage Markup e.g 20">
                            </div>

                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">VAT</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-percent"></i></span>
                                <input type="text"name="vat" id="vat" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input VAT e.g 7.5">
                            </div>
                            <div class="input-group input-group mb-1">
                                <button type="button" id="genRetPrice" class="btn btn-warning">Generrate Suggested Retail Price</button> <span style="margin-left:20px; font-size: 20px"; id="suggestedRetailPrice" class="text text-warning"></span>
                            </div>

                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Retail Price</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-coins"></i></span>
                                <input type="text"name="retailPrice" id="retailPrice" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input your retail Price">
                            </div>
                            
                            <div class="input-group input-group mb-1">
                                <span class="input-group-text" id="inputGroup-sizing-default">Description</span>
                                <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-pen"></i></span>
                                <textarea class="form-control" name="description" id="description" rows="3">Type in Product description </textarea>
                            </div>   

                            <input type="hidden" name="entered_by" id="entered_by" value=" <?= $_SESSION["user_id"]; ?>">
                            <input type="hidden" name="product_id" id="product_id" />
                           
                            <input type="hidden" name="btn_action" id="btn_action" value="" />
                    </div>
                    <div class="modal-footer">
                            <!--button type="submit" id="action" class="btn btn-info">Add</button-->
                            <input type="button" name="action" id="action" class="btn btn-info" value="Add"/>
                        </form>
                        <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Add and Edit Modal -->

        <!--View Modal -->
        <div class="modal fade" id="productViewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="productViewModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row product_data_view">
                    
                    </div>
                        
                </div>
                <div class="modal-footer">
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close </button>
                </div>
                </div>
            </div>
        </div>
        <!-- End View Modal -->

   
    <!-- Modal -->


    <!-- End Modal -->

    <!-- Scripts -->
    <?php include 'partials/scripts.php'; ?>

    <script>

        //Fetching Datatable
        $(document).ready(function(){    
            let btn_action = 'load_table'
            product_DataTable = $('#product_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"product_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[8,9,10],
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //opening modal
        $('#productInput').click(function(){
            $('#productForm')[0].reset();
            $('#suggestedRetailPrice').html('');
            $('#btn_action').val("Add");
            $('#action').attr('disabled', false);
            $('#action').val('Add');
        });

        // close the modal
        $('.close').click(function(){
            $('#productForm')[0].reset();
            $('#suggestedRetailPrice').html('');
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });
        
        //load sub_categories based on categories table
        $('#category').change(function(){
            let cat_id = $('#category').val();
            let  btn_action = 'load_sub_cat';
            $.ajax({
                url: "product_action.php",
                method: "POST",
                data:{btn_action:btn_action, cat_id:cat_id},
                success:function(data){
                    $('#sub_cat').html(data);
                }
            })
        });

        //Adding and Editing 
        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
           
            let form_data = $('#productForm').serialize();
        
            $.ajax({
                url:"product_action.php",
                method:"POST",
                data: form_data,
                dataType:"JSON",
                success:function(data)
                {
                    if(data.success)
                    {
                        $('#productForm')[0].reset();
                        $('#productModal').modal('hide');
                        $('#action').attr('disabled', false);
                        swal({
                            title: "Success!",
                            text: data.success,
                            icon: "success",
                        });
                        product_DataTable.ajax.reload();
                    }
                    else{
                        $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        $('#action').attr('disabled', false);
                    }    
                }
            });
        });

        //Fetch data and set them in the edit modal
        $(document).on('click', '.update', function(){
            let product_id = $(this).attr("id");
            let btn_action = 'fetch_single';
            $('#action').attr('disabled', false);
            $.ajax({
                url:"product_action.php",
                method:"POST",
                data:{product_id:product_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    $('#productModal').modal('show');
                    $('#product_name').val(data.product_name);
                   
                    $('#manufacturer_barcode').val(data.manufacturer_barcode)
                    
                    $('#product_cost').val(data.product_cost_price);
                    $('#vat').val(data.vat);
                    $('#product_markup').val(data.product_markup);
                    $('#recordedLevel').val(data.recorded_level);
                   
                    $('#vendor').val(data.product_vendor_id);
                    $('#product_unit').val(data.product_unit);
                    //$('#pack_size').val(data.pack_size);
                    $('#retailPrice').val(data.retail_price);
                    $('#description').val(data.product_description);
                    $('#product_id').val(data.product_id);
                    
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                    
                }
            })
        });

        //Generate Suggested Retail Price
        $('#genRetPrice').click(function(){
            let cost =  Number($('#product_cost').val()) ;
            let markup = Number($("#product_markup").val());
            let vat =  Number($("#vat").val())
            let sum = (
               cost + (cost * (markup/100)) +(cost * (vat/100))
            );
            $('#suggestedRetailPrice').html(sum.toFixed(2));
        });
        //Deactivate and Activate
        $(document).on('click','.delete', function(){
            let product_id = $(this).attr("id");
            let product_status  = $(this).data('status');
            let btn_action = 'delete';
            swal({
                title: "Are you sure?",
                text: "Product status will be changed",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"product_action.php",
                        method:"POST",
                        data:{product_id:product_id, product_status:product_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    product_DataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                    icon: "danger",
                                });
                                product_DataTable.ajax.reload();
                            }
                        }
                    });
                } else {
                    swal("Status Won't be changed");
                }
            });    
        });

        //Fetch Data for product View
        $(document).on('click','.view', function(){
            let product_id = $(this).attr("id");
            let btn_action = 'product_details';
            $.ajax({
                url:"product_action.php",
                method:"POST",
                data:{product_id:product_id, btn_action:btn_action},
                success:function(data)
                {
                    
                    $('#productViewModal').modal('show');
                    $('.product_data_view').html(data);
                }
            });
            
        });

    
    </script>
</body>
</html>