<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{


  if($_POST['btn_action'] == 'Add')
  {
   
    $table = 'procurements';
   	
    $procurement_item = $_POST['procurement']; 
    $procurement_amount = $_POST['amount'];  
    $procurement_description = $_POST['description'];
    $procurement_status = 1;
   
    $procurement_date = date('Y-m-d H:i:s');
    $message[] = '';

   
    $data = array(
      'procurement_item' => $procurement_item,
      'procurement_amount' => $procurement_amount, 	
      'procurement_description' => $procurement_description, 	
      'procurement_status'  => $procurement_status,	
      'procurement_date' => $procurement_date
      
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      $message['success'] = 'Congrats! Procured Item Added';
      
    }
    else {
        $message['error'] = 'Item could not be added, an error occured';
        
    }

    echo json_encode($message);
  }

  

  if($_POST['btn_action'] == 'delete')
  {
      $table = 'procurements';
      $status = '1';
      $delId = $_POST['procurement_id'];
      
      $dispstat = '';
      if($_POST['procurement_status'] == 1)
      {
        $dispstat = 'inactive';
      }
      else{
        $dispstat = 'active';
      }
      if($_POST['procurement_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('procurement_status' => $status );
      $cond = "procurement_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data["success"] = ' Procured item status changed ' .$dispstat;
      
      }
      else {
        $data['error'] = ' Procured item status not updated';
  
      }
      echo json_encode($data);
  }

 
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    
    $query .= "
      SELECT * FROM procurements 
    ";
   
    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE procurement_item LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR procurement_description LIKE "%'.$_POST["search"]["value"].'%" ';
    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY procurement_id DESC ';
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
      if($row['procurement_status'] == 1)
      {
        $status = '<span class="badge badge-xs bg-success">Active</span>';
      }
      else {
        $status = '<span class="badge badge-xs bg-danger">Inactive</span>';
      }

                       
                                       
        $sub_array = array();  
        $sub_array[] = $id++;
        $sub_array[] = $row['procurement_item'];
        $sub_array[] = $row['procurement_amount'];
        $sub_array[] = $row['procurement_description'];      
        $sub_array[] = $Qobject->date_string($row['procurement_date']);

        $sub_array[] = $status;

        
        
   
        $sub_array[] = '<button name="delete" id="'.$row["procurement_id"].'" class="btn btn-xsx btn-danger delete"  data-status="'.$row["procurement_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
            
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM procurements";
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
