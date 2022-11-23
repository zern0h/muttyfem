<?php
include 'includes/DB.php';
include 'includes/Query.php';
require_once '../dompdf/autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  //View invoice Details in Modal
  if($_POST['btn_action'] == 'load_inventory_details')
  {
      
      $sqlBind = $_POST['product_id'];
      $query = "
          SELECT product_cost_price FROM `products` WHERE product_id = $sqlBind";

      $result = $Qobject->select($query);
      $count = $Qobject->table_row_count($query);

      if($count > 0)
      {                               
          foreach ($result as $row => $inventory) {
            $output['product_cost_price'] = $inventory['product_cost_price'];
          }
      }

      echo json_encode($output);
  }
  
  //Save refund records to the database
  if($_POST['btn_action'] =='Add'){

    $table = 'expiry_damage_report';

    $d_e_product_id = $_POST['product_id'];
    $q_e_qty = $_POST['refund_qty'];
    $q_e_unit_cost = $_POST['itemCost'];
    $q_e_total_cost = $_POST['totalCost'];
    $d_e_condition = $_POST['refundCondition']; 	
    $d_e_action = $_POST['refundAction'];
    $d_e_comment = $_POST['refundComment']; 	
    $d_e_action_by = $_POST['entered_by'];
    $d_e_date = date('Y-m-d H:i:s');
    
    $message[] = '';

    $data = array(
      'd_e_product_id' => $d_e_product_id,
      'q_e_qty' => $q_e_qty, 	
      'q_e_unit_cost' => $q_e_unit_cost, 	
      'q_e_total_cost'  => $q_e_total_cost,	
      'd_e_condition' => $d_e_condition,	
      'd_e_action' => $d_e_action,
      'd_e_comment' => $d_e_comment, 	
      'd_e_action_by'  => $d_e_action_by,	
      'd_e_date' => $d_e_date,
    );

    $result = $Qobject->insert($table, $data);

    if($result){
      
        $innerQuery = "SELECT recorded_level FROM products WHERE product_id = $d_e_product_id";
      
        $innerResult = $Qobject->select($innerQuery);
        $innerCount = $Qobject->table_row_count($innerQuery);

        if($innerCount > 0)
        {
            foreach($innerResult as $product)
            {
                $newQuantity = $product['recorded_level'] - $q_e_qty;

                //updating products table
                $innerTable = 'products';
                $innerData =  array('recorded_level' => $newQuantity );
                $cond = "product_id =$d_e_product_id";

                $innerResult2 = $Qobject->update($innerTable, $innerData, $cond);
            
                
                if($innerResult2)
                {
                    
                    //insert into history table so that we can generate article report
                    $innerTable4 = 'product_history';
                    $prod_hist_prod_id 	=  $d_e_product_id;
                    
                    $hist_action = $d_e_condition .' and '. $d_e_action; 
                    $former_level = $product['recorded_level'];
                    $current_level = $newQuantity;
                    $action_by_id = $d_e_action_by;
                    $prod_hist_date  =   date('Y-m-d H:i:s');	

                    $data =  array(
                        'prod_hist_prod_id' => $prod_hist_prod_id,
                        'hist_action' => $hist_action,
                        'action_by_id' => $action_by_id,
                        'former_level' => $former_level,
                        'current_level' => $current_level,
                        'prod_hist_date' => $prod_hist_date           
                    );
                    $Qobject->insert($innerTable4, $data);
                }
            }
        }
        $message['success'] = 'Congrats! New Refund Created'; 
    }
    else{
      $message['error'] = 'Something Went Wrong';
    }

    echo json_encode($message);
    
  }
  
  //fetchomg refund details to be viewed
  if($_POST['btn_action'] == 'refund_details')
  {
    $sqlBind = $_POST['refund_id'];
    $query = "
      SELECT * FROM `expiry_damage_report` INNER JOIN products ON products.product_id = expiry_damage_report.d_e_product_id INNER JOIN users on users.user_id = expiry_damage_report.d_e_action_by WHERE damage_expiry_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    {
     	 

      foreach ($result as $row => $refund) {
        
        $output.= '<div class="col-12">Product Name: '.$refund['product_name'].'</div>
           
            <div class="col-3">Damaged/Expired Quantity: '.$refund['q_e_qty'].'</div>
            <div class="col-3">Unit Cost: '.$refund['q_e_unit_cost'].'</div>
            <div class="col-3">Total Cost: '.$refund['q_e_total_cost'].'</div>
            <div class="col-3">Item Condition: '.$refund['d_e_condition'].'</div>
            <div class="col-6"> Action: '.$refund['d_e_action'].'</div>
            <div class="col-6">Created By: '.$refund['user_name'].'</div>
            <div class="col-4">Creation Date: '. $Qobject->date_string($refund['d_e_date']).'</div>
            <div class="col-12"> Comment: '.$refund['d_e_comment'].' </div>';
      }
    }

    echo $output;
  }


  //Loading Expiry/Damage Report Datatable
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    
    $query .= "
      SELECT * FROM `expiry_damage_report` INNER JOIN products ON products.product_id = expiry_damage_report.d_e_product_id INNER JOIN users on users.user_id = expiry_damage_report.d_e_action_by  
    ";
   
    if(isset($_POST["search"]["value"]))
    {
        $query .= 'WHERE product_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR q_e_qty LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR d_e_condition LIKE "%'.$_POST["search"]["value"].'%" ';
    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY damage_expiry_id DESC ';
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
     

      $sub_array = array();  
      $sub_array[] = $id++;
      $sub_array[] = $row['product_name'];
      $sub_array[] = $row['q_e_qty'];
      $sub_array[] = $row['q_e_total_cost'];
      $sub_array[] = $row['d_e_condition'];
      $sub_array[] = $row['user_name'];
      $sub_array[] = $Qobject->date_string($row['d_e_date']);
      
    
      $sub_array[] = '<button name="view" id="'.$row["damage_expiry_id"].'" class="btn btn-xsx btn-info view" >view <i class="fas fa-eye"></i></button>';
     
    
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM expiry_damage_report";
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
