<?php 
    session_start(); 
    if(!isset($_SESSION['type']))
    {
        header("location:login.php");
    }
    if($_SESSION['type'] !== "Super_Admin"){
        header("location:login.php");
    }
    $title = 'Suppliers | Clover Cuties';
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
                    <div class="card">
                        <div class="card-header">
                        <div class="row">
                                <div class="col-lg-8">
                                    <h3>
                                        Suppliers/Vendors Table
                                    </h3>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#supplierModal" id="supplierInput">
                                        Add Supplier <span class="fas fa-plus-circle"></span></button>   
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-content">
                            <table id="supplierData"> 	 	 	
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Supplier Code</th>
                                        <th>Phone NO</th>
                                        <th>Status</th>
                                        <th>View</th>
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
    

        <!--View Modal -->
        <div class="modal fade" id="supplierViewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="supplierViewModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Supplier View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row supplier_data_view">
                    
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

        <!--Add Supplier and Edit Modal -->
        <div class="modal fade" id="supplierModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
            <div class=" modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">Supplier Registration</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                <form class="row g-3" id="supplierForm" >
                    <div  id="gen-valid"></div>
                    <div class="col-md-6">
                        <label for="supplierName" class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="supplierName" id="supplierName" placeholder="Input the supplier's company name">
                    </div>
                    <div class="col-md-6">
                        <label for="customerMail" class="form-label">Supplier Email</label>
                        <input type="email" class="form-control" name="supplierMail" id="supplierMail" placeholder="Input the supplier's email address">
                    </div>
                    <div class="col-md-6">
                        <label for="phoneNumber1" class="form-label">Phone Number1</label>
                        <input type="text" class="form-control" name="phoneNumber1" id="phoneNumber1" placeholder="Input the supplier's Phone Number">
                    </div>
                    <div class="col-md-6">
                        <label for="phoneNumber2" class="form-label">Phone Number2</label>
                        <input type="etxt" class="form-control" name="phoneNumber2" id="phoneNumber2" placeholder="Input the supplier's Phone Number 2">
                    </div>
                    <div class="col-12">
                        <label for="supplierAddress" class="form-label">Supplier Address</label>
                        <input type="text" class="form-control" name="supplierAddress1"id="supplierAddress1" placeholder="1234 Main St">
                    </div>
                    <div class="col-12">
                        <label for="supplierAddress2" class="form-label">Supplier Address 2</label>
                        <input type="text" class="form-control" name="supplierAddress2" id="supplierAddress2" placeholder="Apartment, studio, or floor">
                    </div>
                    <div class="col-md-6">
                        <label for="supplierCity" class="form-label">City/Town</label>
                        <input type="text" class="form-control" name="supplierCity" id="supplierCity">
                    </div>
                    <div class="col-md-6">
                        <label for="supplierState" class="form-label">State</label>
                        <select name="supplierState" id="supplierState" class="form-select">
                            <option disabled selected>--Select State--</option>
                            <option value="Abia">Abia</option>
                            <option value="Adamawa">Adamawa</option>
                            <option value="Akwa Ibom">Akwa Ibom</option>
                            <option value="Anambra">Anambra</option>
                            <option value="Bauchi">Bauchi</option>
                            <option value="Bayelsa">Bayelsa</option>
                            <option value="Benue">Benue</option>
                            <option value="Borno">Borno</option>
                            <option value="Cross Rive">Cross River</option>
                            <option value="Delta">Delta</option>
                            <option value="Ebonyi">Ebonyi</option>
                            <option value="Edo">Edo</option>
                            <option value="Ekiti">Ekiti</option>
                            <option value="Enugu">Enugu</option>
                            <option value="FCT">Federal Capital Territory</option>
                            <option value="Gombe">Gombe</option>
                            <option value="Imo">Imo</option>
                            <option value="Jigawa">Jigawa</option>
                            <option value="Kaduna">Kaduna</option>
                            <option value="Kano">Kano</option>
                            <option value="Katsina">Katsina</option>
                            <option value="Kebbi">Kebbi</option>
                            <option value="Kogi">Kogi</option>
                            <option value="Kwara">Kwara</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Nasarawa">Nasarawa</option>
                            <option value="Niger">Niger</option>
                            <option value="Ogun">Ogun</option>
                            <option value="Ondo">Ondo</option>
                            <option value="Osun">Osun</option>
                            <option value="Oyo">Oyo</option>
                            <option value="Plateau">Plateau</option>
                            <option value="Rivers">Rivers</option>
                            <option value="Sokoto">Sokoto</option>
                            <option value="Taraba">Taraba</option>
                            <option value="Yobe">Yobe</option>
                            <option value="Zamfara">Zamfara</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <input type="hidden" name="supplier_entered_by" id="supplier_entered_by" value=" <?= $_SESSION["user_id"]; ?>">
                        <input type="hidden" name="supplier_id" id="supplier_id" />
                        <input type="hidden" name="btn_action" id="btn_action" value="" />
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                      
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    
                    <button type="button" class="btn btn-danger close" data-bs-dismiss="modal">Close </button>
                </div>
                </div>
            </div>
        </div>
        <!--End Add Supplier and Edit Modal -->
    <!-- Modal -->


    <!-- End Modal -->

    <!-- Scripts -->
    <?php include 'partials/scripts.php'; ?>

    <script>

        //Fetching Datatable
        $(document).ready(function(){    
            let btn_action = 'load_table'
            supplierDataTable = $('#supplierData').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url:"suppliers_action.php",
                    type:"POST",
                    data:{btn_action:btn_action}
                },
                "columnDefs":[
                    {
                        "targets":[5,6,7],
                        "orderable":false,
                    },
                ],
                "pageLength": 25
            });
        });

        //opening modal
        $('#supplierInput').click(function(){
            $('#supplierForm')[0].reset();
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

        // close the modal
        $('.close').click(function(){
            $('#supplierForm')[0].reset();
            $('#supplierModal').modal('hide');
            $('#btn_action').val("Add");
            $('#action').val('Add');
        });

        //Adding and Editing 
        $('#action').click(function(e){
            e.preventDefault();
            $('#action').attr('disabled', 'disabled');
            let supplierName = $('#supplierName').val();
            let supplierMail = $('#supplierMail').val(); 
            let phoneNumber1 = $('#phoneNumber1').val();
            let phoneNumber2 = $('#phoneNumber2').val();
            let supplierAddress1 = $('#supplierAddress1').val();
            let supplierAddress2 = $('#supplierAddress2').val();
            let supplierCity = $('#supplierCity').val();
            let supplierState = $('#supplierState').val();
            let supplierEnteredBy = $('#supplier_entered_by').val();
            let supplierId = $('#supplier_id').val();
           
            let btn_action = $('#btn_action').val();
            if(btn_action =='Add')
            {
                if(supplierName == '' || supplierMail == '' || phoneNumber1 == '' || supplierAddress1 == '' || supplierCity == '' || supplierState == '' || supplierEnteredBy == '' ){
                    $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields Are Required Except Address2 <button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $('#action').attr('disabled', false);
                }
                else{    
                    $.ajax({
                        url:"suppliers_action.php",
                        method:"POST",
                        data:{supplierName:supplierName, supplierMail:supplierMail, phoneNumber1:phoneNumber1, phoneNumber2:phoneNumber2,supplierAddress1:supplierAddress1, supplierAddress2:supplierAddress2, supplierCity:supplierCity, supplierState:supplierState, supplierEnteredBy:supplierEnteredBy,btn_action:btn_action},
                        dataType:"JSON",
                        success:function(data)
                        {
                            if(data.success){
                                $('#supplierForm')[0].reset();
                                $('#supplierModal').modal('hide');
                                $('#action').attr('disabled', false);
                                swal({
                                    title: "Success!",
                                    text: data.success,
                                    icon: "success",
                                });
                                supplierDataTable.ajax.reload();
                            }else{
                                $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                $('#action').attr('disabled', false);
                            }                            
                        }
                    });
                }
            }
            else if (btn_action == 'Edit')
            {
                if(supplierName == '' || supplierMail == '' || phoneNumber1 == '' ||supplierAddress1 == '' || supplierCity == '' || supplierState == '' || supplierEnteredBy == '' || supplierId == '' ){
                    $('#gen-valid').fadeIn().html('<div class="alert alert-warning alert-dismissible fade show" role="alert">All Fields Except Address2 are required<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $('#action').attr('disabled', false);  
                }
                else{    
                    $.ajax({
                        url:"suppliers_action.php",
                        method:"POST",
                        data:{supplierName:supplierName, supplierMail:supplierMail, phoneNumber1:phoneNumber1, phoneNumber2:phoneNumber2,supplierAddress1:supplierAddress1, supplierAddress2:supplierAddress2, supplierCity:supplierCity, supplierState:supplierState, supplierId:supplierId,btn_action:btn_action},
                        dataType:"JSON",
                        success:function(data)
                        {
                            if(data.success){
                                $('#supplierForm')[0].reset();
                                $('#supplierModal').modal('hide');
                                $('#action').attr('disabled', false);
                                swal({
                                    title: "Success!",
                                    text: data.success,
                                    icon: "success",
                                });
                                supplierDataTable.ajax.reload();
                            }else{
                                $('#gen-valid').fadeIn().html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button"class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                $('#action').attr('disabled', false);
                            }    
                        }
                    });
                }
            }
            else
            {
                swal({
                    title: "Warning!",
                    text: "Try to check for empty fields",
                    icon: "warning",
                });   
            }
        });

        //Fetch data and set them in the edit modal
        $(document).on('click', '.update', function(){
            let supplier_id = $(this).attr("id");
            let btn_action = 'fetch_single';
            $.ajax({
                url:"suppliers_action.php",
                method:"POST",
                data:{supplier_id:supplier_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                { 
                    $('#supplierModal').modal('show');
                    $('#supplierName').val(data.supplierName);
                    $('#supplierMail').val(data.supplierMail);
                    $('#phoneNumber1').val(data.phone_number1);
                    $('#phoneNumber2').val(data.phone_number2);
                    $('#supplierAddress1').val(data.supplierAddress1);
                    $('#supplierAddress2').val(data.supplierAddress2);
                    $('#supplierCity').val(data.supplierCity);
                    $('#supplierState').val(data.supplierState);
                    $('#supplier_id').val(data.supplierId);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit'); 
                }
            })
        });

        //Deactivate and Activate Supplier
        $(document).on('click','.delete', function(){
            let supplier_id = $(this).attr("id");
            let supplier_status  = $(this).data('status');
            let btn_action = 'delete';
            swal({
                title: "Are you sure?",
                text: "Supplier status will be changed",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"suppliers_action.php",
                        method:"POST",
                        data:{supplier_id:supplier_id, supplier_status:supplier_status, btn_action:btn_action},
                        dataType: "JSON",
                        success:function(data)
                        {
                            if(data.success){
                                    swal(data.success, {
                                        icon: "success",
                                    });
                                    supplierDataTable.ajax.reload();
                            }else{
                                swal(data.error, {
                                    icon: "danger",
                                });
                                supplierDataTable.ajax.reload();
                            }
                        }
                    });
                } else {
                    swal("Status Won't be changed");
                }
            });    
        });

        //Fetch Data for Supplier View
        $(document).on('click','.view', function(){
            let supplierId = $(this).attr("id");
            let btn_action = 'supplier_details';
            $.ajax({
                url:"suppliers_action.php",
                method:"POST",
                data:{supplierId:supplierId, btn_action:btn_action},
                success:function(data)
                {
                    $('#supplierViewModal').modal('show');
                    $('.supplier_data_view').html(data);
                }
            });
            
        });

    </script>
</body>
</html>