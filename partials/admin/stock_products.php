<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] === "Cashier" || $_SESSION['type'] === "Accountant"){
        header("location:login.php");
    }
    $title = 'Stock Products | Clover Cuties';
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
                                        Stock Product Management
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#stockProductModal" id="stockProductInput">
                                        Add Stock Product <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-content">
                            <table id="stock_product_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Unit</th>
                                        <th>Created On</th>
                                        <th>Status</th>
                                        <th>View</th>
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
        <div class="modal fade" id="stockProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stockProductModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockProductModalLabel">Stock Product Registration</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="stockProductForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>
                        
                       
                     
                        
                        <input type="hidden" name="entered_by" id="entered_by" value=" <?= $_SESSION["user_id"]; ?>">
                        <input type="hidden" name="strock_product_id" id="product_id" />
                        <input type="hidden" name="btn_action" id="btn_action" value="" />
                </div>
                <div class="modal-footer">
                    
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    </form>
                    <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                </div>
                </div>
            </div>
        </div>
        <!--End Add and Edit Modal -->

        <!--View Modal -->
        <div class="modal fade" id="stockProductViewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stockProductViewModalLabel" aria-hidden="true">
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
            stockProductDataTable = $('#stock_product_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"stock_product_action.php",
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
        $('#stockProductInput').click(function(){
            $('#stockProductForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

        // close the modal
        $('.close').click(function(){
            $('#stockProductForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

       
        //load brand when category is selected
       $('#category').change(function(){
            let cat_id = $('#category').val();
            let  btn_action = 'load_sub_cat';
            $.ajax({
                url: "stock_product_action.php",
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
            let product_name = $('#product_name').val();
                      
            let product_unit = $('#product_unit').val();
            let category_id = $('#category').val();
            let sub_cat_id = $('#sub_cat').val();
            let brand_id = $('#brand').val();
          
            let product_id = $('#product_id').val();
            let product_entered_by = $('#entered_by').val();
            let btn_action = $('#btn_action').val();
            if(btn_action =='Add')
            {
                if(product_name == '' || product_unit == '' || category_id == '' || sub_cat_id == '' || brand_id == ''  ){
                    $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields are required<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $('#action').attr('disabled', false);
                }
                else{    
                    $.ajax({
                        url:"stock_product_action.php",
                        method:"POST",
                        data:{product_name:product_name, product_unit:product_unit, category_id:category_id, sub_cat_id:sub_cat_id, brand_id:brand_id, product_entered_by:product_entered_by, btn_action:btn_action},
                        dataType:"JSON",
                        success:function(data)
                        {
                            if(data.success){
                                $('#stockProductForm')[0].reset();
                                $('#stockProductModal').modal('hide');
                                $('#action').attr('disabled', false);
                                swal({
                                    title: "Success!",
                                    text: data.success,
                                    icon: "success",
                                });
                                stockProductDataTable.ajax.reload();
                            }else{
                                $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                $('#action').attr('disabled', false);
                            }
                            
                        }
                    });
                }
            }else if (btn_action == 'Edit')
            {
                if(product_name == '' || product_unit == '' || category_id == '' || sub_cat_id == '' || brand_id == ''|| product_id == '' ){
                    $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields Except Dropdowns are required<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $('#action').attr('disabled', false);  
                }
                else{
                    
                    $.ajax({
                        url:"stock_product_action.php",
                        method:"POST",
                        data:{product_name:product_name, product_unit:product_unit, category_id:category_id, sub_cat_id:sub_cat_id,brand_id:brand_id,  product_id:product_id, btn_action:btn_action},
                        dataType:"JSON",
                        success:function(data)
                        {
                            if(data.success){
                                $('#stockProductForm')[0].reset();
                                $('#stockProductModal').modal('hide');
                                $('#action').attr('disabled', false);
                                swal({
                                    title: "Success!",
                                    text: data.success,
                                    icon: "success",
                                });
                                stockProductDataTable.ajax.reload();
                            }else{
                                $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                $('#action').attr('disabled', false);
                            }
                            
                        }
                    });
                }
            }else{
                swal({
                    title: "Warning!",
                    text: "Try to check for empty fields",
                    icon: "warning",
                });   
            }

        });

        //Fetch data and set them in the edit modal
        $(document).on('click', '.update', function(){
            let stock_product_id = $(this).attr("id");
           
            let btn_action = 'fetch_single';
            $.ajax({
                url:"stock_product_action.php",
                method:"POST",
                data:{stock_product_id:stock_product_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    $('#stockProductModal').modal('show');
                    $('#product_name').val(data.stock_product_name);
                    $('#product_unit').val(data.stock_product_unit);
                    $('#brand').val(data.stock_prodoct_brand_id);
                    $('#product_id').val(data.stock_product_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                }
            })
        });

        //Deactivate and Activate
        $(document).on('click','.delete', function(){
            let stock_product_id = $(this).attr("id");
            let stock_product_status  = $(this).data('status');
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
                        url:"stock_product_action.php",
                        method:"POST",
                        data:{stock_product_id:stock_product_id, stock_product_status:stock_product_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    stockProductDataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                    icon: "danger",
                                });
                                stockProductDataTable.ajax.reload();
                            }
                        }
                    });
                } else {
                    swal("Status Won't be changed");
                }
            });    
        });

        //Fetch Data for stock product View
        $(document).on('click','.view', function(){
            let stock_product_id = $(this).attr("id");
            let btn_action = 'product_details';
            $.ajax({
                url:"stock_product_action.php",
                method:"POST",
                data:{stock_product_id:stock_product_id, btn_action:btn_action},
                success:function(data)
                {
                    
                    $('#stockProductViewModal').modal('show');
                    $('.product_data_view').html(data);
                }
            });
            
        });

       
    </script>
</body>
</html>