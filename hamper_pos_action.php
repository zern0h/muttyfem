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
        $query = "SELECT * FROM hamper_overview WHERE hamper_overview_id = $sqlBind";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);

        if($count > 0){
            foreach( $result as $row => $product){
                $output['hamper_overview_id'] = $product['hamper_overview_id'];
                $output['hamper_total_cost'] = $product['hamper_total_cost'];
                $output['hamper_name'] = $product['hamper_name'];
            
            }
        }
        echo json_encode($output);
        
    }

    if($_POST['btn_action'] == 'fetch_by_barcode')
    {
        $sqlBind = $_POST['product_number'];
        $query = "SELECT * FROM hamper_overview WHERE hamper_code = '$sqlBind'";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);

        if($count > 0){
            foreach( $result as $row => $product){
                $output['hamper_overview_id'] = $product['hamper_overview_id'];
                $output['hamper_total_cost'] = $product['hamper_total_cost'];
                $output['hamper_name'] = $product['hamper_name'];
            
            }
        }
        echo json_encode($output);
        
    }


    if($_POST['btn_action'] == 'Add')
    {
    
        $get_item = 'hamper_sales_number';
        $table = 'hamper_sales_overview';
        $cond = 'hamper_sales_overview_id';
        $limit = 1;
        $status =1;
        
        $cashier_id = $_POST['user_id'];   
        $hamper_sales_number = $Qobject->hamperSaleNum($get_item,$table, $cond,$limit);;    
        $hamper_sales_total = $_POST['checkOutValue'];  
        $hamper_payment_type = $_POST['payment_type'];          
        $hamper_sales_status = $status;  
        $hamper_sales_creation_date = date('Y-m-d H:i:s');
        $message[] = '';

        $data = array(
        'hamper_sales_number' => $hamper_sales_number, 	
        'cashier_id' => $cashier_id,
        'hamper_sales_total' => $hamper_sales_total, 	
        'hamper_payment_type'  => $hamper_payment_type,	
        'hamper_sales_status' => $hamper_sales_status,	
        'hamper_sales_creation_date' => $hamper_sales_creation_date
        );

        $result = $Qobject->insert($table, $data);
        $last_id = $Qobject->DBconnect->lastInsertId();
    
        if($result)
        {
            
            //for loop to update inventory overview product.
            for($count = 0; $count<count($_POST["product_id"]); $count++){
                    
                $innerTable = 'hamper_sales';
                    
                $hamper_sales_overview_key = $last_id;
                $hamper_id = $_POST['product_id'][$count];
                $hamper_sales_name = $_POST['product_name'][$count];
                $hamper_sales_qty  = $_POST['quantity'][$count];
                
                $hamper_sales_unit_price = $_POST['pricePerUnit'][$count];
                $hamper_sales_total = $_POST['itemTotalPrice'][$count];
                $hamper_sales_date = date('Y-m-d H:i:s');
                
                $data = array(
                'hamper_sales_overview_key' => $hamper_sales_overview_key,
                'hamper_id' => $hamper_id, 	
                'hamper_sales_name' => $hamper_sales_name, 	
                'hamper_sales_qty'  => $hamper_sales_qty,
                'hamper_sales_unit_price' => $hamper_sales_unit_price,	
                'hamper_sales_total' => $hamper_sales_total,	
                'hamper_sales_date' => $hamper_sales_date
                );

                $innerresult = $Qobject->insert($innerTable, $data);

                if($innerresult){
                    $query3 = "SELECT hamper_overview_id, hamper_quantity FROM hamper_overview WHERE hamper_overview_id = $hamper_id";
                    $result3 = $Qobject->select($query3);
                    $count3 = $Qobject->table_row_count($query3);
          
                    if($count3 > 0){

                        foreach($result3 as $row){
                            $newQty = $row['hamper_quantity'] - $hamper_sales_qty;

                            //update quantity 
                            $table3 = 'hamper_overview';
                            $data =  array('hamper_quantity' => $newQty);
                            $cond = "hamper_overview_id =$hamper_id";
                            $result4 = $Qobject->update($table3, $data, $cond);
                            
                           if($result4)
                           {
                               $query5 = "SELECT hamper_item_product_id,hamper_item_quanity FROM `hamper_items` WHERE hamper_overview_key = $hamper_id ";

                               $result5 = $Qobject->select($query5);
                               $count5 = $Qobject->table_row_count($query5);

                               if($count5 > 0){

                                    foreach($result5 as $row){
                                        $prodQty = $row['hamper_item_quanity'];
                                        $prodId = $row['hamper_item_product_id'];

                                        $query6 = "SELECT product_id,recorded_level FROM products WHERE product_id = $prodId";

                                        $result6 = $Qobject->select($query6);
                                        $count6 = $Qobject->table_row_count($query6);

                                        if($count6 > 0){
                                            foreach($result6 as $row){
                                                $updatedQty = $row['recorded_level'] - $prodQty;

                                                //update quantity 
                                                $table4 = 'products';
                                                $data =  array('recorded_level' => $updatedQty);
                                                $cond = "product_id =$prodId";
                                                $result7 = $Qobject->update($table4, $data, $cond);

                                                if($result7){
                                                    $table5 = 'product_history';
                                                    $prod_hist_prod_id 	=  $prodId;
                                                    $hist_action = 'Sold in '. $hamper_sales_name  ; 
                                                    $former_level = $row['recorded_level'];
                                                    $current_level = $updatedQty;
                                                    $action_by_id = $cashier_id;
                                                    $prod_hist_date  =   date('Y-m-d H:i:s');	

                                                    $data =  array(
                                                        'prod_hist_prod_id' => $prod_hist_prod_id,
                                                        'hist_action' => $hist_action,
                                                        'action_by_id' => $action_by_id,
                                                        'former_level' => $former_level,
                                                        'current_level' => $current_level,
                                                        'prod_hist_date' => $prod_hist_date           
                                                    );
                                                    $Qobject->insert($table5, $data);
                                                }
                                            }
                                        }
                                    }
                               }

                           }
                        }

                    } 
                }
            }

            $message['success'] = 'Congrats! New Hamper Order Created';
            $message['value'] =  $last_id;
        }  
        else{
            $query = "DELETE  FROM hamper_sales_overview WHERE hamper_sales_overview_id =  $last_id";
            $result = $Qobject->delete($query);
            if($result){

                $message['error'] = 'Hamper Order could not be created, an error occured';

            }
        }
        echo json_encode($message);
    }
        
  //View invoice Details in Modal
  if($_POST['btn_action'] == 'invoice_details')
  {
    $sqlBind = $_POST['product_id'];
    $query = "
     SELECT * FROM hamper_sales_overview INNER JOIN users on users.user_id = hamper_sales_overview.cashier_id  WHERE hamper_sales_overview_id = $sqlBind 
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
  
    if($count > 0)
    {
      	 	 	 	 	 	 		 	 
      foreach ($result as $row => $invoice) {
        $output .='<div class="col-12 title"> <h2>MUTTYFEM SUPERMARKET </h2> </div>';
        $output .= '<div class="col-6">
            DATE: <b>'.$Qobject->date_string($invoice["hamper_sales_creation_date"]).'</b>
        </div>';  
        $output .= '<div class="col-6">
            INVOICE NO:  <b>'.$invoice["hamper_sales_number"].'</b>
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
        SELECT * FROM hamper_sales WHERE hamper_sales_overview_key = $sqlBind
        ";

        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $id = 1;
        foreach ($result2 as $row => $item) {
          $output.='<tr>
            <td>'.$id++.'</td>
            <td>'.$item["hamper_sales_name"].'</td>
            <td>'.$item["hamper_sales_qty"].'</td>
            <td>'.number_format($item["hamper_sales_unit_price"],2).'</td> 
            <td>'.number_format($item["hamper_sales_total"],2).'</td>
          </tr>';
        }
        
        $output .= ' </tbody>
          </table>
        </div>';
        $output.='<div class="col-12 right-align">
                TOTAL: <b>'.number_format($invoice["hamper_sales_total"],2).'</b>
            </div>';
        $output.='<div class="col-12 right-align">
               PAYMENT TYPE: <b>'.$invoice["hamper_payment_type"].'</b>
            </div>';
        $output.='<div class="col-12 right-align">
            CASHIER: <b>'.$invoice["user_name"].'</b>
        </div>';

      }
    }
    
    echo $output;
  }
  
  //Fetch Cashiers Hampers History
  if($_POST['btn_action'] == 'history')
  {
    $query = '';
    $sqlBind = $_POST['user_id'];
    $output = array();
   
    $query .= "
    SELECT * FROM hamper_sales_overview INNER JOIN users on users.user_id = hamper_sales_overview.cashier_id  WHERE cashier_id = $sqlBind 
    ";
   
	
    if(isset($_POST["search"]["value"]))
    { 
        $query .= 'AND(';
        $query .= 'user_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR hamper_sales_number LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR hamper_payment_type LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR hamper_sales_creation_date LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= ')';

    }
   
    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY hamper_sales_overview_id DESC ';
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
      if($row['hamper_sales_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Paid</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Unpaid</span>';
      }

                               
        $sub_array = array();  
        $sub_array[] = $row['hamper_sales_number'];
        $sub_array[] = $row['user_name'];
        $sub_array[] = number_format($row['hamper_sales_total'],2);
        $sub_array[] = $row['hamper_payment_type'];
        $sub_array[] = $Qobject->date_string($row['hamper_sales_creation_date']);
        $sub_array[] = $status;

      
        $sub_array[] = '<a href="printHamperReceipt.php?invoiceNUmber='.$row["hamper_sales_overview_id"].'" target="_blank" class="btn btn-success">PDF <i class="fas fa-file-pdf"></a>';
        $sub_array[] = '<button name="view" id="'.$row["hamper_sales_overview_id"].'" class="btn btn-xsx btn-warning view" >view <i class="fas fa-eye"></i></button>';
       
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM hamper_sales_overview WHERE cashier_id  = $sqlBind";
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
