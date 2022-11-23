<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  if($_POST['btn_action'] == 'Add')
  {
    $table = 'brands';  	 	 	 	 
    $brand_name = $_POST['brand_name'];
    $brand_status = 1;
    $brand_created_on = date('Y-m-d H:i:s');;
    $data[] = '';

    $data = array(
      'brand_name' => $brand_name ,
      'brand_status' => $brand_status ,
      'brand_created_on' => $brand_created_on
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      $data['success'] = 'Congrats! New Brand Added';
      
    }
    else {
        $data['error'] = 'Brand could not be added, an error occured';
        
    }

    echo json_encode($data);
  }

  if($_POST['btn_action'] == 'fetch_single')
  {
    $sqlBind  = $_POST['brand_id'];
    $query = "SELECT * FROM brands WHERE brand_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    

    if ($count > 0)
    {
      foreach ($result as $row => $brand) {
        $output['brand_id'] = $brand['brand_id'];
        $output['brand_name'] = $brand['brand_name'];
      }
    }

    echo json_encode($output);
  }

  if($_POST['btn_action'] == 'Edit')
  {

    $table = 'brands';

    $brand_name = $_POST['brand_name'];
    $brand_id = $_POST['brand_id'];
   
    $report[] = '';
    $editId = $_POST['brand_id'];
  
    $data =  array(
      'brand_name' => $brand_name,
      'brand_id' => $brand_id,
    );
 
    
   
     $cond = "brand_id=$editId";

     $result = $Qobject->update($table, $data, $cond);
     if($result)
     {
       $report['success'] = 'Category successfully updated';
       
     }
     else {
       $report['error'] = 'An errror occured, Category could not be added';
     }
     echo json_encode($report);
  }


  if($_POST['btn_action'] == 'delete')
  {
      $table = 'brands';
      $status = '1';
      $delId = $_POST['brand_id'];
      
      $dispstat = '';
      if($_POST['brand_status'] == 1)
      {
        $dispstat = 'inactive';
      }
      else{
        $dispstat = 'active';
      }
      if($_POST['brand_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('brand_status' => $status );
      $cond = "brand_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data["success"] = ' Brand status changed ' .$dispstat;
      
      }
      else {
        $data['error'] = ' Player status not updated';
  
      }
      echo json_encode($data);
  }


  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    $query .= "
      SELECT * FROM brands
    ";

    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE brand_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR brand_created_on  LIKE "%'.$_POST["search"]["value"].'%" ';

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY brand_id DESC ';
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
      if($row['brand_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Active</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
      }
    	$sub_array = array();  
      $sub_array[] = $row['brand_id'];
    	$sub_array[] = $row['brand_name'];
      $sub_array[] = $Qobject->date_string($row['brand_created_on']);

      $sub_array[] = $status;

      $sub_array[] = '<button name="update" id="'.$row["brand_id"].'" class="btn btn-xs btn-warning update" >Update <i class="fas fa-pen"></i></button>';
      $sub_array[] = '<button name="delete" id="'.$row["brand_id"].'" class="btn btn-xs btn-danger delete"  data-status="'.$row["brand_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM brands";
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
