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
        <div class="wrapper">
           
            <div class="row table-area">
                <div class="col-12 col-m-12 col-sm-12">
                    
                   
                    <div class="card">
                        <div class="card-header">
                        <div class="row">
                                <div class="col-lg-8">
                                    <h3>
                                        Proof of Delivery Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#proofOfDeliveryModal" id="proofOfDeliveryInput">
                                        Add Proof of Delivery <span class="fas fa-plus-circle"></span></button>   
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
                                        <th>Vendor</th>
                                        <th>Total</th>
                                        <th>Created on</th>
                                        <th>Payment Status</th>
                                        <th>View</th>
                                        <th>PDF</th>
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
        <div class="modal fade" id="proofOfDeliveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="proofOfDeliveryModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="proofOfDeliveryModalLabel">Proof of Delivery Creation</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="proofOfDeliveryForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>

                        <div class="col-md-4">
                            <label for="podDataList" class="form-label">Purchase Order Number</label>
                            <input class="form-control" list="podnumberdatalistOptions" id="podDataList" placeholder="Input Purchase Order Number to search..." autocomplete="off">
                            <datalist id="podnumberdatalistOptions">
                            <?php echo $LObject->loadPurchaseOrder(); ?>
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info"  id="fetchPurchaseDetails">
                                Fetch Purchase Order Details <span class="fas fa-search"></span></button> 
                        </div>
                       
                        <div class="col-md-6">
                            <label for="supplierName" class="form-label">Supplier/Vendor Name</label>
                            <input type="text" class="form-control disable" name="supplierName" id="supplierName">
                        </div>
                        <div class="col-md-6">
                            <label for="podNumber" class="form-label">Purchase Order ID</label>
                            <input type="text" class="form-control disable" name="podNumber" id="podNumber">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="vendorEmployeeName" class="form-label">Supplier/Vendor Employee Name</label>
                            <input type="text" class="form-control" name="vendorEmployeeName" id="vendorEmployeeName">
                        </div>

                        <div class="col-md-6">
                            <label for="driverName" class="form-label">Driver's Name</label>
                            <input type="text" class="form-control" name="driverName" id="driverName">
                        </div>
                        
                        
                        
                       
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3"><label for="">Article</label></div>
                                <div class="col-md-2"><label for="">PO Qty</label></div>
                                <div class="col-md-3"><label for="">EXP Date</label></div>
                                <div class="col-md-2"><label for="">UNIT PRICE</label></div>
                                <div class="col-md-2"><label for="">Action</label></div>
                            </div>
							
							<hr />
							<span id="span_product_details"></span>
							<hr />
						</div>

                        <input type="hidden" name="entered_by" id="entered_by" value=" <?= $_SESSION["user_id"]; ?>">
                        <input type="hidden" name="raised_by" id="raised_by" value="">
                        <input type="hidden" name="supplier_id" id="supplier_id" value=""/>
                        <input type="hidden" name="po_id" id="po_id" value=""/>
                        <input type="hidden" name="btn_action" id="btn_action" value="Add" />
                </div>
                <div class="modal-footer">
                    
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    </form>
                    <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                </div>
                </div>
            </div>
        </div>

        <!-- End Add Purchase Order Modal-->
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
            let pay = 'none';
            purchaseOrderDataTable = $('#purchase_order_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"pod_action.php",
                    type:"POST",
                    data:{btn_action:btn_action, pay:pay}
                },
                "columnDefs":[
                    {
                        "targets":[6,7],
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //fire the a host of procedures for proof of delivery form 
        $('#proofOfDeliveryInput').click(function(){
            $('#proofOfDeliveryForm')[0].reset();
            $('#span_product_details').html('');
        });
        

        //fetching supplier list
        $('#fetchPurchaseDetails').on('click', function(){
           
            let pod_code = $('#podDataList').val();
            let btn_action = 'load_pod_details';
            $.ajax({
                url: 'pod_action.php',
                method: "POST",
                data:{pod_code:pod_code, btn_action:btn_action},
                dataType:"json",
                success:function(data){
                    $('#supplier_id').val(data.supplier_id);
                    $('#supplierName').val(data.supplierName);
                    $('#podNumber').val(data.podNumber);
                    $('#po_id').val(data.po_id);
                    $('#raised_by').val(data.raised_by);
                    
                    let poNum = data.po_id;
                    fetchPurchaseOrderProduct(poNum);
                } 
            })
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
        
        //Submitting Purchase Order form
        $(document).on('submit', '#proofOfDeliveryForm', function(event){
			event.preventDefault();
			$('#action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax({
				url:"pod_action.php",
				method:"POST",
				data:form_data,
                dataType:"JSON",
				success:function(data){
					if(data.success){
                        $('#proofOfDeliveryForm')[0].reset();
                        $('#proofOfDeliveryModal').modal('hide');
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
        
        // function to remove rows
        $(document).on('click', '.remove', function(){
            var row_no = $(this).attr("id");
            $('#row'+row_no).remove();
        });

        //function to fetch all products supplied by vendor
        function fetchPurchaseOrderProduct(poNum){
            let btn_action = 'fetch_P_Order_Products';
            $.ajax({
                url: "pod_action.php",
                method: "POST",
                data: {poNum:poNum,btn_action:btn_action},
                success:function(data){
                    $('#span_product_details').html(data);
                }
            });
        }

    </script>
</body>
</html>