<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Expiry-Damage Report | Muttyfem Supermarket';
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
                                        Damage/Expiry Record Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#refundModal" id="refundInput">
                                        Create Damage/Expiry Record <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-content">
                            <table id="refund_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Total Cost</th>
                                        <th>Condition</th>
                                        <th>Created By</th>
                                        <th>Created On</th>
                                        <th>View</th>
                                       
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
        <div class="modal fade" id="refundModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="refundModalLabel">Damages/Expiry Record Creation</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="refundForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>

                      

                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Select Product</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-trademark"></i></span>
                            <select name="product_id" id="product_id" aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                <option disabled selected>Choose product</option>
                                <?php echo $LObject->loadProductRefund(); ?>    
                            </select>
                        </div>
                        
                        
                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Damaged/Expired Quantity</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-fill"></i></span>
                            <input type="text" name="refund_qty" id="refund_qty" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Returned Quantity">
                        </div>
                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Item Cost</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-money-bill-wave"></i></span>
                            <input type="text" name="itemCost" id="itemCost" class="form-control disable" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" >
                        </div>

                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Select Product Condition</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-info-circle"></i></span>
                            <select name="refundCondition" id="refundCondition" aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                <option disabled selected>Choose Product Condition</option>
                                <option  value="Damaged">Damaged</option> 
                                <option  value="Expired">Expired</option>
                                <option  value="Others">Others</option> 
                            </select>
                        </div>

                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Select Action</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-directions"></i></span>
                            <select name="refundAction" id="refundAction" aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                <option disabled selected>Choose Action</option>
                                <option  value="Destroy">Destroy</option> 
                                <option  value="Return to Supplier">Return to Supplier</option>      
                            </select>
                        </div>

                         <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Damage/Expiry Comment</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-comments"></i></span>
                            <textarea class="form-control" name="refundComment" id="refundComment" rows="3">Type in comment or observation on state of item</textarea>
                        </div>  

                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Total Cost</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-money-check-alt"></i></span>
                            <input type="text" name="totalCost" id="totalCost" class="form-control disable" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" >
                        </div>


                        <input type="hidden" name="entered_by" id="entered_by" value=" <?= $_SESSION["user_id"]; ?>"> 
                      
                       
                       
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
        <div class="modal fade" id="refundViewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="refundViewModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Refund View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row refund_data_view">
                    
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
            refundDataTable = $('#refund_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"expiry_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[7],
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //opening modal
        $('#refundInput, .close').click(function(){
            $('#refundForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').attr('disabled', false);
            $('#action').val('Add');
        });

        //load inventory particular data when invoice and product is selected
        $('#product_id').change(function(){
            let product_id = $('#product_id').val();
            let invoice_id = $('#invoice_number').val();
            let  btn_action = 'load_inventory_details';
            if(product_id != '' && invoice_id != ''){
                $.ajax({
                    url: "expiry_action.php",
                    method: "POST",
                    data:{btn_action:btn_action, product_id:product_id, invoice_id:invoice_id},
                    dataType: "JSON",
                    success:function(data){
                        $('#itemCost').val(data.product_cost_price);
                       
                    }
                });
            }else{
                $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">Kindly Select an Invoice Number and Product<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            }
           
        });

        //Calculate total cost based on quantity
        $(function(){
            $("#refund_qty").on("keydown keyup", qty);
            function qty(){
                let sum = (
                    Number($("#itemCost").val()) * Number($("#refund_qty").val())
                );
               $('#totalCost').val(sum.toFixed(2));
            }
        });

        //Adding 
        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
            let product_number = $('#product_id').val();
            
            let quantity = $('#refund_qty').val();
            let unitPrice = $('#itemCost').val();
            let refundCondition = $('#refundCondition').val();
            let refundAction = $('#refundAction').val();
            let refundPayout = $('#totalCost').val();
            let refundComment = $('#refundComment').val();
            let btn_action = $('#btn_action').val();
            let form_data = $("#refundForm").serialize();

            if(btn_action =='Add')
            {
                if( product_number == '' ||  quantity == '' || unitPrice == '' || refundCondition == '' || refundAction == '' || refundPayout == '' || refundComment == ''  ){
                    $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields are required<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $('#action').attr('disabled', false);
                }
                else{    
                    $.ajax({
                        url:"expiry_action.php",
                        method:"POST",
                        data:form_data,
                        dataType:"JSON",
                        success:function(data)
                        {
                            if(data.success){
                                $('#refundForm')[0].reset();
                                $('#refundModal').modal('hide');
                                $('#action').attr('disabled', false);
                                swal({
                                    title: "Success!",
                                    text: data.success,
                                    icon: "success",
                                });
                                refundDataTable.ajax.reload();
                            }else{
                                $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                $('#action').attr('disabled', false);
                            }
                            
                        }
                    });
                }
            }
            else
            {
                swal({
                    title: "Warning!",
                    text: "Try to check for empty fields",
                    icon: "warning",
                });   
            }

        });

      

        //Fetch Data for product View
        $(document).on('click','.view', function(){
            let refund_id = $(this).attr("id");
            let btn_action = 'refund_details';
            $.ajax({
                url:"expiry_action.php",
                method:"POST",
                data:{refund_id:refund_id, btn_action:btn_action},
                success:function(data)
                {
                    $('#refundViewModal').modal('show');
                    $('.refund_data_view').html(data);
                }
            });
            
        });

    </script>
</body>
</html>