<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  //save user

  if($_POST['btn_action'] == 'Add')
  {
    $table = 'categories';
    $cat_name = $_POST['cat_name'];
    $cat_status = 1;
    $cat_created_date = date('Y-m-d H:i:s');
    
    $report[] = '';
    $data = array(
      'cat_name' => $cat_name ,
      'cat_status' => $cat_status ,
      'cat_created_on' => $cat_created_date 
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      $report['success'] = 'Congrats! New Category Added';
    }
    else {
      $report['error'] = 'Category could not be added, an error occured';
    }
    echo json_encode($report);
  }


  if($_POST['btn_action'] == 'fetch_single')
  {
    $sqlBind  = $_POST['cat_id'];
    $query = "SELECT * FROM categories WHERE cat_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if ($count > 0)
    {
      foreach ($result as $row => $player) {
        $output['cat_id'] = $player['cat_id'];
        $output['cat_name'] = $player['cat_name'];
      }
    }

    echo json_encode($output);
  }

  if($_POST['btn_action'] == 'Edit')
  {

    $table = 'categories';
    $cat_name = $_POST['cat_name'];   
    $editId = $_POST['cat_id'];

    $report[] = '';
    $data = array(
        'cat_name' => $cat_name,
    );
  
    
    $cond = "cat_id=$editId";

    $result = $Qobject->update($table, $data, $cond);
    if($result)
    {
      $report['success'] = 'Category successfully updated';
    }
    else {
      $report['error'] = 'An errror occured, Category could not be updated';
    }
		echo json_encode($report);
  }


  if($_POST['btn_action'] == 'delete')
  {
      $table = 'categories';
      $status = '1';
      $delId = $_POST['cat_id'];
      $report[] = '';
      $dispstat = '';
      if($_POST['cat_status'] == 1)
      {
        $dispstat = 'Inactive';
      }
      else{
        $dispstat = 'Active';
      }

      if($_POST['cat_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('cat_status' => $status );
      $cond = "cat_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $report['success'] = 'Category status changed ' .$dispstat;
      }
      else {
        $report['error'] = ' Category status not updated';
      }
    echo json_encode($report);
  }


  if($_POST['btn_action'] == 'load_table')
  {
        $query = '';

        $output = array();
        $query .= "
        SELECT * FROM categories
        ";

        if(isset($_POST["search"]["value"]))
        {
            $query .= 'WHERE cat_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $query .= 'OR cat_created_on LIKE "%'.$_POST["search"]["value"].'%" ';

        }

        if(isset($_POST['order']))
        {
            $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY cat_id DESC ';
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
            if($row['cat_status'] == 1)
            {
                $status = '<span class="badge badge-xs bg-success">Active</span>';
            }
            else {
                $status = '<span class="badge badge-xs bg-danger">Inactive</span>';
            }

            $sub_array = array();
            $sub_array[] = $row['cat_id'];
            $sub_array[] = $row['cat_name'];
            $sub_array[] = $Qobject->date_string($row['cat_created_on']);
        

          $sub_array[] = $status;
          $sub_array[] = '<button name="update" id="'.$row["cat_id"].'" class="btn btn-xsx btn-warning update" >Update <i class="fas fa-pen"></i></button>';
          $sub_array[] = '<button name="delete" id="'.$row["cat_id"].'" class="btn btn-xsx btn-danger delete"  data-status="'.$row["cat_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
           
          $data[] = $sub_array;
        }

        $sql = "SELECT * FROM categories";
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
