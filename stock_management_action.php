<?php
include 'includes/DB.php';
include 'includes/Query.php';
require_once '../dompdf/autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

  //loading product
  if($_POST['btn_action'] == 'fetch_product')
  {
      $sqlBind = $_POST['product_number'];
      $query = "SELECT * FROM stock_products WHERE stock_product_id = $sqlBind";
      $result = $Qobject->select($query);
      $count = $Qobject->table_row_count($query);

      if($count > 0){
          foreach( $result as $row => $stock_product){
              
              $output['stock_product_name'] = $stock_product['stock_product_name'];
              $output['stock_product_unit'] = $stock_product['stock_product_unit'];
              $output['stock_product_quantity'] = $stock_product['stock_product_quantity'];
          }
      }
      echo json_encode($output);
      
  }

  if($_POST['btn_action'] == 'Add')
  {
    $table = 'shelf_stock_items';
    $message[] = '';
    for ($count = 0; $count < count($_POST['product_id']); $count++ )
    {
                
        $shelf_stock_item_product_name = $_POST["product_name"][$count];
        $shelf_stock_item_product_id = $_POST["product_id"][$count];
        $shelf_stock_item_qty = $_POST["quantity"][$count];
        $shelf_stock_item_unit = $_POST["unit"][$count];
        $overAllQuantity = $_POST["overallQuantity"][$count] - $_POST["quantity"][$count];
        $shelf_stock_item_date = date('Y-m-d H:i:s');;
        $shelf_stock_item_created_by = $_POST["user_id"];
        $newStockQuantity = $shelf_stock_item_qty - $overAllQuantity;

        $data = array(
        'shelf_stock_item_product_name' => $shelf_stock_item_product_name,
        'shelf_stock_item_product_id' => $shelf_stock_item_product_id, 	
        'shelf_stock_item_qty' => $shelf_stock_item_qty, 	
        'shelf_stock_item_unit'  => $shelf_stock_item_unit,	
        'shelf_stock_item_date' => $shelf_stock_item_date,	
        'shelf_stock_item_created_by' => $shelf_stock_item_created_by
        );
    
        $result = $Qobject->insert($table, $data);

        if($result){
            //updating quantity
            $table2 = 'stock_products';
            $data2 =  array('stock_product_quantity' => $overAllQuantity);
            $cond = "stock_product_id = $shelf_stock_item_product_id";
            $result2 = $Qobject->update($table2, $data2, $cond);

            if($result2){
              $query = "SELECT recorded_level FROM products WHERE stock_product_number = $shelf_stock_item_product_id";
              $result3 = $Qobject->select($query);
              $count3 = $Qobject->table_row_count($query);

              if($count3 > 0){
                $newRecLevel = 0;
                foreach ($result3 as $row => $product){
                  $newRecLevel =  $product['recorded_level'] + $shelf_stock_item_qty;
                }
                $table3 = 'products';
                $data3 =  array('recorded_level' => $newRecLevel);
                $cond3 = "stock_product_number = $shelf_stock_item_product_id";
                $result3 = $Qobject->update($table3, $data3, $cond3);
              }

            }
           

        }
    }
    $message['success'] = 'Stock product successfully removed from store and can be added to the shelf';

    echo json_encode($message);
  
  }

  //View invoice Details in Modal
  if($_POST['btn_action'] == 'invoice_details')
  {
    $sqlBind = $_POST['product_id'];
    $query = "
    SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE inventory_number = $sqlBind
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
  
  //Fetch Stock removed from store
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';
    
    $output = array();
   
    $query .= "
    SELECT * FROM shelf_stock_items INNER JOIN users ON shelf_stock_items.shelf_stock_item_created_by = users.user_id  
    ";
   
	
    if(isset($_POST["search"]["value"]))
    { 
        $query .= 'WhERE shelf_stock_item_product_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR shelf_stock_item_date LIKE "%'.$_POST["search"]["value"].'%" ';
        
    }
   
    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY shelf_stock_item_id DESC ';
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
        $sub_array[] = $row['shelf_stock_item_product_name'];
        $sub_array[] = $row['user_name'];
        $sub_array[] = $row['shelf_stock_item_qty'];
        $sub_array[] = $row['shelf_stock_item_unit'];
        $sub_array[] = $Qobject->date_string($row['shelf_stock_item_date']);
       
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM shelf_stock_items";
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
