<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{
  //Adding products to the database
  if($_POST['btn_action'] == 'Add')
  {
    $get_item = 'supplier_code';
    $table = 'suppliers';
    $cond = 'supplier_id';
    $limit = 1;

    $query = "SELECT * FROM $table";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
     	 	 	 	 	 	 	 	 	 	 	 	 	 	
    $supplierName = $_POST['supplierName'];  
    $supplierCode = $Qobject->supplierNumber($get_item,$table, $cond,$limit);
    $supplierMail = $_POST['supplierMail'];  
    $phoneNumber1 = $_POST['phoneNumber1'];  
    $phoneNumber2 = $_POST['phoneNumber2']; 
    $supplierAddress1 =   $_POST['supplierAddress1'];
    $supplierAddress2 = $_POST['supplierAddress2'];       
    $supplierCity = $_POST['supplierCity'];      
    $supplierState = $_POST['supplierState'];    
    $supplierEnteredBy = $_POST['supplierEnteredBy'];      
    $supplierCreated = date('Y-m-d H:i:s');

    $message[] = '';
   	 	 	 	 	 	 	 	 	 
    $data = array(
      'supplier_name' => $supplierName,
      'phone_number1' => $phoneNumber1,
      'phone_number2' => $phoneNumber2,
      'supplier_email' => $supplierMail, 	
      'supplier_address1' => $supplierAddress1, 	
      'supplier_address2'  => $supplierAddress2,	
      'supplier_city' => $supplierCity,	
      'supplier_state' => $supplierState,
      'supplier_code' => $supplierCode,
      'supplier_created_by' => $supplierEnteredBy, 	
      'time_created' => $supplierCreated
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      $message['success'] = 'Congrats! New Supplier Added';
      
    }
    else {
        $message['error'] = 'Supplier could not be added, an error occured';
        
    }

    echo json_encode($message);
  }

  //fetchomg supplier details to be viewed
  if($_POST['btn_action'] == 'supplier_details')
  {
    $sqlBind = $_POST['supplierId'];
    $query = "
    SELECT * FROM suppliers INNER JOIN users ON users.user_id = suppliers.supplier_created_by  WHERE supplier_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    {
      	 	 	 	 	 	 		 	 
      foreach ($result as $row => $supplier) {
        $status = '';
        if($supplier['supplier_status'] == 1)
        {
          $status = '<span class="badge bg-xs bg-success">Active</span>';
        }
        else {
          $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
        }   
        $output.= '<div class="col-6">Supplier Name: '.$supplier['supplier_name'].'</div>
            <div class="col-6">Supplier Email: '.$supplier['supplier_email'].'</div>
            <div class="col-6">Phone Number1: '.$supplier['phone_number1'].'</div>
            <div class="col-6">Phone Number2: '.$supplier['phone_number2'].'</div>
            <div class="col-12">Address1: '.$supplier['supplier_address1'].'</div>
            <div class="col-12">Address2: '.$supplier['supplier_address2'].'</div>
            <div class="col-4">Supplier NO: '.$supplier['supplier_code'].'</div>
            <div class="col-4">City: '.$supplier['supplier_city'].'</div>
            <div class="col-4">State: '.$supplier['supplier_state'].'</div>
            <div class="col-6">Status: '.$status.'</div>
            <div class="col-6">Creation Date: '.$Qobject->date_string($supplier['time_created']).'</div>
            <div class="col-12">Creation By: '.$supplier['user_name'].'</div>';
            
      }
    }

    echo $output;
  }
  
  //Fetch single supplier details for update
  if($_POST['btn_action'] == 'fetch_single')
  {
    $sqlBind  = $_POST['supplier_id'];
    $query = "SELECT * FROM suppliers WHERE supplier_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    

    if ($count > 0)
    {
      foreach ($result as $row => $supplier) {
        $output['supplierId'] = $supplier['supplier_id'];
        $output['supplierName'] = $supplier['supplier_name'];
        $output['supplierMail'] = $supplier['supplier_email'];
        $output['phone_number1'] = $supplier['phone_number1'];
        $output['phone_number2'] = $supplier['phone_number2'];
        $output['supplierAddress1'] = $supplier['supplier_address1'];
        $output['supplierAddress2'] = $supplier['supplier_address2'];
        $output['supplierCity'] = $supplier['supplier_city'];
        $output['supplierState'] = $supplier['supplier_state'];
       
      }
    }

    echo json_encode($output);
  }

  //Editing supplier
  if($_POST['btn_action'] == 'Edit')
  {
    $table = 'suppliers';

    $supplierName = $_POST['supplierName'];  
    $supplierMail = $_POST['supplierMail']; 
    $phoneNumber1 = $_POST['phoneNumber1'];  
    $phoneNumber2 = $_POST['phoneNumber2']; 
    $supplierAddress1 = $_POST['supplierAddress1'];        
    $supplierAddress2 = $_POST['supplierAddress2']; 
    $supplierCity = $_POST['supplierCity'];       
    $supplierState = $_POST['supplierState'];     

    $report[] = '';
    $editId = $_POST['supplierId'];
    if($supplierName != '' && $phoneNumber1 != ''  && $supplierMail != '' && $supplierAddress1 != '' && $supplierCity != '' && $supplierState != ''){
      $data = array(	
        'supplier_name' => $supplierName,
        'phone_number1' => $phoneNumber1,
        'phone_number2' => $phoneNumber2, 	
        'supplier_email' => $supplierMail,	
        'supplier_address1' => $supplierAddress1,	
        'supplier_address2' => $supplierAddress2,
        'supplier_city' => $supplierCity, 	
        'supplier_state' => $supplierState, 	
      );
    }
    
    $cond = "supplier_id=$editId";

    $result = $Qobject->update($table, $data, $cond);
    if($result)
    {
      $report['success'] = 'Supplier successfully updated';
      
    }
    else {
      $report['error'] = 'An errror occured, Supplier could not be added';
    }
    echo json_encode($report);
  }

  //Activate and Deactivate Supplier
  if($_POST['btn_action'] == 'delete')
  {
      $table = 'suppliers';
      $status = '1';
      $delId = $_POST['supplier_id'];
      
      $dispstat = '';
      if($_POST['supplier_status'] == 1)
      {
        $dispstat = 'inactive';
      }
      else{
        $dispstat = 'active';
      }
      if($_POST['supplier_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('supplier_status' => $status );
      $cond = "supplier_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data["success"] = ' Supplier status changed ' .$dispstat;
      
      }
      else {
        $data['error'] = ' Supplier status not updated';
  
      }
      echo json_encode($data);
  }

  //Fetches Data for Datatable
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    
    $query .= "
    SELECT * FROM suppliers INNER JOIN users ON users.user_id = suppliers.supplier_created_by 
    ";
   
    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR time_created LIKE "%'.$_POST["search"]["value"].'%" ';
     

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY supplier_id DESC ';
    }

    if($_POST['length'] != -1)
    {
    	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $statement = $Qobject->select($query);

    $data = array();
    $filtered_rows = $Qobject->table_row_count($query);
    $id = 1;
    foreach($statement as $row)
    {
      $status = '';
      if($row['supplier_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Active</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
      }
                        
    	$sub_array = array();  
      $sub_array[] = $id++;
      $sub_array[] = $row['supplier_name'];
    	$sub_array[] = $row['supplier_code'];
      $sub_array[] = $row['phone_number1'];
    
      $sub_array[] = $status;

      $sub_array[] = '<button name="update" id="'.$row["supplier_id"].'" class="btn btn-xs btn-success view" >View <i class="fas fa-eye"></i></button>';
     
      $sub_array[] = '<button name="update" id="'.$row["supplier_id"].'" class="btn btn-xs btn-warning update" >Update <i class="fas fa-pen"></i></button>';
      $sub_array[] = '<button name="delete" id="'.$row["supplier_id"].'" class="btn btn-xs btn-danger delete"  data-status="'.$row["supplier_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM suppliers";
    $total_row = $Qobject->table_row_count($sql);

    $output = array(
    	"draw"    			=> 	intval($_POST["draw"]),
    	"recordsTotal"  	=>  $filtered_rows,
    	"recordsFiltered" 	=> $total_row,
    	"data"    			=> 	$data
    );

    echo json_encode($output);

  }

}

?>
