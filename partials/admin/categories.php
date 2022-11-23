<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Categories | Clover Cuties';
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
                <div class="col-lg-12 col-md-3 col-sm-12">
                        <!-- Button trigger modal -->
                        
                </div>
               
              

              
            </div>

            <div class="row">
                <div class="col-12 col-m-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                        <div class="row">
                                <div class="col-lg-8">
                                    <h3>Category Table</h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal" id="categoryInput">Add Category <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="category_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
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

    
    <!-- Modal -->
    <div class="modal fade" id="categoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class=" modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CategoryModalLabel">Category Form</h5>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="categoryForm" class="row g-3">
                    <div  id="gen-valid">
                        
                    </div>
                    <div class="input-group input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Category Name</span>
                        <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-bars"></i></span>
                        <input type="text" name="cat_name" id="cat_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Categoty Name" required>
                    </div>
                    <div class="input-group input-group mb-1">
                        
                        <input type="hidden" name="cat_id" id="cat_id" />
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
    <!-- Modal -->

    <!-- Scripts -->
    <?php include 'partials/scripts.php' ?>
    <!-- End Scripts -->
    <script>
       

        $(document).ready(function(){
            
            let btn_action = 'load_table'
            categorydataTable = $('#category_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"categories_action.php",
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

        $('#categoryInput').click(function(){
            $('#categoryForm')[0].reset();
            $('#btn_action').val("Add");
        });

        $('.close').click(function(){
            $('#categoryForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });
            
        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
            let cat_name = $('#cat_name').val();
            let cat_id = $('#cat_id').val();
            let btn_action = $('#btn_action').val();
            if(cat_name === ''){
                $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">Category Name Cannot be Empty<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $('#action').attr('disabled', false);
            }else{
                $.ajax({
                    url:"categories_action.php",
                    method:"POST",
                    data:{cat_name:cat_name, cat_id:cat_id, btn_action:btn_action},
                    dataType:"json",
                    success:function(data)
                    {
                        if(data.success){
                            $('#categoryForm')[0].reset();
                            $('#categoryModal').modal('hide');
                            $('#action').attr('disabled', false);
                            swal({
                                title: "Success!",
                                text: data.success,
                                icon: "success",
                            });
                            categorydataTable.ajax.reload();
                        }else{
                            $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            $('#action').attr('disabled', false);
                        }   
                    }
                });
            }
                

        
        });

        $(document).on('click', '.update', function(){
            let cat_id = $(this).attr("id");
            let btn_action = 'fetch_single';
            $.ajax({
                url:"categories_action.php",
                method:"POST",
                data:{cat_id:cat_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {            
                    $('#categoryModal').modal('show');    
                    $('#cat_name').val(data.cat_name);
                    $('#cat_id').val(data.cat_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                }
            })
        });

        $(document).on('click','.delete', function(){
            let cat_id = $(this).attr("id");
            let cat_status  = $(this).data('status');
            let btn_action = 'delete';
            swal({
                title: "Are you sure?",
                text: "Category status will be changed",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"categories_action.php",
                        method:"POST",
                        data:{cat_id:cat_id, cat_status:cat_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    categorydataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                        icon: "danger",
                                    });
                                    categorydataTable.ajax.reload();
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