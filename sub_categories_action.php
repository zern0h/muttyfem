<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  if($_POST['btn_action'] == 'Add')
  {
    $table = 'sub_categories';  	 	 	 	 
    $sub_category_name = $_POST['sub_category_name'];
    $category_id = $_POST['category'];
    $sub_category_status = 1;
    $sub_category_date = date('Y-m-d H:i:s');;
    $data[] = '';

    $data = array(
      'sub_category_name' => $sub_category_name ,
      'category_id' => $category_id ,
      'sub_category_status' => $sub_category_status ,
      'sub_category_date' => $sub_category_date
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      $data['success'] = 'Congrats! New Sub Cateogry Added';
      
    }
    else {
        $data['error'] = 'Sub Category could not be added, an error occured';
        
    }

    echo json_encode($data);
  }

  if($_POST['btn_action'] == 'fetch_single')
  {
    $sqlBind  = $_POST['sub_cat_id'];
    $query = "SELECT * FROM sub_categories WHERE sub_category_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    

    if ($count > 0)
    {
      foreach ($result as $row => $brand) {
        $output['sub_category_id'] = $brand['sub_category_id'];
        $output['category_id'] = $brand['category_id'];
        $output['sub_category_name'] = $brand['sub_category_name'];
      }
    }

    echo json_encode($output);
  }

  if($_POST['btn_action'] == 'Edit')
  {
    $table = 'sub_categories';

    $sub_cat_name = $_POST['sub_category_name'];
    $sub_cat_id = $_POST['sub_category_id'];
    $category_id = $_POST['category'];
   
    $report[] = '';
    $editId = $_POST['sub_category_id'];
   
    $data =  array(
        'sub_category_name' => $sub_cat_name,
        'category_id' => $category_id      
    );
   
     $cond = "sub_category_id=$editId";

     $result = $Qobject->update($table, $data, $cond);
     if($result)
     {
       $report['success'] = 'Sub Category successfully updated';
       
     }
     else {
       $report['error'] = 'An errror occured,Sub Category could not be added';
     }
     echo json_encode($report);
  }


  if($_POST['btn_action'] == 'delete')
  {
    //sub_category_id	sub_category_name	category_id	sub_category_status	sub_category_date
      $table = 'sub_categories';
      $status = '1';
      $delId = $_POST['sub_cat_id'];
      
      $dispstat = '';
      if($_POST['sub_cat_status'] == 1)
      {
        $dispstat = 'inactive';
      }
      else{
        $dispstat = 'active';
      }
      if($_POST['sub_cat_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('sub_category_status' => $status );
      $cond = "sub_category_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data["success"] = 'Sub Category status changed ' .$dispstat;
      
      }
      else {
        $data['error'] = 'Sub Category status not updated';
  
      }
      echo json_encode($data);
  }


  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    $query .= "
      SELECT * FROM sub_categories INNER JOIN categories ON categories.cat_id = sub_categories.category_id
    ";

    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE sub_category_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR cat_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR sub_category_date  LIKE "%'.$_POST["search"]["value"].'%" ';

    }
	

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY sub_category_id DESC ';
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
        if($row['sub_category_status'] == 1)
        {
            $status = '<span class="badge bg-xs bg-success">Active</span>';
        }
        else {
            $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
        }
        //sub_category_id 	sub_category_name 	category_id 	sub_category_status 	sub_category_date
        $sub_array = array();  
        $sub_array[] = $row['sub_category_id'];
    	$sub_array[] = $row['sub_category_name'];
        $sub_array[] = $row['cat_name'];
        $sub_array[] = $Qobject->date_string($row['sub_category_date']);

        $sub_array[] = $status;

        $sub_array[] = '<button name="update" id="'.$row["sub_category_id"].'" class="btn btn-xs btn-warning update" >Update <i class="fas fa-pen"></i></button>';
        $sub_array[] = '<button name="delete" id="'.$row["sub_category_id"].'" class="btn btn-xs btn-danger delete"  data-status="'.$row["sub_category_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM sub_categories";
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
