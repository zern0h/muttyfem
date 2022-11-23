<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;

if(isset($_POST['btn_action']))
{

    

    if($_POST['btn_action'] == 'check suppliers')
    {
     
        $query = "SELECT count(*) as total from suppliers where supplier_status =  1";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo $total;
        }else{
            echo '0';
        }
    } 

    if($_POST['btn_action'] == 'check products')
    {
     
        $query = "SELECT count(*) as total from products WHERE product_status = 1";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo $total;
        }else{
            echo '0';
        }
    }
    if($_POST['btn_action'] == 'check categories')
    {
     
        $query = "SELECT count(*) as total from categories WHERE cat_status = 1";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo $total;
        }else{
            echo '0';
        }
    }

    if($_POST['btn_action'] == 'check sub_categories')
    {
     
        $query = "SELECT count(*) as total from sub_categories WHERE sub_category_status = 1";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo $total;
        }else{
            echo '0';
        }
    }
 
    if($_POST['btn_action'] == 'check total transaction')
    {
     
        $query = "SELECT SUM(inventory_order_total) as total FROM inventory_overview ";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo '<span>&#8358</span>'.number_format($total,2).'';
        }else{
            echo '0';
        }
    }

    if($_POST['btn_action'] == 'check daily sales')
    {
     
        $query = "SELECT COUNT(*) AS total FROM `inventory_overview` WHERE DATE(inventory_order_created_date) = CURDATE() AND inventory_order_status = 1";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo $total;
        }else{
            echo '0';
        }
    }

    if($_POST['btn_action'] == 'check monthly sales')
    {
     
        $query = "SELECT COUNT(*) AS total FROM `inventory_overview` WHERE MONTH(inventory_order_created_date) = MONTH(now()) AND YEAR(inventory_order_created_date) = YEAR(now()) AND inventory_order_status = 1 ";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo $total;
        }else{
            echo '0';
        }
    }

    if($_POST['btn_action'] == 'check total pod')
    {
     
        $query = "SELECT SUM(pod_overview_total) as total FROM proof_of_delivery";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo '<span>&#8358</span>'.number_format($total,2).'';
        }else{
            echo '0';
        }
    } 

    if($_POST['btn_action'] == 'check total paid delivery')
    {
     
        $query = "SELECT SUM(payment_made) as total FROM proof_of_delivery";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total += $row['total'];
             
          }
         
         echo '<span>&#8358</span>'.number_format($total,2).'';
        }else{
            echo '0';
        }
    } 
    
    if($_POST['btn_action'] == 'check outstanding')
    {
     
        $query = " SELECT SUM(pod_overview_total) AS overview_total, SUM(payment_made) as payment_total FROM proof_of_delivery";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
   
        if ($count > 0)
        {
          	 $total = 0;	
          foreach( $result as $row){
                $total = $row['overview_total'] - $row['payment_total'];
             
          }
         
         echo '<span>&#8358</span>'.number_format($total,2).'';
        }else{
            echo '0';
        }
    }

  
    if($_POST['btn_action'] == 'get sales'){
        $query = "SELECT inventory_number, inventory_order_total, payment_type, inventory_order_status,inventory_order_created_date FROM `inventory_overview` ORDER BY inventory_overview_id DESC LIMIT 10 ";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);

        $output = '';
        $id = 1;
        $output .='<table id="unpaidTable"> 	 	 	
        <thead>
            <tr>
                <th>#</th>
                <th>Inv NO:</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';
        
        
        if($count > 0){
            foreach( $result as $row){
               		
                $status = '';

                if($row['inventory_order_status'] == 1)
                {
                    $status = '<span class="badge bg-xs bg-success">Paid</span>';
                }else{
                    $status = '<span class="badge bg-xs bg-danger">Unpaid</span>';
                }
               
                $output .= '<tr>
                    <td>'.$id++.'</td>
                    <td>'.$row['inventory_number'].'</td>
                    <td>'.number_format($row['inventory_order_total'],2).'</td>
                    <td>'.$row['payment_type'].'</td>
                    <td>'.$status.'</td>
                    <td>'.$Qobject->date_string($row['inventory_order_created_date']).'</td>
                </tr>';
            }

          
        }else{
            $output .= '<tr>
                <td>'.$id++.'</td>
                <td>-</td>
                <td>'.number_format(0,2).'</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>';
        }
        $output.= '</tbody>
        </table>';
        echo $output;
    }

    if($_POST['btn_action'] == 'get pod'){
        $query = "SELECT pod_number, pod_overview_total, payment_made, pod_overview_status,pod_creation_time FROM `proof_of_delivery` ORDER BY pod_overview_id DESC LIMIT 10 ";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);

        $output = '';
        $id = 1;
        $output .='<table id="unpaidTable"> 	 	 	
        <thead>
            <tr>
                <th>#</th>
                <th>P.Order NO:</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';
        
        if($count > 0){
            foreach( $result as $row){
               		
                $status = '';

                if($row['pod_overview_status'] == 1)
                {
                    $status = '<span class="badge bg-xs bg-success">Paid</span>';
                }else{
                    $status = '<span class="badge bg-xs bg-danger">Unpaid</span>';
                }
               
                $output .= '<tr>
                    <td>'.$id++.'</td>
                    <td>'.$row['pod_number'].'</td>
                    <td>'.number_format($row['pod_overview_total'],2).'</td>
                    <td>'.number_format($row['payment_made'],2).'</td>
                    <td>'.$status.'</td>
                    <td>'.$Qobject->date_string($row['pod_creation_time']).'</td>
                </tr>';
            }

          
        }else{
            $output .= '<tr>
                <td>'.$id++.'</td>
                <td>-</td>
                <td>'.number_format(0,2).'</td>
                <td>'.number_format(0,2).'</td>
                <td>-</td>
                <td>-</td>
            </tr>';
        }
        $output.= '</tbody>
        </table>';
        echo $output;
    }
}

?>

