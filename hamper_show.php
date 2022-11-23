<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] === "cashier"){
        header("location:login.php");
    }
    
    $title = 'Hamper | Muttyfem';
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
                                        Hamper Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <a href="hamper_creation.php" class="btn btn-success">Create Hamper</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="hamper_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>Hamper No</th>
                                        <th>Hamper Name</th>
                                        <th>QTY</th>
                                        <th>Price</th>
                                        <th>Created By</th>
                                        <th>Created on</th>
                                        <th>Status</th>
                                        <th>View</th>
                                        <th>Print Barcode</th>
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
                    <h5 class="modal-title" id="InvoiceModalLabel">Hamper View</h5>
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
          
            hamperDatatable = $('#hamper_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"hamper_creation_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[7,8],
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //View Invoice before Printing it
        $(document).on('click','.view', function(){
            let hamper_id = $(this).attr("id");           
            let btn_action = 'invoice_details';
            $.ajax({
                url:"hamper_creation_action.php",
                method:"POST",
                data:{hamper_id:hamper_id, btn_action:btn_action},
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