<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  //fectch purchase order details for the proof of delivery form
  if($_POST['btn_action'] == 'load_pod_details')
  {
    $sqlBind  = $_POST['pod_code'];
    $query = "
    SELECT * FROM purchase_order_overview INNER JOIN users ON users.user_id = purchase_order_overview.po_created_by INNER JOIN suppliers on suppliers.supplier_id = purchase_order_overview.po_company WHERE po_number = '$sqlBind'
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
     
    if ($count > 0)
    {
 
      foreach ($result as $row => $pod) {
        $output['supplier_id'] = $pod['po_company'];
        $output['supplierName'] = $pod['supplier_name'];
        $output['podNumber'] = $pod['po_number'];
        $output['po_id'] = $pod['po_overview_id'];
        $output['raised_by'] = $pod['user_name'];
        
      }
    }

    echo json_encode($output);
  }

  //function to load all products attributed to a purchase_order
  if($_POST['btn_action'] == 'fetch_P_Order_Products')
  {
    $sqlBind = $_POST['poNum'];
    $query = "SELECT * FROM `purchase_order_products` INNER JOIN products on products.product_id = purchase_order_products.product_ordered_id WHERE po_overview_number = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    
    if($count > 0)
    {
      
      $id = 0;
      foreach($result as $row => $product){
        $id++;

			  $output .= '<span id="row'.$id.'"><div class="row">';
			
          $output .= '<div class="col-md-4">';
                $output .= '<input type="text" name="product_name[]" id="product_name'.$id.'" class="form-control disable"  required value="'.$product['product_name'].'">
                <input type="hidden" name="product_id[]" id="product_id'.$id.'" value="'.$product['product_ordered_id'].'">';
          $output .= '</div>';

          $output .= '<div class="col-md-3">';
            $output .= '<input type="text" name="quantity[]" id="quantity'.$id.'" class="form-control" required value="'.$product['po_product_qty'].'" > ';
          $output .= '</div>';

         

          $output .= '<div class="col-md-3">';
            $output .= '<input type="text" name="pricePerUnit[]" class="form-control" value="'.$product['po_unit_cost'].'">';
          $output .='</div>'; 

          $output .= '<div class="col-md-2"><button type="button" name="remove" id="'.$id.'" class="btn btn-danger btn-xs remove">Remove</button></div>';
        $output .= '</div></span>';

      }
      echo $output;

    }



  }

  //Fetch purchase order overview related data to aid payment
  if($_POST['btn_action'] == 'pay'){
    $sqlBind  = $_POST['pod_id'];
    $query = "SELECT pod_overview_total, payment_made FROM proof_of_delivery WHERE pod_overview_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
 
    if ($count > 0)
    {
      foreach ($result as $row => $supplier) {
        $output['pod_overview_total'] = $supplier['pod_overview_total'];
        $output['payment_made'] = $supplier['payment_made'];
        $output['outstanding'] = $supplier['pod_overview_total'] - $supplier['payment_made'];
      }
    }
    echo json_encode($output);
  }
 

  //Adding delivery to the database as well as updating the quantity and unit price of item
  if($_POST['btn_action'] == 'Add')
  {
    
    $get_item = 'pod_number';
    $table = 'proof_of_delivery';
    $cond = 'pod_overview_id';
    $limit = 1;
    $status = 0;
    
    $receiving_staff_id = $_POST['entered_by'];   
    $po_gen_by = $_POST['raised_by'];  
    $company_id = $_POST['supplier_id'];
    $company_employee_name = $_POST['vendorEmployeeName'];
    $company_driver_name = $_POST['driverName'];
    $pod_number = $Qobject->podNumber($get_item,$table, $cond,$limit);
    $p_order_id = $_POST['po_id'];  
    $pod_overview_total = 0;            
    $pod_overview_status = $status;  
    $pod_creation_time = date('Y-m-d H:i:s');
    $message[] = '';
   		
    $data = array(
      'receiving_staff_id' => $receiving_staff_id,
      'po_gen_by' => $po_gen_by, 	
      'company_id' => $company_id, 	
      'company_employee_name'  => $company_employee_name,	
      'company_driver_name' => $company_driver_name,
      'pod_number' => $pod_number, 	
      'p_order_id' => $p_order_id,
      'pod_overview_total' => $pod_overview_total, 	
      'pod_overview_status'  => $pod_overview_status,	
      'pod_creation_time' => $pod_creation_time
      
    );

    $result = $Qobject->insert($table, $data);
    $last_id = $Qobject->DBconnect->lastInsertId();
   
    if($result)
    {
      $total = 0;
      //for loop to update proof of delivery.
      for($count = 0; $count<count($_POST["product_id"]); $count++)
			{
					
        $innerTable = 'proof_of_delivery_products';

        $pod_overview_number = $last_id;
        $pod_rec_prod_id = $_POST['product_id'][$count];
        $pod_product_quantity = $_POST['quantity'][$count];
        $pod_overview_unit_cost  = $_POST['pricePerUnit'][$count];
        $pod_total_cost =   (int)$_POST['quantity'][$count] * (int)$_POST['pricePerUnit'][$count];
        $pod_date = date('Y-m-d H:i:s');;
        $total += $pod_total_cost;
       

        $data = array(
          'pod_overview_number' => $pod_overview_number,
          'pod_rec_prod_id' => $pod_rec_prod_id, 	
          'pod_product_quantity' => $pod_product_quantity, 	
          'pod_overview_unit_cost'  => $pod_overview_unit_cost,
          'pod_total_cost' => $pod_total_cost,	
          'pod_date' => $pod_date
        );

        $innerresult = $Qobject->insert($innerTable, $data);
        
        if($innerresult){
          //Selecting quantity by ID
          $inner_sub_query = "SELECT product_id, recorded_level FROM products WHERE product_id = $pod_rec_prod_id ";
      
          $inner_sub_result = $Qobject->select($inner_sub_query);
          $inner_sub_count = $Qobject->table_row_count($inner_sub_query);
          $newQuantity = 0;
          if($inner_sub_count > 0){
            //add to the old to new and update the value
            foreach ($inner_sub_result as $row => $product ){
              $id = $product['product_id'];
              $newQuantity  =  $product['recorded_level'] + $pod_product_quantity; 

              //updating quantity and unit cost price
              $table = 'products';
              $data =  array(
                'recorded_level' => $newQuantity,
                 'product_cost_price' => $pod_overview_unit_cost           
              );
              $cond = "product_id =$id";
              $result = $Qobject->update($table, $data, $cond);
              
            }
  
          }
        }else{

          $query = "DELETE  FROM proof_of_delivery WHERE pod_overview_id =  $pod_overview_number";
          $result = $Qobject->delete($query);
          if($result){

            $message['error'] = 'Proof of delivery could not be created, an error occured';

          }
          
        }
				
			}

      //updating total amount
      $table2 = 'proof_of_delivery';
      $data2 =  array('pod_overview_total' => $total );
      $cond2 = "pod_overview_id =$last_id";
      $result2 = $Qobject->update($table2, $data2, $cond2);  

      // updating purchase order status
      $table3 = 'purchase_order_overview';
      $data3 = array('po_overview_status' => 1 );;
      $cond3 = "po_overview_id =$p_order_id";
      $result3 = $Qobject->update($table3, $data3, $cond3);

      $message['success'] = 'Congrats! New Proof of Delivery Created';  

    }
    else {

      $message['error'] = 'Proof of delivery could not be created, an error occured';
       
    }

    echo json_encode($message);

  }

  //Updating payment made on the order delivered
  if($_POST['btn_action'] == 'pay_outstanding'){
    
    $id = $_POST['pod_id'];
    $payment = $_POST['purchase_pamyment'] + $_POST['payment_made'];
    $total = $_POST['total'];
    $status = '';
    if($payment == $total){
      $status = 1;
    }
    else{
      $status = 0;
    }
    //updating quantity
    $table = 'proof_of_delivery';
    $data = array('payment_made' => $payment,
              'pod_overview_status' => $status
            );
    $cond = "pod_overview_id =$id";
    $result = $Qobject->update($table, $data, $cond);
    $message[] = '';
    if($result){
      $message['success'] = 'Payment Successfully Made';
    }
    else{
      $message['error'] = 'Payment Successfully Made';
    }
    echo json_encode($message);
  }
  //fetching purchase order details to be viewed purchase_order_details
  if($_POST['btn_action'] == 'proof_of_delivery_details')
  {
    $sqlBind = $_POST['pod_id'];
    $query = "
    SELECT * FROM proof_of_delivery  INNER JOIN suppliers on suppliers.supplier_id = proof_of_delivery.company_id INNER JOIN purchase_order_overview on purchase_order_overview.po_overview_id = proof_of_delivery.p_order_id INNER JOIN users on users.user_id = proof_of_delivery.receiving_staff_id WHERE pod_overview_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    { 	 	 	 		 	 

      foreach ($result as $row => $purchase_order) {

        $status= '';
        if($purchase_order['pod_overview_status'] == 1)
        {
          $status = '<span class="badge  bg-success">Paid</span>';
        }
        else {
          $status = '<span class="badge  bg-danger">Outstanding</span>';
        } 	 	
        $output .='<div class="col-12 title"> <h2>MUTTYFEM SUPERMARKET</h2> </div>';
        $output .='<div class="col-12 "> <h4>1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA ROAD, OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA </h4> </div>';
        $output .= '<div class="col-6">
          DATE: <b>'.$Qobject->date_string($purchase_order["pod_creation_time"]).'</b>
        </div>';  
        $output .= '<div class="col-6">
          PROOF OF DELIVERY NO: <b>'.$purchase_order["pod_number"].'</b>
        </div>'; 
        $output .= '<div class="col-6">
          PURCHASE ORDER NO:  <b>'.$purchase_order["po_number"].'</b>
        </div>'; 

        $output .='<div class="col-4 "> <h5>SUPPLIER DETAILS </h5> </div>';
        $output .='<div class="col-8 ">SUPPLIER NAME: <b>'.$purchase_order["supplier_name"].'</b> </div>';
        $output .= '<div class="col-6">
          PHONE NUMBER: <b>'.$purchase_order["phone_number1"]. ' '.$purchase_order["phone_number2"].'</b>
        </div>';  
        $output .= '<div class="col-6">
          Email: <b>'.$purchase_order["supplier_email"].'</b>
        </div>'; 
        $output .= '<div class="col-12">
          ADDRESS1: <b>'.$purchase_order["supplier_address1"].'</b>
        </div>'; 
        $output .= '<div class="col-12">
          ADDRESS2: <b>'.$purchase_order["supplier_address2"].'</b>
        </div>';
        $output .= '<div class="col-12">
          CITY & STATE: <b>'.$purchase_order["supplier_city"].' '.$purchase_order["supplier_state"].'</b>
        </div>';
        $output .='<div class="col-12">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td>NO</td>
                    <td>PRODUCT CODE</td>
                    <td>ITEM</td>
                    <td>QUANTITY</td>
                    <td>PRICE</td>
                    <td>TOTAL</td>
                </tr>
            </thead>
            <tbody>';
        
        $query2 = "
        SELECT * FROM proof_of_delivery_products INNER JOIN products ON products.product_id = proof_of_delivery_products.pod_rec_prod_id WHERE pod_overview_number = $sqlBind
        ";
      
        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $id = 1;

        foreach ($result2 as $row => $item) {
          $output.='<tr>
            <td>'.$id++.'</td>
            <td>'.$item["product_barcode"].'</td>
            <td>'.$item["product_name"].'</td>
            <td>'.$item["pod_product_quantity"].'</td>
            <td>'.number_format($item["pod_overview_unit_cost"],2).'</td> 
            <td>'.number_format($item["pod_total_cost"],2).'</td>
          </tr>';
        }

        $output .= ' </tbody>
          </table>
        </div>';
        $output.='<div class="col-4 right-align">
                TOTAL: <b>'.number_format($purchase_order["pod_overview_total"],2).'</b>
            </div>';
        $output.='<div class="col-4 right-align">
                  TOTAL PAID: <b>'.number_format($purchase_order["payment_made"],2).'</b>
              </div>';
        $output.='<div class="col-4 right-align">
                OUTSTANDING PAYMENT: <b>'.number_format(($purchase_order["pod_overview_total"] - $purchase_order["payment_made"]),2).'</b>
            </div>'; 
        $output.='<div class="col-12 right-align">
               PAYMENT STATUS: '.$status.'
            </div>';
        $output.='<div class="col-12 right-align">
            PURCHASE ORDER RAISED BY: <b>'.$purchase_order["po_gen_by"].'</b>
        </div>';
        $output.='<div class="col-12 right-align">
            DELIVERY RECEIVED BY: <b>'.$purchase_order["user_name"].'</b>
        </div>';

      }
    }

    echo $output;
  }
  
  //Fetches Data for Datatable
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
 	
    $query .= "
      SELECT * FROM proof_of_delivery  INNER JOIN suppliers on suppliers.supplier_id = proof_of_delivery.company_id
      ";
   
    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE pod_number LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR pod_overview_total LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR pod_overview_status LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR pod_creation_time LIKE "%'.$_POST["search"]["value"].'%" ';

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY pod_overview_id DESC ';
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
      $status= '';
      if($row['pod_overview_status'] == 1)
      {
        $status = '<span class="badge  bg-success">Paid</span>';
      }
      else {
        $status = '<span class="badge  bg-danger">Outstanding</span>';
      }
                                       
    	$sub_array = array();  
      $sub_array[] = $id++;
      $sub_array[] = $row['pod_number'];
      $sub_array[] = $row['supplier_name'];
    	$sub_array[] =  number_format($row['pod_overview_total'],2);
      $sub_array[] = $Qobject->date_string($row['pod_creation_time']);
      $sub_array[] = $status;

      $sub_array[] = '<button name="view" id="'.$row["pod_overview_id"].'" class="btn btn-xs btn-success view" >View <i class="fas fa-eye"></i></button>';
      if($_POST['pay'] == 'payment'){
        $sub_array[] = '<button name="pay" id="'.$row["pod_overview_id"].'" class="btn btn-xs btn-warning pay" >PAY <i class="fas fa-dollar-sign"></i></button>';
      }
      $sub_array[] = '<a href="printProofOfDelivery.php?podNum='.$row["pod_overview_id"].'" target="_blank" class="btn btn-info ">PDF <i class="fas fa-file-pdf"></a>';
      
         	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM purchase_order_overview";
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
