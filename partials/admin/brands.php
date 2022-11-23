<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Admin"){
        header("location:login.php");
    }
    $title = 'Brands | Clover Cuties';
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
           
            <div class="row"> 
                <div class="col-12 col-m-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3>Brand Table</h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#brandModal" id="brandInput">
                                        Add Brand <span class="fas fa-plus-circle"></span>
                                    </button>   
                                </div>
                            </div>    
                        </div>
                        <div class="card-content">
                            <table id="brand_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Brand Name</th>
                                        <th>Created On</th>
                                        <th>Status</th>
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

    <!--Modals -->
        <!-- Add and Edit Modal -->
        <div class="modal fade" id="brandModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Brand Registration</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="brandForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>
                        
                        <div class="input-group input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Brand</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-box"></i></span>
                            <input type="text" name="brand_name" id="brand_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Brand Name">
                        </div>
                        
                        
                        <div class="input-group input-group mb-1">
                            <input type="hidden" name="brand_id" id="brand_id" />
                            <input type="hidden" name="btn_action" id="btn_action" value="Add" />
                        </div>  
                </div>
                <div class="modal-footer">
                    
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    </form>
                    <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                </div>
                </div>
            </div>
        </div>
        <!-- End Add and Edit Modal -->
     
    <!-- End Modals -->

    <?php include 'partials/scripts.php' ; ?>
       
    <script>
        $(document).ready(function(){
            
            let btn_action = 'load_table'
            brand_dataTable = $('#brand_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"brand_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[4,5],
                        "orderable":false,
                    },
                ],
                "pageLength": 10
            });
        });

        $('#brandInput').click(function(){
            $('#brandForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

        $('.close').click(function(){
            $('#brandForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

       

        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
            let brand_name = $('#brand_name').val();
            let brand_id = $('#brand_id').val();
            let btn_action = $('#btn_action').val();
            if(brand_name === ''){
                $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields Must Be Filled<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $('#action').attr('disabled', false);
            }else{
                $.ajax({
                    url:"brand_action.php",
                    method:"POST",
                    data:{brand_name:brand_name, brand_id:brand_id, btn_action:btn_action},
                    dataType:"json",
                    success:function(data)
                    {
                        if(data.success){
                            $('#brandForm')[0].reset();
                            $('#brandModal').modal('hide');
                            $('#action').attr('disabled', false);
                            swal({
                                title: "Success!",
                                text: data.success,
                                icon: "success",
                            });
                            brand_dataTable.ajax.reload();
                        }else{
                            $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            $('#action').attr('disabled', false);
                        }   
                        
                    }
                });
            }
           
        
        });

        $(document).on('click', '.update', function(){
         
            let brand_id = $(this).attr("id");
            let btn_action = 'fetch_single';
            
            $.ajax({
                url:"brand_action.php",
                method:"POST",
                data:{brand_id:brand_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {     
                    $('#brandModal').modal('show');    
                    $('#brand_name').val(data.brand_name); 
                    $('#brand_id').val(data.brand_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                }
            });
        });

        $(document).on('click','.delete', function(){
            let brand_id = $(this).attr("id");
            let brand_status  = $(this).data('status');
            let btn_action = 'delete';
            swal({
                title: "Are you sure?",
                text: "Brand status will be changed",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"brand_action.php",
                        method:"POST",
                        data:{brand_id:brand_id, brand_status:brand_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    brand_dataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                        icon: "danger",
                                    });
                                    brand_dataTable.ajax.reload(); 
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