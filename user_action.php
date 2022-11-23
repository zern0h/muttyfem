<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  //save user

  if($_POST['btn_action'] == 'Add')
  {
     	 	 	 	 	 	  
    
    $table = 'users';
    $user_name = $_POST['name'];
    $user_email =  $_POST['email'];
    $user_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user_role =  $_POST['user_role'];
    $user_status = 1;
    $user_created_date = date('Y-m-d H:i:s');
    $data[] = '';

    $data = array(
      'user_name' => $user_name ,
      'user_email' => $user_email ,
      'user_password' => $user_password ,
      'user_role' => $user_role ,
      'user_status' => $user_status ,
      'user_created_date' => $user_created_date 
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      
      $data['success'] = 'Success! Congrats, New User Added';
    }
    else {
      $data['error'] = 'User could not be added, an error occured';
    }

    echo json_encode($data);
  }


  if($_POST['btn_action'] == 'fetch_single')
  {
    $sqlBind  = $_POST['user_id'];
    $query = "SELECT * FROM users WHERE user_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if ($count > 0)
    {
      foreach ($result as $row => $user) {
        $output['user_id'] = $user['user_id'];
        $output['user_name'] = $user['user_name'];
        $output['user_email'] = $user['user_email'];
        $output['user_role'] = $user['user_role'];
      }
    }

    echo json_encode($output);
  }

  if($_POST['btn_action'] == 'Edit')
  {

    $table = 'users';
    $user_name = $_POST['name'];
    $user_email =  $_POST['email'];
    $user_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user_role =  $_POST['user_role'];
    
    $editId = $_POST['user_id'];

    $report[] = '';

    if($_POST['password'] != '')
		{
        $data = array(
          'user_name' => $user_name ,
          'user_email' => $user_email ,
          'user_password' => $user_password ,
          'user_role' => $user_role 
        );
		}
		else
		{
      $data = array(
        'user_name' => $user_name ,
        'user_email' => $user_email ,
        'user_role' => $user_role 
      );
    }
    
    $cond = "user_id=$editId";

    $result = $Qobject->update($table, $data, $cond);
    if($result)
    {
      $report['success'] = 'Success! User profile successfully updated';
    }
    else {
      $report['error'] = 'An errror occured, user profile could not be added';
      
    }
		echo json_encode($report);
  }


  if($_POST['btn_action'] == 'delete')
  {
      $table = 'users';
      $status = '1';
      $delId = $_POST['user_id'];
      $data[] = '';
      $dispstat = '';
      if($_POST['user_status'] == 1)
      {
        $dispstat = 'Inactive';
      }
      else{
        $dispstat = 'active';
      }

      if($_POST['user_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('user_status' => $status );
      $cond = "user_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data['success'] = 'Success! User status changed ' .$dispstat;
        
      }
      else {
        $data['error'] = ' Error! User status not changed';
      }

      echo json_encode($data);
  }


  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    $query .= "
    SELECT * FROM users
    ";

    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE user_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR user_email LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR user_role LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR user_created_date LIKE "%'.$_POST["search"]["value"].'%" ';

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY user_id DESC ';
    }

    if($_POST['length'] != -1)
    {
    	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $statement = $Qobject->select($query);

    $data = array();
    $filtered_rows = $Qobject->table_row_count($query);

    foreach($statement as $row)
    {
      $status = '';
      if($row['user_status'] == 1)
      {
        $status = '<span class="badge bg-success">Active</span>';
      }
      else {
        $status = '<span class="badge bg-danger">Inactive</span>';
      }
    	$sub_array = array();
      $sub_array[] = $row['user_id'];
    	$sub_array[] = $row['user_name'];
    	$sub_array[] = $row['user_email'];
      $sub_array[] = $row['user_role'];
    	$sub_array[] = $Qobject->date_string($row['user_created_date']);
     
      $sub_array[] = $status;
      $sub_array[] = '<button name="update" id="'.$row["user_id"].'" class="btn btn-xs btn-warning update" >Update <i class="fas fa-pen"></i></button>';
      $sub_array[] = '<button name="delete" id="'.$row["user_id"].'" class="btn btn-xs btn-danger delete"  data-status="'.$row["user_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
   
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM users";
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
