<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Extra | Clover Cuties';
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
                                        Update Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateModal" id="updateInput">
                                        Increase or Reduce Product <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-content">
                            <table id="update_data"> 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Previous QTY</th>
                                        <th>Updated QTY</th>
                                        <th>Updated Action</th>
                                        <th>Created By</th>
                                        <th>Created On</th>   
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
        <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Reduce or Increase Product Quantity</h5>
                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div  id="gen-valid">
                            
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label for="product_number" class="form-label">EnterProduct Number</label>
                                <input type="text" name="product_number" id="product_number" placeholder="Enter Product Number">
                            </div>

                            <div class="col-md-4">
                                <button type="button" name="search_code" id="search_code" class="btn btn-success btn-xs">Search Product Code</button>
                            </div>
                        </div>

                           
                            <hr />
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Product Name</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="form-label">Current QTY</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">QTY</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-lable">Reduce</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-lable">Increase</label>
                                </div>   
                            </div>
                                <span id="span_product_details">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="hidden" name="product_id" id="product_id" />
                                            <input type="text" name="product_name" id="product_name" class="form-control disable">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" id="curQty" 
                                            name="curQty" class=" form-control  disable">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="product_quantity" id="product_quantity" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                           <button  id="reduce" class="btn btn-danger">-</button>
                                        </div>
                                        <div class="col-md-2">
                                           <button  id="increace" class="btn btn-success">+</button>
                                        </div>
                               
                                    </div>
                                </span>
                            <hr />
                            <input type="hidden" name="entered_by" id="entered_by" value=" <?= $_SESSION["user_id"]; ?>">
                        
                               
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Add and Edit Modal -->

     
   

    <!-- Scripts -->
    <?php include 'partials/scripts.php'; ?>

    <script>

        //Fetching Datatable
        $(document).ready(function(){    
            let btn_action = 'load_table'
            update_Data = $('#update_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"extra_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //opening modal
        $('#updateInput').click(function(){
            $('#product_id').val('');
            $('#product_name').val('');
            $('#curQty').val('');
            $('#product_quantity').val('');
            $('#product_number').val('');
        });

        // close the modal
        $('.close').click(function(){
            $('#product_id').val('');
            $('#product_name').val('');
            $('#curQty').val('');
            $('#product_quantity').val('');
            $('#product_number').val('');
        });
        
        //load sub_categories based on categories table
        $('#search_code').click(function(){
            let product_number = $('#product_number').val();
            let  btn_action = 'load_product';
            $.ajax({
                url: "extra_action.php",
                method: "POST",
                data:{product_number:product_number, btn_action:btn_action},
                dataType:"JSON",
                success:function(data){
                    $('#product_id').val(data.product_id);
                    $('#product_name').val(data.product_name);
                    $('#curQty').val(data.recorded_level); 
                }
            })
        });

        $('#reduce').click(function(){
            let product_id = $('#product_id').val();
            let current_qty = $('#curQty').val();
            let user_id = $('#entered_by').val();
            let qty = $('#product_quantity').val();
            let btn_action = 'reduce';
            $.ajax({
                url: "extra_action.php",
                method: "POST",
                data:{product_id:product_id,current_qty:current_qty, user_id:user_id,qty:qty,btn_action:btn_action},
                dataType:"JSON",
                success:function(data)
                {
                    if(data.success)
                    {
                        $('#product_id').val('');
                        $('#product_name').val('');
                        $('#curQty').val('');
                        $('#product_quantity').val('');
                        $('#product_number').val('');
                        $('#updateModal').modal('hide');
                        swal({
                            title: "Success!",
                            text: data.success,
                            icon: "success",
                        });
                        update_Data.ajax.reload();
                    }
                    else
                    {
                        $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }
                }

            })
        });

        $('#increace').click(function(){
            let product_id = $('#product_id').val();
            let current_qty = $('#curQty').val();
            let user_id = $('#entered_by').val();
            let qty = $('#product_quantity').val();
            let btn_action = 'increase';
            $.ajax({
                url: "extra_action.php",
                method: "POST",
                data:{product_id:product_id,current_qty:current_qty, user_id:user_id,qty:qty,btn_action:btn_action},
                dataType:"JSON",
                success:function(data)
                {
                    if(data.success)
                    {
                        $('#product_id').val('');
                        $('#product_name').val('');
                        $('#curQty').val('');
                        $('#product_quantity').val('');
                        $('#product_number').val('');
                        $('#updateModal').modal('hide');
                        swal({
                            title: "Success!",
                            text: data.success,
                            icon: "success",
                        });
                        update_Data.ajax.reload();
                    }
                    else
                    {
                        $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }
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
                        //update_data.ajax.reload();
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

        //Viewing Barcode
        $(document).on('click','.barcode', function(){ 
            let barcode = $(this).data('barcode');
            let price = $(this).data('price');
            let btn_action = "barcode";   
            $.ajax({
                method: "POST",
                url: "product_action.php",
                data:{barcode:barcode, price:price,btn_action:btn_action},
                success:function(data){
                    $('#barcodeModal').modal('show');
                    $('.barcode_data_view').html(data);
                },

            })
        });

        //Printing Barcode
        $(document).on('click','.print_receipt_modal',function(){
            let printContent = $('.barcode_data_view');
            let WinPrint = window.open('', '', 'width =900,height=650');
            WinPrint.document.write(printContent.html());
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        });
    </script>
</body>
</html>