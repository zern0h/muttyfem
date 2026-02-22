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
        $output .='<div class="col-12 title"> <h2>MUTTYFEM </h2> </div>';
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
        SELECT * FROM inventory_order_product INNER JOIN products ON inventory_order_product.inventory_product_id = products.product_id WHERE inventory_overview_id =$sqlBind
        ";

        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $id = 1;
        foreach ($result2 as $row => $item) {
          $output.='<tr>
            <td>'.$id++.'</td>
            <td>'.$item["product_name"].'</td>
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
   
  //Loading Invoice Datatable
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    
    $query .= "
        SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id
    ";
   
    if(isset($_POST["search"]["value"]))
    {
        $query .= 'WHERE user_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR inventory_number LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR payment_type LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR inventory_order_created_date LIKE "%'.$_POST["search"]["value"].'%" ';

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

        $sub_array[] = '<a href="printOrderPDF.php?invoiceNum='.$row["inventory_overview_id"].'" target="_blank" class="btn btn-info ">PDF <i class="fas fa-file-pdf"></a>';
        $sub_array[] = '<button name="view" id="'.$row["inventory_overview_id"].'" class="btn btn-xsx btn-warning view" >view <i class="fas fa-eye"></i></button>';
    
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM inventory_overview";
    $total_row = $Qobject->table_row_count($sql);

    $output = array(
    	"draw"    			=> 	intval($_POST["draw"]),
    	"recordsTotal"  	=>  $filtered_rows,
    	"recordsFiltered" 	=> $total_row,
    	"data"    			=> 	$data
    );

    echo json_encode($output);

  }

  

  //Generate POS Receipt
  if($_POST['btn_action'] == 'Fetch Print')
  {
  
     // instantiate and use the dompdf class
     $dompdf = new Dompdf();
      
     // (Optional) Setup the paper size and orientation
     //$dompdf->setOptions('dpi', 72);
     $dompdf->setPaper(array(0,0,204,500));
     $sqlBind = $_POST['print_code'];
     $query = "
     SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE inventory_number = $sqlBind
     ";
 
     $result = $Qobject->select($query);
     $count = $Qobject->table_row_count($query);
 
     if($count > 0)
     {
                                         
         foreach ($result as $row => $invoice) {
             $html = '<html>
                 <head>
                     <style>
                         * {
                             font-size: 12px;
                             font-family: Times New Roman;
                         }
                         
                         td,
                         th,
                         tr,
                         table {
                             border-top: 1px solid black;
                             border-collapse: collapse;
                         }
                         
                         td.description,
                         th.description {
                             width: 75px;
                             max-width: 75px;
                         }
                         
                         td.id,
                         th.id {
                             width: 15px;
                             max-width: 15px;
                             word-break: break-all;
                         }
 
                         td.quantity,
                         th.quantity {
                             width: 25px;
                             max-width: 25px;
                             word-break: break-all;
                         }
 
                         td.price,
                         th.price {
                             width: 40px;
                             max-width: 40px;
                             word-break: break-all;
                         }
                         
                         .centered {
                             text-align: center;
                             align-content: center;
                         }
                         
                         .ticket {
                             width: 155px;
                             max-width: 155px;
                         }
                         
                         img {
                             max-width: inherit;
                             width: inherit;
                         }
                         
                     </style>
                 </head>
                 <body>
                     <div class="ticket">
                        <h1 class="centered">CLOVER CUTIES</h1>
                         <p class="centered"> 
                             <br>Invoice NO: '.$invoice["inventory_number"].'
                             <br>[Date]: '.$Qobject->date_string($invoice["inventory_order_created_date"]).' 
                             </p>
                         <table>
                             <thead>
                                 <tr>
                                     <th class="id">#</th>
                                     <th class="description">Item</th>
                                     <th class="quantity">Q.</th>
                                     <th class="price">Amount</th>
                                 </tr>
                             </thead>
                             <tbody>';
                             
                             $query2 = "
                             SELECT * FROM inventory_order_product INNER JOIN products ON inventory_order_product.inventory_product_id = products.product_id WHERE inventory_overview_id =$sqlBind
                             ";
 
                             $result2 = $Qobject->select($query2);
                             $count2 = $Qobject->table_row_count($query2);
                             $id = 1;
                             foreach ($result2 as $row => $item) {
                                 $html .= '<tr>
                                     <td class="id">'.$id++.'</td>
                                     <td class="description">'. $item["product_name"].'</td>
                                     <td class="quantity">'. $item["inventory_quantity"].'</td>
                                     <td class="price">'.number_format($item["inventory_total_price"],2).'</td>
                                 </tr>
                                 ';
                             }
                             $html.='<tr>
                                     <td class="id"></td>
                                     <td colspan="2"><b>TOTAL</b></td>
                                     <td class="price"><b>'.number_format($invoice["inventory_order_total"],2).'</b></td>
                                 </tr>
                             </tbody>
                         </table>
                         <p class="centered">[Payment Type] '.$invoice["payment_type"].'</p>
                         <p class="centered">[Cashier\'s Name] '.$invoice["user_name"].'</p>                        
                         <p class="centered">[Address]
                             <br>No.37 Oyo Road, Opposite Providence Court, Sango Mokola Road, Ibadan, Oyo State.
                             <br>[IG] @clovercuties
                             <br>+2348027111853
                         </p>
                         <p class="centered">[Invoice generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')).'</p>                        
                     </div>
                 </body>
             </html>
                 ';
 
             $dompdf->loadHtml($html);
             // Render the HTML as PDF
             $dompdf->render();
 
             // Output the generated PDF to Browser
             $dompdf->stream('invoice'.$invoice["inventory_number"].'thermal');
         }
     }
  }
}



?>
