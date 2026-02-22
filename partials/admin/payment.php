<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    
    $title = 'Payment | Muttyfem';
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
                                        Proof Of Delivery Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                      
                                </div>
                            </div>
                            
                        </div>
                            
                        </div>
                        <div class="card-content">
                            <table id="purchase_order_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order NO</th>
                                        <th>vendor</th>
                                        <th>Total</th>
                                        <th>Created on</th>
                                        <th>Status</th>
                                        <th>PDF</th>
                                        <th>PAY</th>
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

    <!-- Modal -->
        <!-- Add Purchase Order Modal -->
        <div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Payment For Purchase Order</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="purchaseOrderPaymentForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>

                        <div class="col-md-6">
                            <label for="total" class="form-label">Purchase Order Payable Total</label>
                            <input type="text" class="form-control disable" name="total" id="total" >
                        </div>

                        <div class="col-md-6">
                            <label for="total" class="form-label">Payment Made</label>
                            <input type="text" class="form-control disable" name="payment_made" id="payment_made" >
                        </div>
                        <div class="col-md-6">
                            <label for="outstanding" class="form-label">Purchase Order Outstanding Payment</label>
                            <input type="text" class="form-control disable" name="outstanding" id="outstanding" >
                        </div>
                        <div class="col-6">
                            <label for="purchase_pamyment" class="form-label">Pay Outstanding Due</label>
                            <input type="text" class="form-control" name="purchase_pamyment"id="purchase_pamyment"  >
                        </div>
                        
                       
                        <input type="hidden" name="purchase_order_id" id="purchase_order_id" value=""/>
                        <input type="hidden" name="btn_action" id="btn_action" value="pay_outstanding" />
                </div>
                <div class="modal-footer">
                    
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Pay Outstanding" />
                    </form>
                    <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                </div>
                </div>
            </div>
        </div>

        <!-- End Add Purchase Order Modal
        <!--Invoice View Modal -->
        <!--View Modal -->
        <div class="modal fade" id="purchaseOrderViewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="purchaseOrderViewModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="InvoiceModalLabel">Invoice View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row purchase_order_data_view">
                    
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
        <!-- End Invoice View Modal -->
    <!-- End Modal -->

    <!-- scripts -->
    <?php include 'partials/scripts.php'; ?>

    <script>

        //Reload Table 
        $(document).ready(function(){
            
            let btn_action = 'load_table';
            let pay = 'payment';
            purchaseOrderDataTable = $('#purchase_order_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"pod_action.php",
                    type:"POST",
                    data:{btn_action:btn_action,pay:pay}
                },
                "columnDefs":[
                    {
                        "targets":[6,7,8],
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //reset form
        $('#refundInput, .close').click(function(){
            $('#purchaseOrderPaymentForm')[0].reset();
            $('#action').attr('disabled', false);
           
        });
       
        //View Purchase before Printing it
        $(document).on('click','.view', function(){
            let pod_id = $(this).attr("id");           
            let btn_action = 'proof_of_delivery_details';
            $.ajax({
                url:"pod_action.php",
                method:"POST",
                data:{pod_id:pod_id, btn_action:btn_action},
                success:function(data)
                {
                    $('#purchaseOrderViewModal').modal('show');
                    $('.purchase_order_data_view').html(data);
                }
            });            
        });
        
        //fire payment form 
        $(document).on('click','.pay',function(){
            let pod_id = $(this).attr("id"); 
            let btn_action = 'pay';
            $.ajax({
                url:"pod_action.php",
				method:"POST",
				data:{pod_id:pod_id,btn_action:btn_action},
                dataType:"JSON",
                success:function(data){
                    $('#paymentModal').modal('show');
                    $('#purchase_order_id').val(pod_id);
                    $('#total').val(data.pod_overview_total);
                    $('#payment_made').val(data.payment_made);
                    $('#outstanding').val(data.outstanding);
                }

            })
        })

        //Submitting proof of delivery payment form
        $(document).on('submit', '#purchaseOrderPaymentForm', function(event){
			event.preventDefault();
			$('#action').attr('disabled', 'disabled');
			let purchase_pamyment = $('#purchase_pamyment').val();
            let pod_id = $('#purchase_order_id').val();
            let payment_made = $('#payment_made').val(); 
            let total = $('#total').val();
            let btn_action = $('#btn_action').val();
			$.ajax({
				url:"pod_action.php",
				method:"POST",
				data:{purchase_pamyment:purchase_pamyment,pod_id:pod_id,payment_made:payment_made,total:total,btn_action:btn_action},
                dataType:"JSON",
				success:function(data){
					if(data.success){
                        $('#purchaseOrderPaymentForm')[0].reset();
                        $('#paymentModal').modal('hide');
                        $('#action').attr('disabled', false);
                        swal({
                            title: "Success!",
                            text: data.success,
                            icon: "success",
                        });
                        purchaseOrderDataTable.ajax.reload();
                    }else{
                        $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        $('#action').attr('disabled', false);
                    }
				}
			});
		});
        
    </script>
</body>
</html>