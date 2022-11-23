<?php
    include 'includes/DB.php';
    include 'includes/Query.php';
    
    $Qobject = new Query;
    
    include 'includes/barcode128.php';
    if($_POST['btn_action'] == 'Fetch Print')
    {
      
        $sqlBind = $_POST['print_code'];
        $query = "
        SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE inventory_number = '$sqlBind'
        ";

        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $output = '';
        if($count > 0)
        {
                                            
        foreach ($result as $row => $invoice) {
            $output .='<div class="col-12 title"> <h2>>Clover Cuties </h2>  </div>';
            $output .= '<div class="col-6">
                Date <b>'.$invoice["inventory_order_created_date"].'</b>
            </div>';  
            $output .= '<div class="col-6">
                Invoice No:  <b>'.$invoice["inventory_number"].'</b>
            </div>'; 

            $output .='<div class="col-12">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <td>NO</td>
                        <td>Item</td>
                        <td>QTY</td>
                        <td>Price</td>
                        <td>Total</td>
                    </tr>
                </thead>
                <tbody>';
            
            $query2 = "
            SELECT * FROM inventory_order_product INNER JOIN products ON inventory_order_product.inventory_product_id = products.product_id WHERE inventory_overview_id ='$sqlBind'
            ";

            $result2 = $Qobject->select($query2);
            $count2 = $Qobject->table_row_count($query2);
            $id = 1;
            foreach ($result2 as $row => $item) {
            $output.='<tr>
                <td>'.$id++.'</td>
                <td>'.$item["product_name"].'</td>
                <td>'.$item["inventory_quantity"].'</td>
                <td>'.$item["inventory_price"].'</td>
                <td>'.$item["inventory_total_price"].'</td>
            </tr>';
            }

            $output .= ' </tbody>
            </table>
            </div>';
            $output.='<div class="col-12 right-align">
                    Total: <b>'.$invoice["inventory_order_total"].'</b>
                </div>';
            $output.='<div class="col-12 right-align">
                    Payment Type: <b>'.$invoice["payment_type"].'</b>
                </div>';
            $output.='<div class="col-12 right-align">
                Cashier: <b>'.$invoice["user_name"].'</b>
            </div>';
            $output.='<div class="col-12 ">
                '.bar128(stripcslashes($invoice["inventory_number"])).'
            </div>';
        }
        }

        echo $output;
    }


?>