<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Cashier"){
        header("location:login.php");
    }
    $title = 'History | MUTTYFEM SUPERMARKET';
    include 'partials/header.php' 
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
                    <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['user_id'] ?>">
                  
                    <div class="card">
                        <div class="card-header">
                            <h3>
                                Invoice Table
                            </h3>
                            
                        </div>
                        <div class="card-content">
                            <table id="invoice_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Cashier Name</th>
                                        <th>Total</th>
                                        <th>Payment Type</th>
                                        <th>Created on</th>
                                        <th>Status</th>
                                        <th>PDF</th>
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
        <!--View Modal -->
        <div class="modal fade" id="InvoiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="InvoiceModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="InvoiceModalLabel">Invoice View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row invoice_data_view">
                    
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

    <!-- End Modal -->

    <!-- scripts -->

    <?php include 'partials/scripts.php'; ?>
    <script>

        $(document).ready(function(){
            
            let btn_action = 'history';
            let user_id = $('#user_id').val()
            invoice_DataTable = $('#invoice_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"pos_action.php",
                    type:"POST",
                    data:{user_id:user_id, btn_action:btn_action}
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

        //View Invoice before Printing it
        $(document).on('click','.view', function(){
            let product_id = $(this).attr("id");           
            let btn_action = 'invoice_details';
            $.ajax({
                url:"pos_action.php",
                method:"POST",
                data:{product_id:product_id, btn_action:btn_action},
                success:function(data)
                {
                    $('#InvoiceModal').modal('show');
                    $('.invoice_data_view').html(data);
                }
            });            
        });
        

    </script>
</body>
</html>