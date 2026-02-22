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
    //View invoice Details in Modal
    if($_POST['btn_action'] == 'invoice_details')
    {
        $sqlBind = $_POST['product_id'];
        $query = "
        SELECT * FROM hamper_sales_overview INNER JOIN users on users.user_id = hamper_sales_overview.cashier_id 
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
  
   
  //Loading Invoice Datatable
   if($_POST['btn_action'] == 'load_table')
    {
        $query = '';
        $output = array();
    
        $query .= "
        SELECT * FROM hamper_sales_overview INNER JOIN users on users.user_id = hamper_sales_overview.cashier_id  
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

        $sql = "SELECT * FROM hamper_sales_overview";
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
