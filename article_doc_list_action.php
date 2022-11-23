<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{


  if($_POST['btn_action'] == 'fetch_product')
  {
    $sqlBind = $_POST['product_number'];
    $query = "
    SELECT * FROM `product_history` INNER JOIN products on products.product_id = product_history.prod_hist_prod_id INNER JOIN users on users.user_id = product_history.action_by_id WHERE prod_hist_prod_id = '$sqlBind' 
    ";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    $output = '';
    if($count > 0)
    {
	
        foreach($result as $row => $transaction)
        {
            $output .= '<tr>
                <td>'.$transaction["product_name"].'</td>
                <td>'.$transaction["hist_action"].'</td>
                <td>'.$transaction["former_level"].'</td>
                <td>'.$transaction["current_level"].'</td>
                <td>'.$transaction["user_name"].'</td>
                <td>'.$Qobject->date_string($transaction['prod_hist_date']).'</td>
            </tr>';


        }
    }else{
      $output .= '<tr>
          <td>Null</td>
          <td>Null</td>
          <td>Null</td>
          <td>Null</td>
          <td>Null</td>
          <td>Null</td>
      </tr>';
    }
    echo $output;
  }

 
  
  

}

?>
