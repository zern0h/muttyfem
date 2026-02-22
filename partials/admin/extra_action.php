<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;

if(isset($_POST['btn_action']))
{

    if($_POST['btn_action'] == 'load_product')
    {
       $sqlBind = $_POST['product_number'];
       $query = "SELECT product_id, product_name, recorded_level FROM products WHERE product_barcode = '$sqlBind'";
       $result = $Qobject->select($query);
       $count = $Qobject->table_row_count($query);

        if($count > 0){
           foreach($result as $row => $product)
           {
               $output['product_id'] = $product['product_id'];
               $output['product_name'] = $product['product_name']; 
               $output['recorded_level'] = $product['recorded_level'];    
            }
        }

        echo json_encode($output);
    } 

    if($_POST['btn_action'] == 'reduce')
    {
        
        $table = 'update_quantity';
        
        $updated_product_id = $_POST['product_id'];
        $current_qty = $_POST['current_qty'];
        $updated_qty = $_POST['qty'];
        $update_action = $_POST['btn_action'];
        $qty_updated_by = $_POST['user_id'];
        $update_qty_creation_date = date('Y-m-d H:i:s');
        
        $message[] = '';
    	
        $data = array(
            'updated_product_id' => $updated_product_id,
            'current_qty' => $current_qty, 	
            'updated_qty' => $updated_qty, 	
            'update_action'  => $update_action,	
            'qty_updated_by' => $qty_updated_by,
            'update_qty_creation_date' => $update_qty_creation_date	
        );
        $result = $Qobject->insert($table, $data);
        
        if($result)
        {
            $newQty = $current_qty - $updated_qty;
             //updating quantity 
             $table = 'products';
             $data =  array(
               'recorded_level' => $newQty        
             );
             $cond = "product_id =$updated_product_id";
             $result = $Qobject->update($table, $data, $cond);

             $message['success'] = "Product successfully reduced by $updated_qty ";
        }else{
            $message["error"] = "Product could not be reduced";
        }
        echo json_encode($message);
    }

    if($_POST['btn_action'] == 'increase')
    {
        $table = 'update_quantity';
        
        $updated_product_id = $_POST['product_id'];
        $current_qty = $_POST['current_qty'];
        $updated_qty = $_POST['qty'];
        $update_action = $_POST['btn_action'];
        $qty_updated_by = $_POST['user_id'];
        $update_qty_creation_date = date('Y-m-d H:i:s');
        
        $message[] = '';
       	
        $data = array(
            'updated_product_id' => $updated_product_id,
            'current_qty' => $current_qty, 	
            'updated_qty' => $updated_qty, 	
            'update_action'  => $update_action,	
            'qty_updated_by' => $qty_updated_by,
            'update_qty_creation_date' => $update_qty_creation_date	
        );
        $result = $Qobject->insert($table, $data);
        
        if($result)
        {
            $newQty = $current_qty + $updated_qty;
             //updating quantity 
             $table = 'products';
             $data =  array(
               'recorded_level' => $newQty        
             );
             $cond = "product_id =$updated_product_id";
             $result = $Qobject->update($table, $data, $cond);

             $message['success'] = "Product successfully increased by $updated_qty";
        }else{
            $message["error"] = "Product could not be increased";
        }
        echo json_encode($message);
    }

    if($_POST['btn_action'] == 'load_table')
    {
        $query = '';

        $output = array();
         
        $query .= "
        SELECT * FROM update_quantity  INNER JOIN products on products.product_id = update_quantity.updated_product_id INNER JOIN users on users.user_id = update_quantity.qty_updated_by
        ";
       
        if(isset($_POST["search"]["value"]))
        {
            $query .= 'WHERE product_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $query .= 'OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $query .= 'OR update_action LIKE "%'.$_POST["search"]["value"].'%" ';
        }
    
        if(isset($_POST['order']))
        {
            $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY update_qty_id DESC ';
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
            $sub_array[] = $row['current_qty'];
            $sub_array[] = $row['updated_qty'];
            $sub_array[] = $row['update_action'];
            $sub_array[] = $row['user_name'];
            $sub_array[] = $Qobject->date_string($row['update_qty_creation_date']);
     
            $data[] = $sub_array;
        }
    
        $sql = "SELECT * FROM update_quantity";
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