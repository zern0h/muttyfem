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
      $sqlBind1 = $_POST['invoice_id'];
      $sqlBind2 = $_POST['product_id'];
      $query = "
          SELECT * FROM `inventory_order_product` WHERE inventory_overview_id = $sqlBind1 AND inventory_product_id =  $sqlBind2 
      ";

      $result = $Qobject->select($query);
      $count = $Qobject->table_row_count($query);

      if($count > 0)
      {                               
          foreach ($result as $row => $inventory) {
              $output['inventory_price'] = $inventory['inventory_price'];
              $output['inventory_quantity'] = $inventory['inventory_quantity'];
              $output['inventory_total_price'] = $inventory['inventory_total_price'];   
          }
      }

      echo json_encode($output);
  }

  //View invoice Total Value in modal
  if($_POST['btn_action'] == 'load_inventory_total')
  {
    $sqlBind = $_POST['invoice_id'];
    $query = "
      SELECT inventory_order_total FROM inventory_overview WHERE inventory_overview_id = $sqlBind
    ";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if($count > 0)
    {                               
      foreach ($result as $row => $inventory) {
        $output['inventory_order_total'] = $inventory['inventory_order_total'];   
      }
    }
    echo json_encode($output);
  }

  //Save refund records to the database
  if($_POST['btn_action'] =='Add'){

    $table = 'refunds';

    $refund_inventory_id = $_POST['invoice_number'];
    $refund_product_id = $_POST['product_id'];
    $refund_quantity = $_POST['refund_qty'];
    $refund_product_condition = $_POST['refundCondition'];
    $refund_action = $_POST['refundAction'];
    $refund_comment = $_POST['refundComment'];
    $refund_payout = $_POST['refundPayout'];
    $refund_status = 0;
    $refund_created_by = $_POST['entered_by'];
    $refund_creation_date = date('Y-m-d H:i:s');

    $newOverallTotal = $_POST['overall_total_cost'] - $_POST['refundPayout'];
    $newInvProdTotal = $_POST['total_cost'] - $_POST['refundPayout'];
    $newInvProdQty =  $_POST['total_qty'] - $_POST['refund_qty'];
    $message[] = '';

    $data = array(
      'refund_inventory_id' => $refund_inventory_id,
      'refund_product_id' => $refund_product_id, 	
      'refund_quantity' => $refund_quantity, 	
      'refund_product_condition'  => $refund_product_condition,	
      'refund_action' => $refund_action,	
      'refund_action' => $refund_action,
      'refund_comment' => $refund_comment, 	
      'refund_payout'  => $refund_payout,	
      'refund_status' => $refund_status,
      'refund_created_by' => $refund_created_by, 	
      'refund_creation_date'  => $refund_creation_date
    );

    $result = $Qobject->insert($table, $data);
    
    if($result){
      
      $prod_id = $refund_product_id;
      $inv_over_id = $refund_inventory_id;
      
      //updating total and quantity
      $table = 'inventory_order_product';
      $data =  array(
        'inventory_quantity' => $newInvProdQty,
        'inventory_total_price' => $newInvProdTotal
      );
      $cond = "inventory_product_id = $prod_id AND inventory_overview_id = $inv_over_id";
      $result = $Qobject->update($table, $data, $cond);

      if($result){
        $id = $refund_inventory_id;
      
        //updating total
        $table = 'inventory_overview';
        $data =  array(
          'inventory_order_total' => $newOverallTotal
        );
        $cond = "inventory_overview_id =$id";
        $result = $Qobject->update($table, $data, $cond);
        $message['success'] = 'Congrats! New Refund Created'; 
      }
      
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
    SELECT * FROM refunds INNER JOIN inventory_overview ON inventory_overview.inventory_overview_id = refunds.refund_inventory_id INNER JOIN products ON products.product_id = refunds.refund_product_id INNER JOIN users ON users.user_id = refunds.refund_created_by WHERE refund_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    {
     	 
      foreach ($result as $row => $refund) {
        $status = '';
        if($refund['refund_status'] == 1)
        {
          $status = '<span class="badge bg-xs bg-success">Completed</span>';
        }
        else {
          $status = '<span class="badge bg-xs bg-danger">Pending</span>';
        }

                
        $output.= '<div class="col-6">Product Name: '.$refund['product_name'].'</div>
            <div class="col-6">Invoice NO:'.$refund['inventory_number'].'</div>
            <div class="col-3">Refunded Quantity: '.$refund['refund_quantity'].'</div>
            <div class="col-3">Refund Payout: '.$refund['refund_payout'].'</div>
            <div class="col-3">Refund Status: '.$status.'</div>
            <div class="col-3">Refund Condition: '.$refund['refund_product_condition'].'</div>
            <div class="col-3">Refund Action: '.$refund['refund_action'].'</div>
            <div class="col-4">Created By: '.$refund['user_name'].'</div>
            <div class="col-4">Creation Date: '. $Qobject->date_string($refund['refund_creation_date']).'</div>
            <div class="col-12">Refund Comment: '.$refund['refund_comment'].' </div>';
      }
    }

    echo $output;
  }

  //Activating and deactivating status
  if($_POST['btn_action'] == 'activation')
  {
      $table = 'refunds';
      $status = '1';
      $delId = $_POST['refund_number'];
      
      $dispstat = '';
      if($_POST['refund_status'] == 1)
      {
        $dispstat = 'Pending';
      }
      else{
        $dispstat = 'Completed';
      }
      if($_POST['refund_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('refund_status' => $status );
      $cond = "refund_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data["success"] = ' Refund status changed ' .$dispstat;
      
      }
      else {
        $data['error'] = ' Refund status not updated';
  
      }
      echo json_encode($data);
  }

  //Loading Invoice Datatable
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    
    $query .= "
      SELECT * FROM refunds INNER JOIN inventory_overview ON inventory_overview.inventory_overview_id = refunds.refund_inventory_id INNER JOIN products ON products.product_id = refunds.refund_product_id INNER JOIN users ON users.user_id = refunds.refund_created_by
    ";
   
    if(isset($_POST["search"]["value"]))
    {
        $query .= 'WHERE product_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR inventory_number LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR refund_product_condition LIKE "%'.$_POST["search"]["value"].'%" ';
    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY refund_id DESC ';
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
      if($row['refund_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Completed</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Pending</span>';
      }

      $sub_array = array();  
      $sub_array[] = $id++;
      $sub_array[] = $row['product_name'];
      $sub_array[] = $row['refund_quantity'];
      $sub_array[] = $row['refund_payout'];
      $sub_array[] = $row['inventory_number'];
      $sub_array[] = $row['refund_action'];
      $sub_array[] = $row['refund_product_condition'];  
      $sub_array[] = $Qobject->date_string($row['refund_creation_date']);
      $sub_array[] = $status;
    
      $sub_array[] = '<button name="view" id="'.$row["refund_id"].'" class="btn btn-xsx btn-info view" >view <i class="fas fa-eye"></i></button>';
      $sub_array[] = '<button name="delete" id="'.$row["refund_id"].'" class="btn btn-xs btn-warning delete"  data-status="'.$row["refund_status"].'" >Activate <i class="fas fa-times-circle"></i></button>';
    
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM refunds";
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
