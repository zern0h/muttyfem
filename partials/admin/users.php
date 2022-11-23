<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Users | Clover Cuties';
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
                <div class="col-12 col-m-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3>
                                        User Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userModal" id="userInput">
                                        Add Users <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="user_data"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
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

        <!-- Modal -->
        <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">User Registration</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="userForm" class="row g-3">
                        <div  id="gen-valid">
                            
                        </div>
                        <div class="input-group input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" id="name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Firstname Middlename Lastname">
                        </div>
                        <div class="input-group input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-at"></i></span>
                            <input type="email" name="email" id="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Email">
                        </div>
                        
                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Input Password"> 
                            <i class="fas fa-eye" id="password-show-icon"></i>
                            <i class="fas fa-eye-slash hide-icon" id="password-hide-icon"></i>
                        </div>
                        
                        <div class="input-group input-group mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-default">User Type</span>
                            <span class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-users"></i></span>
                            <select name="userRole" id="userRole"aria-label="Sizing example input" class="form-control" aria-describedby="inputGroup-sizing-default" >
                                <option selected >Select User Type</option>
                                <option value="Super_Admin" >Super Admin</option>
                                <option value="Admin" >Admin</option>
                                <option value="Sub_Admin" >Sub Admin</option>
                                <option value="Cashier" >Cashier</option> 
                            </select>
                            <input type="hidden" name="user_id" id="user_id" />
                            <input type="hidden" name="btn_action" id="btn_action" />
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
    
    <!-- End Content -->
   
    <!-- Scripts -->
        <?php include 'partials/scripts.php' ?>
    <!-- End Scripts -->
    <script>

        //fetching Datatable
        $(document).ready(function(){
            
            let btn_action = 'load_table'
            userdataTable = $('#user_data').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"user_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[6,7],
                        "orderable":false,
                    },
                ],
                "pageLength": 10
            });
        });

        //opening modal
        $('#userInput').click(function(){
            $('#userForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

        // close the modal
        $('.close').click(function(){
            $('#userForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });
        
       
        // Adding and Editing 
        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
            let name = $('#name').val();
            let email = $('#email').val();
            let password = $('#password').val();
          
            let user_role = $('#userRole').val();
            let user_id = $('#user_id').val();
            let btn_action = $('#btn_action').val();
            if(btn_action == 'Add'){
                if(name == '' || email == '' || password == '' || user_role == ''){
                    $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields are required<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $('#action').attr('disabled', false);
                }
                
                else{
                    $.ajax({
                        url:"user_action.php",
                        method:"POST",
                        data:{name:name, email:email, password:password, user_role:user_role, user_id:user_id, btn_action:btn_action},
                        dataType:"json",
                        success:function(data)
                        {
                            if(data.success){
                                $('#userForm')[0].reset();
                                $('#userModal').modal('hide');
                                $('#action').attr('disabled', false);
                                swal({
                                    title: "Success!",
                                    text: data.success,
                                    icon: "success",
                                });
                                userdataTable.ajax.reload();
                            }else{
                                $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                $('#action').attr('disabled', false);
                            }
                        
                        }
                    });
                }
            }
            else if(btn_action == 'Edit'){
                if(name == '' || email == '' || user_role == ''){
                    $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields are required<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $('#action').attr('disabled', false);
                }
                else{
                    $.ajax({
                        url:"user_action.php",
                        method:"POST",
                        data:{name:name, email:email, password:password, user_role:user_role, user_id:user_id, btn_action:btn_action},
                        dataType:"json",
                        success:function(data)
                        {
                            if(data.success){
                                $('#userForm')[0].reset();
                                $('#userModal').modal('hide');
                                $('#action').attr('disabled', false);
                                swal({
                                    title: "Success!",
                                    text: data.success,
                                    icon: "success",
                                });
                                userdataTable.ajax.reload();
                            }else{
                                $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                $('#action').attr('disabled', false);
                            }
                        
                        }
                    });
                }
               
            }   
        });

        //Fetching Data for Update
        $(document).on('click', '.update', function(){
            let user_id = $(this).attr("id");
            let btn_action = 'fetch_single';
            $.ajax({
                url:"user_action.php",
                method:"POST",
                data:{user_id:user_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    $('#userModal').modal('show');
                  
                    $('#name').val(data.user_name);
                    $('#email').val(data.user_email);
                    $('#userRole').val(data.user_role);
                    $('#user_id').val(data.user_id);

                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                    $('#password').attr('required', false);
                    $('#conPassword').attr('required', false)
                }
            })
        });

        //Deactivate and Activate
        $(document).on('click','.delete', function(){
            let user_id = $(this).attr("id");
            let user_status  = $(this).data('status');
            let btn_action = 'delete';
            swal({
                title: "Are you sure?",
                text: "User status will be changed",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"user_action.php",
                        method:"POST",
                        data:{user_id:user_id, user_status:user_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    userdataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                        icon: "danger",
                                    });
                                    userdataTable.ajax.reload();
                            }
                        }
                    });
                } else {
                    swal("Status Won't be changed");
                }
            });    
        });
      
        //Hide Password
        $('#password-show-icon').click(function(){
            $('#password').attr("type", "text");
            $('#password-hide-icon').removeClass('hide-icon');
            $(this).addClass('hide-icon');
        });

        //Show Password
        $('#password-hide-icon').click(function(){
            $('#password').attr("type", "password");
            $('#password-show-icon').removeClass('hide-icon');
            $(this).addClass('hide-icon');
        });

    </script>
</body>
</html>