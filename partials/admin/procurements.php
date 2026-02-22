<?php 
    session_start();
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Procurements | Clover Cuties';
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
            
            <div class="row">
                <div class="col-12-lg col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3>Procurement Table</h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#procurementModal" id="procurementInput">
                                        Add Procurement <span class="fas fa-plus-circle"></span>
                                    </button>   
                                </div>
                            </div>    
                        </div>
                        <div class="card-content"> 
                            <table id="procurement_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Procurement Date</th>
                                        <th>Status</th>
                                       
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

    <!-- Modal -->
    <div class="modal fade" id="procurementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="procurementModalLabel" aria-hidden="true">
        <div class=" modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="procurementModalLabel">Procurement Registration</h5>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="procurementForm" class="row g-3">
                    <div  id="gen-valid">
                        
                    </div>
                    <div class="input-group input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Item</span>
                        <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-list"></i></span>
                        <input type="text" name="procurement" id="procurement" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input item to procure">
                    </div>
                    <div class="input-group input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Amount</span>
                        <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-coins"></i></span>
                        <input type="text" name="amount" id="amount" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Amount">
                    </div>
                    
                    
                    
                    <div class="input-group input-group mb-1">
                        <span class="input-group-text" id="inputGroup-sizing-default">Description</span>
                        <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-pen"></i></span>
                        <textarea class="form-control" name="description" id="description" rows="3">State Reasons for the procurement </textarea>
                      
                    </div>
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
    <!-- Modal -->

    <!-- Scripts -->
    <?php include 'partials/scripts.php' ?>
    <!-- End Scripts -->

    <script>

        //Loading dataTable
        $(document).ready(function(){
            
            let btn_action = 'load_table'
            procurement_dataTable = $('#procurement_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"procurement_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[6],
                        "orderable":false,
                    },
                ],
                "pageLength": 10
            });
        });
       
        // Adding Procured Items
        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
            let procurement = $('#procurement').val();
            let amount = $('#amount').val(); 
            let description = $('#description').val();
            
            let btn_action = $('#btn_action').val();
            
            if(procurement === '' || amount === '' || description == ''){
                $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields are required<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $('#action').attr('disabled', false);
            }
            else{  
                $.ajax({
                    url:"procurement_action.php",
                    method:"POST",
                    data:{procurement:procurement, amount:amount, description:description, btn_action:btn_action},
                    dataType:"JSON",
                    success:function(data)
                    {
                        if(data.success){
                            $('#procurementForm')[0].reset();
                            $('#procurementModal').modal('hide');
                            $('#action').attr('disabled', false);
                            swal({
                                title: "Success!",
                                text: data.success,
                                icon: "success",
                            });
                            procurement_dataTable.ajax.reload(); 
                        }else{
                            $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            $('#action').attr('disabled', false); 
                        }    
                    }
                });
            }
            
        });
   
        //Deactivate and Activate
        $(document).on('click','.delete', function(){
            let procurement_id = $(this).attr("id");
            let procurement_status  = $(this).data('status');
            let btn_action = 'delete';
            swal({
                title: "Are you sure?",
                text: "Pocurement status will be changed",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"procurement_action.php",
                        method:"POST",
                        data:{procurement_id:procurement_id, procurement_status:procurement_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    procurement_dataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                        icon: "danger",
                                    });
                                    procurement_dataTable.ajax.reload();
                            }
                        }
                    });
                } else {
                    swal("Status Won't be changed");
                }
            });    
        });
    </script>
</body>
</html>