<?php
include 'includes/DB.php';
include 'includes/Query.php';


$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  //loading product
  if($_POST['btn_action'] == 'fetch_product')
  {
    $sqlBind = $_POST['product_number'];
    $query = "SELECT * FROM products WHERE product_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if($count > 0){
        foreach( $result as $row => $product){
            $output['product_id'] = $product['product_id'];
            $output['product_cost'] = $product['retail_price'];
            $output['product_name'] = $product['product_name'];
            $output['product_unit'] = $product['product_unit'];
        }
    }
    echo json_encode($output);
      
  }

  if($_POST['btn_action'] == 'fetch_by_barcode')
  {
    $sqlBind = $_POST['product_number'];
    $query = "SELECT * FROM products WHERE product_barcode = '$sqlBind' || manufacturer_barcode = '$sqlBind'";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if($count > 0){
        foreach( $result as $row => $product){
           
            $output['product_id'] = $product['product_id'];
            $output['product_cost'] = $product['retail_price'];
            $output['product_name'] = $product['product_name'];
            $output['product_unit'] = $product['product_unit'];
        }
    }
    echo json_encode($output);
      
  }


  if($_POST['btn_action'] == 'Add')
  {
    
    $get_item = 'inventory_number';
    $table = 'inventory_overview';
    $cond = 'inventory_overview_id';
    $limit = 1;
    $status = 1;
   
    $cashier_id = $_POST['user_id'];   
    $inventory_number = $Qobject->invoiceNumber($get_item,$table, $cond,$limit);;    
    $inventory_order_total = $_POST['checkOutValue'];  
    $payment_type = $_POST['payment_type'];          
    $inventory_order_status = $status;  
    $inventory_order_created_date = date('Y-m-d H:i:s');
    $message[] = '';

    $data = array(
      'cashier_id' => $cashier_id,
      'inventory_number' => $inventory_number, 	
      'inventory_order_total' => $inventory_order_total, 	
      'payment_type'  => $payment_type,	
      'inventory_order_status' => $inventory_order_status,	
      'inventory_order_created_date' => $inventory_order_created_date
    );

    $result = $Qobject->insert($table, $data);
    $last_id = $Qobject->DBconnect->lastInsertId();
   
    if($result)
    {
         
      //for loop to update inventory overview product.
      for($count = 0; $count<count($_POST["product_id"]); $count++){
				
        $innerTable = 'inventory_order_product';
			
        $inventory_overview_id = $last_id;
        $inventory_product_id = $_POST['product_id'][$count];
        $inventory_product_name = $_POST['product_name'][$count];
        $inventory_quantity  = $_POST['quantity'][$count];
        $inventory_product_unit  = $_POST['unit'][$count];
        $inventory_price = $_POST['pricePerUnit'][$count];
        $inventory_total_price = $_POST['itemTotalPrice'][$count];
        $inventory_order_date = date('Y-m-d H:i:s');
        

        $data = array(
          'inventory_overview_id' => $inventory_overview_id,
          'inventory_product_id' => $inventory_product_id, 	
          'inventory_product_name' => $inventory_product_name, 	
          'inventory_quantity'  => $inventory_quantity,
          'inventory_product_unit' => $inventory_product_unit,	
          'inventory_price' => $inventory_price,
          'inventory_total_price' => $inventory_total_price,	
          'inventory_order_date' => $inventory_order_date
        );

        $innerresult = $Qobject->insert($innerTable, $data);
        
        if($innerresult){

          //Selecting quantity by ID
          $inner_sub_query = "SELECT product_id, recorded_level FROM products WHERE product_id = $inventory_product_id ";
      
          $inner_sub_result = $Qobject->select($inner_sub_query);
          $inner_sub_count = $Qobject->table_row_count($inner_sub_query);
          
          if($inner_sub_count > 0){
              //add to the old to new and update the value
              foreach ($inner_sub_result as $row => $product ){
                $id = $product['product_id'];
                $newQuantity  =  $product['recorded_level'] - $inventory_quantity ; 

                //updating quantity
                $table = 'products';
                $data =  array('recorded_level' => $newQuantity );
                $cond = "product_id =$id";
                $result = $Qobject->update($table, $data, $cond);
                
                if($result)
                {
                  //insert into history table so that we can generate article report
                  $innerTable4 = 'product_history';
                  $prod_hist_prod_id 	=  $id;
                  $hist_action = 'Sold' ; 
                  $former_level = $product['recorded_level'];
                  $current_level = $newQuantity;
                  $action_by_id = $cashier_id;
                  $prod_hist_date  =   date('Y-m-d H:i:s');	

                  $data =  array(
                    'prod_hist_prod_id' => $id,
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
        }
        else{

          $query = "DELETE  FROM inventory_order_product WHERE inventory_overview_id =  $inventory_overview_id";
          $result = $Qobject->delete($query);
          if($result){

            $message['error'] = 'Order could not be created, an error occured';

          }
          
        }
			
			}
      $message['success'] = 'Congrats! New Order Created';
      $message['value'] =  $last_id;
      
    }
    else {

      $message['error'] = 'Purchase Order Could not be created, an error occured';
       
    }

    echo json_encode($message);
  
  }

  //View invoice Details in Modal
  if($_POST['btn_action'] == 'invoice_details')
  {
    $sqlBind = $_POST['product_id'];
    $query = "
    SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE inventory_overview_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    {
      	 	 	 	 	 	 		 	 
      foreach ($result as $row => $invoice) {
        $output .='<div class="col-12 title"> <h2>MUTTYFEM SUPERMARKET </h2> </div>';
        $output .= '<div class="col-6">
            DATE: <b>'.$Qobject->date_string($invoice["inventory_order_created_date"]).'</b>
        </div>';  
        $output .= '<div class="col-6">
            INVOICE NO:  <b>'.$invoice["inventory_number"].'</b>
        </div>'; 

        $output .='<div class="col-12">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td>NO</td>
                    <td>ITEM</td>
                    <td>QUANTITY</td>
                    <td>PRICE</td>
                    <td>TOTAL</td>
                </tr>
            </thead>
            <tbody>';
        
        $query2 = "
        SELECT * FROM inventory_order_product WHERE inventory_overview_id = $sqlBind
        ";

        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $id = 1;
        foreach ($result2 as $row => $item) {
          $output.='<tr>
            <td>'.$id++.'</td>
            <td>'.$item["inventory_product_name"].'</td>
            <td>'.$item["inventory_quantity"].'</td>
            <td>'.number_format($item["inventory_price"],2).'</td> 
            <td>'.number_format($item["inventory_total_price"],2).'</td>
          </tr>';
        }

        $output .= ' </tbody>
          </table>
        </div>';
        $output.='<div class="col-12 right-align">
                TOTAL: <b>'.number_format($invoice["inventory_order_total"],2).'</b>
            </div>';
        $output.='<div class="col-12 right-align">
               PAYMENT TYPE: <b>'.$invoice["payment_type"].'</b>
            </div>';
        $output.='<div class="col-12 right-align">
            CASHIER: <b>'.$invoice["user_name"].'</b>
        </div>';

      }
    }

    echo $output;
  }
  
  //Fetch Cashiers POS History
  if($_POST['btn_action'] == 'history')
  {
    $query = '';
    $sqlBind = $_POST['user_id'];
    $output = array();
   
    $query .= "
    SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE cashier_id = $sqlBind 
    ";
   
    if(isset($_POST["search"]["value"]))
    { 
        $query .= 'AND(';
        $query .= 'user_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR inventory_number LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR payment_type LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR inventory_order_created_date LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= ')';

    }
   
    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY inventory_overview_id DESC ';
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
      if($row['inventory_order_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Paid</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Unpaid</span>';
      }

                                   
        $sub_array = array();  
        $sub_array[] = $row['inventory_number'];
        $sub_array[] = $row['user_name'];
        $sub_array[] = number_format($row['inventory_order_total'],2);
        $sub_array[] = $row['payment_type'];
        $sub_array[] = $Qobject->date_string($row['inventory_order_created_date']);
        $sub_array[] = $status;

      
        $sub_array[] = '<a href="printReceipt.php?invoiceNUmber='.$row["inventory_overview_id"].'" target="_blank" class="btn btn-success">PDF <i class="fas fa-file-pdf"></a>';
        $sub_array[] = '<button name="view" id="'.$row["inventory_overview_id"].'" class="btn btn-xsx btn-warning view" >view <i class="fas fa-eye"></i></button>';
       
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM inventory_overview WHERE cashier_id  = $sqlBind";
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
