<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] === "Cashier" || $_SESSION['type'] === 'Sub_Admin'){
        header("location:login.php");
    }
    
    $title = 'Purchase Order | Muttyfem';
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
                                        Purchase Order Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#purchaseOrderModal" id="purchaseOrderInput">
                                        Add Purchase Order <span class="fas fa-plus-circle"></span></button>   
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
                                        <th>Order Status</th>
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
        <div class="modal fade" id="purchaseOrderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseOrderModalLabel">Purchase Order Creation</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="purchaseOrderForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>

                        <div class="col-md-4">
                            <label for="supplierDataList" class="form-label">Supplier Number</label>
                            <input class="form-control" list="suppliernumberdatalistOptions" id="supplierDataList" placeholder="Input Supplier Number to search..." autocomplete="off">
                            <datalist id="suppliernumberdatalistOptions">
                            <?php echo $LObject->loadSupplierCode(); ?>
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info"  id="fetchSupplierDetails">
                                Fetch Supplier Details <span class="fas fa-search"></span></button> 
                        </div>
                       
                        <div class="col-md-6">
                            <label for="supplierName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="supplierName" id="supplierName" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="customerMail" class="form-label">Supplier Email</label>
                            <input type="email" class="form-control" name="supplierMail" id="supplierMail" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="supplierPhone1" class="form-label">Supplier Phone Number</label>
                            <input type="tel" class="form-control" name="supplierPhone1" id="supplierPhone1" disabled>
                        </div>
                       
                        <div class="col-md-6">
                            <label for="supplierPhone2" class="form-label">Supplier Phone Number</label>
                            <input type="tel" class="form-control" name="supplierPhone2" id="supplierPhone2" disabled>
                        </div>
                        <div class="col-12">
                            <label for="supplierAddress" class="form-label">Supplier Address</label>
                            <input type="text" class="form-control" name="supplierAddress1"id="supplierAddress1"  disabled>
                        </div>
                        <div class="col-12">
                            <label for="supplierAddress2" class="form-label">Supplier Address 2</label>
                            <input type="text" class="form-control" name="supplierAddress2" id="supplierAddress2" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="supplierCity" class="form-label">City/Town</label>
                            <input type="text" class="form-control" name="supplierCity" id="supplierCity" disabled>
                        </div>

                        <div class="col-md-6">
                            <label for="supplierState" class="form-label">State</label>
                            <input type="text" class="form-control" name="supplierState" id="supplierState" disabled>
                        </div>
                       
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4"><label for="">ARTICLE NAME</label></div>
                                <div class="col-md-3"><label for="">PO QTTY</label></div>
                              
                                <div class="col-md-3"><label for="">UNIT PRICE</label></div>
                                <div class="col-md-2"><label for="">ACTION</label></div>
                            </div>
							
							<hr />
							<span id="span_product_details"></span>
							<hr />
						</div>

                        <input type="hidden" name="entered_by" id="entered_by" value=" <?= $_SESSION["user_id"]; ?>">
                        <input type="hidden" name="supplier_id" id="supplier_id" value=""/>
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
            purchaseOrderDataTable = $('#purchase_order_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"purchase_order_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[5,6],
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //fire the a host of procedures for purchase order form 
        $('#purchaseOrderInput').click(function(){
            $('#purchaseOrderForm')[0].reset();
            $('#span_product_details').html('');
            //add_product_row();
        });
        

        //fetching supplier list
        $('#fetchSupplierDetails').on('click', function(){
           
            let supplier_code = $('#supplierDataList').val();
            let btn_action = 'load_supplier_details';
            $.ajax({
                url: 'purchase_order_action.php',
                method: "POST",
                data:{supplier_code:supplier_code, btn_action:btn_action},
                dataType:"json",
                success:function(data){
                    $('#supplier_id').val(data.supplier_id);
                    $('#supplierName').val(data.supplier_name);
                    $('#supplierMail').val(data.supplier_email);
                    $('#supplierPhone1').val(data.phone_number1);
                    $('#supplierPhone2').val(data.phone_number2);
                    $('#supplierAddress1').val(data.supplier_address1);
                    $('#supplierAddress2').val(data.supplier_address2);
                    $('#supplierCity').val(data.supplier_city);
                    $('#supplierState').val(data.supplier_state);
                    let supplier_number = data.supplier_id;
                    fetchVendorProduct(supplier_number);
                } 
            })
        });

        //View Purchase before Printing it
        $(document).on('click','.view', function(){
            let purchase_order_id = $(this).attr("id");           
            let btn_action = 'purchase_order_details';
            $.ajax({
                url:"purchase_order_action.php",
                method:"POST",
                data:{purchase_order_id:purchase_order_id, btn_action:btn_action},
                success:function(data)
                {
                    $('#purchaseOrderViewModal').modal('show');
                    $('.purchase_order_data_view').html(data);
                }
            });            
        });
        
        //Submitting Purchase Order form
        $(document).on('submit', '#purchaseOrderForm', function(event){
			event.preventDefault();
			$('#action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax({
				url:"purchase_order_action.php",
				method:"POST",
				data:form_data,
                dataType:"JSON",
				success:function(data){
					if(data.success){
                        $('#purchaseOrderForm')[0].reset();
                        $('#purchaseOrderModal').modal('hide');
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
        function fetchVendorProduct(supplier_number){
            let btn_action = 'fetch_supplier_products';
            $.ajax({
                url: "purchase_order_action.php",
                method: "POST",
                data: {supplier_number:supplier_number,btn_action:btn_action},
                success:function(data){
                    $('#span_product_details').html(data);
                }
            });
        }

    </script>
</body>
</html>