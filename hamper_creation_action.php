<?php
include 'includes/DB.php';
include 'includes/Query.php';


$Qobject = new Query;


if(isset($_POST['btn_action']))
{

    //loading product by dropdown
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

    //loading product by barcode
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

    if($_POST['btn_action'] == 'fetch_for_print')
    {
        $sqlBind = $_POST['product_number'];
        $query = "SELECT * FROM hamper_overview WHERE hamper_overview_id = $sqlBind";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);

        if($count > 0){
            foreach( $result as $row => $product){
                $output['hamper_overview_id'] = $product['hamper_overview_id'];
                $output['hamper_code'] = $product['hamper_code'];
                $output['hamper_name'] = $product['hamper_name'];
                $output['hamper_total_cost'] = $product['hamper_total_cost'];
            
            }
        }
        echo json_encode($output);
        
    }


    if($_POST['btn_action'] == 'Add')
    {
    
        $get_item = 'hamper_code';
        $table = 'hamper_overview';
        $cond = 'hamper_overview_id';
        $limit = 1;
       
        $hamper_name = $_POST['hamper_name'];
        $hamper_code = $Qobject->hamperNum($get_item,$table, $cond,$limit);;    
        $hamper_total_cost = $_POST['hamper_price']; 
        $hamper_estimated_total_cost = $_POST['total_hamper_cost']; 
        $hamper_quantity = $_POST['hamper_qty'];
        $hamper_status = 1; 
        $hamper_created_by = $_POST['user_id']; 
        $hamper_created_at = date('Y-m-d H:i:s');
        $message[] = '';

        $data = array(
            'hamper_name' => $hamper_name,
            'hamper_code' => $hamper_code,
            'hamper_total_cost' => $hamper_total_cost, 	
            'hamper_estimated_total_cost' => $hamper_estimated_total_cost, 	
            'hamper_quantity'  => $hamper_quantity,	
            'hamper_status' => $hamper_status,	
            'hamper_created_by' => $hamper_created_by,
            'hamper_created_at' => $hamper_created_at
        );

        $result = $Qobject->insert($table, $data);
        $last_id = $Qobject->DBconnect->lastInsertId();
        
        if($result)
        {
            for($count = 0; $count<count($_POST["product_id"]); $count++){
                              
                $innerTable = 'hamper_items';
                    
                $hamper_overview_key = $last_id;
                $hamper_item_product_id = $_POST['product_id'][$count];
                $hamper_item_cost = $_POST['pricePerUnit'][$count];
                $hamper_item_quanity  = $_POST['quantity'][$count];
                $hamper_item_total_price  = $_POST['itemTotalPrice'][$count];
                $hamper_item_date =  date('Y-m-d H:i:s');
                
                $data = array(
                'hamper_overview_key' => $hamper_overview_key,
                'hamper_item_product_id' => $hamper_item_product_id, 	
                'hamper_item_cost' => $hamper_item_cost, 	
                'hamper_item_quanity'  => $hamper_item_quanity,
                'hamper_item_total_price' => $hamper_item_total_price,	
                'hamper_item_date' => $hamper_item_date
                );

                $innerresult = $Qobject->insert($innerTable, $data);

                if($innerresult)
                {
                    //Selecting quantity by ID
                    $inner_sub_query = "SELECT recorded_level FROM products WHERE product_id = $hamper_item_product_id ";
        
                    $inner_sub_result = $Qobject->select($inner_sub_query);
                    $inner_sub_count = $Qobject->table_row_count($inner_sub_query);

                    if($inner_sub_count)
                    {
                        foreach ($inner_sub_result as $row => $product ){
                            $newQuantity  =  $product['recorded_level'] ; 

                            //insert into history table so that we can generate article report
                            $innerTable4 = 'product_history';
                            $prod_hist_prod_id 	=  $hamper_item_product_id;
                            $hist_action = $hamper_item_quanity *  $hamper_quantity.' Added To '.$hamper_name.' Not Sold Yet' ; 
                            $former_level = $newQuantity;
                            $current_level = $newQuantity;
                            $action_by_id = $hamper_created_by;
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

            }

            $message['success'] = 'Congrats! Hamper Created';

        }else{
            $query = "DELETE  FROM hamper_overview WHERE hamper_overview_id =  $hamper_overview_key";
            $result = $Qobject->delete($query);
            if($result){

                $message['error'] = 'Hamper could not be created, an error occured';

            }
        }

    echo json_encode($message);
  
  }

  //View invoice Details in Modal
  if($_POST['btn_action'] == 'invoice_details')
  {
    $sqlBind = $_POST['hamper_id'];
    $query = "
    SELECT * FROM hamper_overview INNER JOIN users ON users.user_id = hamper_overview.hamper_created_by WHERE hamper_overview_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    {
      	 	 		 	 	 		 	 
        foreach ($result as $row => $invoice) {
      
            $output .='<div class="col-12 title"> <h3>MUTTYFEM SUPERMARKET </h3> </div>';
            $output .='<div class="col-12 title"> <h5>'.$invoice["hamper_name"].'</h5> </div>';
            $output .= '<div class="col-6">
                DATE: <b>'.$Qobject->date_string($invoice["hamper_created_at"]).'</b>
            </div>';  
            $output .= '<div class="col-6">
                HAMPER NO:  <b>'.$invoice["hamper_code"].'</b>
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
                SELECT * FROM hamper_items INNER JOIN products on products.product_id = hamper_items.hamper_item_product_id WHERE hamper_overview_key = $sqlBind
            ";

            $result2 = $Qobject->select($query2);
            $count2 = $Qobject->table_row_count($query2);
            $id = 1;


            foreach ($result2 as $row => $item) {
                $output.='<tr>
                    <td>'.$id++.'</td>
                    <td>'.$item["product_name"].'</td>
                    <td>'.$item["hamper_item_quanity"].'</td>
                    <td>'.number_format($item["hamper_item_cost"],2).'</td> 
                    <td>'.number_format($item["hamper_item_total_price"],2).'</td>
                </tr>';
            }

            $output .= ' </tbody>
            </table>
            </div>';

            $status;
            if($invoice["hamper_status"] == 1)
            {
                $status = '<b class="text text-success">Active</b>';
            }else{
                $status = '<b class="text text-danger">Inactive</b>';
            }
            $output.='<div class="col-12 right-align">
                    HAMPER PRICE: <b>'.number_format($invoice["hamper_total_cost"],2).'</b>
                </div>';
            $output.='<div class="col-12 right-align">
                HAMPER QUANTITY: <b>'.$invoice["hamper_quantity"].'</b>
                </div>';
            $output.='<div class="col-12 right-align">
                STATUS: <b>'.$status.'</b>
                </div>';
            $output.='<div class="col-12 right-align">
                HAMPER CREATED BY: <b>'.$invoice["user_name"].'</b>
            </div>';

        }
    }

    echo $output;
  }
  
  //Fetch Cashiers POS History
  if($_POST['btn_action'] == 'history')
  {
    $query = '';
    $output = array();
   
    $query .= "
        SELECT * FROM hamper_overview INNER JOIN users ON users.user_id = hamper_overview.hamper_created_by WHERE hamper_status = 1
    ";
   
    if(isset($_POST["search"]["value"]))
    { 
        $query .= 'AND(';
        $query .= 'hamper_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR hamper_code LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR hamper_created_at LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= ')';

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY hamper_overview_id DESC ';
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
      if($row['hamper_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Active</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
      }

                                   
        $sub_array = array();  
        $sub_array[] = $row['hamper_code'];
        $sub_array[] = $row['hamper_name'];
        $sub_array[] = $row['hamper_quantity'];
        
        $sub_array[] = number_format($row['hamper_total_cost'],2);
        $sub_array[] = $row['user_name'];
        $sub_array[] = $Qobject->date_string($row['hamper_created_at']);
        
       
        $sub_array[] = $status;

      
        $sub_array[] = '<button name="view" id="'.$row["hamper_overview_id"].'" class="btn btn-xsx btn-warning view" >view <i class="fas fa-eye"></i></button>';
        $sub_array[] = '<button name="barcode" id="'.$row["hamper_overview_id"].'" class="btn btn-xsx btn-warning barcode" >Barcode <i class="fas fa-barcode"></i></button>';
       
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM hamper_overview WHERE hamper_status  = 1";
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
