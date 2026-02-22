<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  //fectch supplier details for the purchase order form
  if($_POST['btn_action'] == 'load_supplier_details')
  {
    $sqlBind  = $_POST['supplier_code'];
    $query = "SELECT * FROM suppliers WHERE supplier_code = '$sqlBind'";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
 
    if ($count > 0)
    {
      foreach ($result as $row => $supplier) {
        $output['supplier_id'] = $supplier['supplier_id'];
        $output['supplier_name'] = $supplier['supplier_name'];
        $output['supplier_email'] = $supplier['supplier_email'];
        $output['phone_number1'] = $supplier['phone_number1'];
        $output['phone_number2'] = $supplier['phone_number2'];
        $output['supplier_address1'] = $supplier['supplier_address1'];
        $output['supplier_address2'] = $supplier['supplier_address2'];
        $output['supplier_city'] = $supplier['supplier_city'];
        $output['supplier_state'] = $supplier['supplier_state'];
      }
    }

    echo json_encode($output);
  }

  //function to load all products attributed to a supplier
  if($_POST['btn_action'] == 'fetch_supplier_products')
  {
    $sqlBind = $_POST['supplier_number'];
    $query = "SELECT * FROM products WHERE product_vendor_id = $sqlBind";
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
                <input type="hidden" name="product_id[]" id="product_id'.$id.'" value="'.$product['product_id'].'">';
          $output .= '</div>';

          $output .= '<div class="col-md-3">';
            $output .= '<input type="text" name="quantity[]" id="quantity'.$id.'" class="form-control" required placeholder="qty">';
          $output .= '</div>';

          $output .= '<div class="col-md-3">';
            $output .= '<input type="text" name="pricePerUnit[]" class="form-control disable" value="'.$product['product_cost_price'].'">';
          $output .='</div>'; 

          $output .= '<div class="col-md-2"><button type="button" name="remove" id="'.$id.'" class="btn btn-danger btn-xs remove">Remove</button></div>';
        $output .= '</div></span>';

      }
      echo $output;

    }

  } 

  //Adding purchase order to the database
  if($_POST['btn_action'] == 'Add')
  {

    $get_item = 'po_number';
    $table = 'purchase_order_overview';
    $cond = 'po_overview_id';
    $limit = 1;
    $status = 0;
    
    $po_created_by = $_POST['entered_by'];   
    $po_company = $_POST['supplier_id'];  
    $po_number = $Qobject->purchaseOrderNumber($get_item,$table, $cond,$limit);;    
    $po_overview_total = 0;            
    $po_overview_status = $status;  
    $po_creation_time = date('Y-m-d H:i:s');
    $message[] = '';
    //po_overview_id 	po_created_by 	po_company 	po_number 	po_overview_total 	po_overview_status 	po_creation_time 	
    $data = array(
      'po_created_by' => $po_created_by,
      'po_company' => $po_company, 	
      'po_number' => $po_number, 	
      'po_overview_total'  => $po_overview_total,	
      'po_overview_status' => $po_overview_status,
      'po_creation_time' => $po_creation_time
    );

    $result = $Qobject->insert($table, $data);
    $last_id = $Qobject->DBconnect->lastInsertId();
   
    if($result)
    {
      $total = 0;
      //for loop to update purchase order  product.
      for($count = 0; $count<count($_POST["product_id"]); $count++)
			{
				
        $innerTable = 'purchase_order_products';

        $po_overview_number = $last_id;
        $product_ordered_id = $_POST['product_id'][$count];
        $po_product_qty = $_POST['quantity'][$count];
        $po_unit_cost  = $_POST['pricePerUnit'][$count];
        $po_total_cost =   $_POST['quantity'][$count] * $_POST['pricePerUnit'][$count] ;
        $po_date = date('Y-m-d H:i:s');;
        $total += $po_total_cost;
        //po_product_id 	po_overview_number 	product_ordered_id 	po_product_qty po_pk_size 	po_unit_cost 	po_total_cost 	po_date 	
        $data = array(
          'po_overview_number' => $po_overview_number,
          'product_ordered_id' => $product_ordered_id, 	
          'po_product_qty' => $po_product_qty, 	
          'po_unit_cost'  => $po_unit_cost,
          'po_total_cost' => $po_total_cost,	
          'po_date' => $po_date
        );

        $innerresult = $Qobject->insert($innerTable, $data);

        if($innerresult)
        {
          //updating total amount
          $table = 'purchase_order_overview';
          $data =  array('po_overview_total' => $total );
          $cond = "po_overview_id =$last_id";
          $result = $Qobject->update($table, $data, $cond); 

          $message['success'] = 'Congrats! New Purchase Order Created';  

        }
        else
        {

          $query = "DELETE  FROM purchase_order_overview WHERE po_overview_id =  $po_overview_number";
          $result = $Qobject->delete($query);
          if($result){

            $message['error'] = 'Purchase could not be created, an error occured';

          }

        }
				
			}
     
    }
    else {

      $message['error'] = 'Purchase Order Could not be created, an error occured';
       
    }

    echo json_encode($message);

  }
  
  //fetching purchase order details to be viewed purchase_order_details
  if($_POST['btn_action'] == 'purchase_order_details')
  {

    $sqlBind = $_POST['purchase_order_id'];
    $query = "
    SELECT * FROM purchase_order_overview INNER JOIN users ON users.user_id = purchase_order_overview.po_created_by INNER JOIN suppliers on suppliers.supplier_id = purchase_order_overview.po_company WHERE po_overview_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    { 	 	 	 		 	 
      foreach ($result as $row => $purchase_order) {

        $status= '';
        if($purchase_order['po_overview_status'] == 1)
        {
          $status = '<span class="badge  bg-success">Closed</span>';
        }
        else {
          $status = '<span class="badge  bg-danger">Open</span>';
        } 	 	
        $output .='<div class="col-12 title"> <h2>MUTTYFEM SUPERMARKET</h2> </div>';
        $output .='<div class="col-12 "> <h4>1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA ROAD, OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA </h4> </div>';

        
        $output .= '<div class="col-6">
          DATE: <b>'.$Qobject->date_string($purchase_order["po_creation_time"]).'</b>
        </div>';  
        $output .= '<div class="col-6">
          PURCHASE ORDER NO:  <b>'.$purchase_order["po_number"].'</b>
        </div>'; 

        $output .='<div class="col-4 "> <h5>SUPPLIER DETAILS </h5> </div>';
        $output .='<div class="col-8 ">SUPPLIER NAME: <b>'.$purchase_order["supplier_name"].'</b> </div>';
        $output .= '<div class="col-6">
          SUPPLIER NUMBER: <b>'.$purchase_order["supplier_code"].'</b>
        </div>';
        $output .= '<div class="col-6">
          PHONE NUMBER: <b>'.$purchase_order["phone_number1"]. ' '.$purchase_order["phone_number2"].'</b>
        </div>';  
        $output .= '<div class="col-12">
          Email: <b>'.$purchase_order["supplier_email"].'</b>
        </div>'; 
        $output .= '<div class="col-12">
          ADDRESS1: <b>'.$purchase_order["supplier_address1"].'</b>
        </div>'; 
        $output .= '<div class="col-12">
          ADDRESS2: <b>'.$purchase_order["supplier_address2"].'</b>
        </div>';
        $output .= '<div class="col-12">
          CITY & STATE: <b>'.$purchase_order["supplier_city"].', '.strtoupper($purchase_order["supplier_state"]).' STATE</b>
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
        SELECT * FROM purchase_order_products INNER JOIN products ON products.product_id = purchase_order_products.product_ordered_id INNER JOIN purchase_order_overview ON purchase_order_overview.po_overview_id = purchase_order_products.po_overview_number  WHERE po_overview_number = $sqlBind
        ";
      
        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $id = 1;
        foreach ($result2 as $row => $item) {
          $output.='<tr>
            <td>'.$id++.'</td>
            <td>'.$item["product_name"].'</td>
            <td>'.$item["po_product_qty"].'</td>
            <td>'.number_format($item["po_unit_cost"],2).'</td> 
            <td>'.number_format($item["po_total_cost"],2).'</td>
          </tr>';
        }

        $output .= ' </tbody>
          </table>
        </div>';
        $output.='<div class="col-4 right-align">
                TOTAL: <b>'.number_format($purchase_order["po_overview_total"],2).'</b>
            </div>';
        
        $output.='<div class="col-12 right-align">
               PURCHASE ORDER STATUS: '.$status.'
            </div>';
        $output.='<div class="col-12 right-align">
            STAFF: <b>'.$purchase_order["user_name"].'</b>
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
      SELECT * FROM purchase_order_overview INNER JOIN suppliers on suppliers.supplier_id = purchase_order_overview.po_company
      ";
   
    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE po_number LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR po_overview_total LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR po_overview_status LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR po_creation_time LIKE "%'.$_POST["search"]["value"].'%" ';

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY po_overview_id DESC ';
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
      if($row['po_overview_status'] == 1)
      {
        $status = '<span class="badge  bg-success">Closed</span>';
      }
      else {
        $status = '<span class="badge  bg-danger">Open</span>';
      }
                                       
    	$sub_array = array();  
      $sub_array[] = $id++;
      $sub_array[] = $row['po_number'];
      $sub_array[] = $row['supplier_name'];
    	$sub_array[] =  number_format($row['po_overview_total'],2);
      $sub_array[] = $Qobject->date_string($row['po_creation_time']);
      $sub_array[] = $status;

      $sub_array[] = '<button name="view" id="'.$row["po_overview_id"].'" class="btn btn-xs btn-success view" >View <i class="fas fa-eye"></i></button>';

      $sub_array[] = '<a href="printPurchaseOrder.php?orderNum='.$row["po_overview_id"].'" target="_blank" class="btn btn-info ">PDF <i class="fas fa-file-pdf"></a>';
      
         	
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
