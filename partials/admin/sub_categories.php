<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Sub Categories | Muttyfem Supermarket';
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
                                    <h3>Sub Category Table</h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#subCategoryModal" id="subCategoryInput">
                                        Add Sub Category <span class="fas fa-plus-circle"></span>
                                    </button>   
                                </div>
                            </div>    
                        </div>
                        <div class="card-content">
                            <table id="subCategories_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sub Category</th>
                                        <th>Category</th>
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
        <div class="modal fade" id="subCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="subCategoryModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subCategoryModalLabel">Sub Categories Registration</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="subcategoriesForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>
                        
                        <div class="input-group input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Sub Category Name</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-sort-down"></i></span>
                            <input type="text" name="sub_category_name" id="sub_category_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Sub Category">
                        </div>

                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Category</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-bars"></i></span>
                            <select name="category" id="category" aria-label="Sizing example input" class="form-control selectpicker" aria-describedby="inputGroup-sizing-default" >
                               <?php echo $LObject->loadCategories(); ?>   
                            </select>
                           
                        </div>                      
                        
                        <div class="input-group input-group mb-1">
                            <input type="hidden" name="sub_category_id" id="sub_category_id" />
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
            sub_categories_dataTable = $('#subCategories_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"sub_categories_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[5,6],
                        "orderable":false,
                    },
                ],
                "pageLength": 10
            });
        });

        $('#subCategoryInput').click(function(){
            $('#subcategoriesForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

        $('.close').click(function(){
            $('#subcategoriesForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
            let form_data = $('#subcategoriesForm').serialize();
        
            if($('#sub_category_name').val() === '' || $('#category').val() === ''){
                $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields Must Be Filled<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $('#action').attr('disabled', false);
            }else{
                $.ajax({
                    url:"sub_categories_action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        if(data.success){
                            $('#subcategoriesForm')[0].reset();
                            $('#subCategoryModal').modal('hide');
                            $('#action').attr('disabled', false);
                            swal({
                                title: "Success!",
                                text: data.success,
                                icon: "success",
                            });
                            sub_categories_dataTable.ajax.reload();
                        }else{
                            $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            $('#action').attr('disabled', false);
                        }   
                        
                    }
                });
            }
           
        
        });
       
        $(document).on('click', '.update', function(){
         
            let sub_cat_id = $(this).attr("id");
            let btn_action = 'fetch_single';
            
            $.ajax({
                url:"sub_categories_action.php",
                method:"POST",
                data:{sub_cat_id:sub_cat_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {     
                    $('#subCategoryModal').modal('show');    
                    $('#sub_category_name').val(data.sub_category_name); 
                    $('#category').val(data.category_id); 
                    $('#sub_category_id').val(data.sub_category_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                }
            });
        });

        $(document).on('click','.delete', function(){
            let sub_cat_id = $(this).attr("id");
            let sub_cat_status  = $(this).data('status');
            let btn_action = 'delete';
            swal({
                title: "Are you sure?",
                text: "Sub category status will be changed",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"sub_categories_action.php",
                        method:"POST",
                        data:{sub_cat_id:sub_cat_id, sub_cat_status:sub_cat_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    sub_categories_dataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                        icon: "danger",
                                    });
                                    sub_categories_dataTable.ajax.reload(); 
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