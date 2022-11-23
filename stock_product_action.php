<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

 

  //loading brands
  if($_POST['btn_action'] == 'load_sub_cat')
  {
    $cat_id =  $_POST['cat_id'];
    echo $Qobject->loadBrand($cat_id);
  }

  //Adding products to the database
  if($_POST['btn_action'] == 'Add')
  {
    $get_item = 'stock_product_code';
    $table = 'stock_products';
    $cond = 'stock_product_id';
    $limit = 1;

    $query = "SELECT * FROM $table";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
     	 	 	 	 	 	 	 	 	 	 	 	 	 	
    $stock_product_name = $_POST['product_name'];  
    $stock_product_code = $Qobject->stockProductNumber($get_item,$table, $cond,$limit);    
    $stock_product_category_id = $_POST['category_id'];   
    $stock_product_sub_cat_id = $_POST['sub_cat_id'];       
    $stock_prodoct_brand_id = $_POST['brand_id'];  
    $stock_product_unit = $_POST['product_unit'];       
    $stock_product_created_by = $_POST['product_entered_by'];      
    $stock_product_created_at = date('Y-m-d H:i:s');
    $message[] = '';

    $data = array(
      'stock_product_name' => $stock_product_name,
      'stock_product_category_id' => $stock_product_category_id, 	
      'stock_product_sub_cat_id' => $stock_product_sub_cat_id,
      'stock_prodoct_brand_id' => $stock_prodoct_brand_id, 	
      'stock_product_unit'  => $stock_product_unit,	
      'stock_product_code' => $stock_product_code,	
      'stock_product_created_by' => $stock_product_created_by,
      'stock_product_created_at' => $stock_product_created_at
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      $message['success'] = 'Congrats! New Stock Product Added';
      
    }
    else {
        $message['error'] = 'Stock Product could not be added, an error occured';
        
    }

    echo json_encode($message);
  }

  //fetchomg product details to be viewed
  if($_POST['btn_action'] == 'product_details')
  {
    $sqlBind = $_POST['stock_product_id'];
    $query = "
    SELECT * FROM stock_products INNER JOIN categories ON categories.cat_id = stock_products.stock_product_category_id INNER JOIN sub_categories on sub_categories.sub_category_id = stock_products.stock_product_sub_cat_id INNER JOIN brands on brands.brand_id = stock_products.stock_prodoct_brand_id INNER JOIN users on users.user_id = stock_products.stock_product_created_by WHERE stock_product_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    {
      	 	 	 	 	 	 		 	 
      foreach ($result as $row => $product) {
        $status = '';
        if($product['stock_product_status'] == 1)
        {
          $status = '<span class="badge bg-xs bg-success">Active</span>';
        }
        else {
          $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
        }

        $output.= '<div class="col-6">Product Name: '.$product['stock_product_name'].'</div>
            <div class="col-6">Product Category: '.$product['cat_name'].'</div>
            <div class="col-6">Sub Category: '.$product['sub_category_name'].'</div>
            <div class="col-6">Product Brand: '.$product['brand_name'].'</div>
            <div class="col-3">Quantity: '.$product['stock_product_quantity'] .' '.$product['stock_product_unit'].'</div>
            <div class="col-3">Status: '.$status.' </div>
            <div class="col-6">Entered By: '.$product['user_name'].'</div>
            <div class="col-6">Creation Date: '. $Qobject->date_string($product['stock_product_created_at']).'</div>';
      }
    }

    echo $output;
  }
  
  //Fetch single product details for update
  if($_POST['btn_action'] == 'fetch_single')
  {
    $sqlBind  = $_POST['stock_product_id'];
    $query = "SELECT * FROM stock_products WHERE stock_product_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    
    if ($count > 0)
    {
      foreach ($result as $row => $product) {
        $output['stock_product_id'] = $product['stock_product_id'];
        $output['stock_prodoct_brand_id'] = $product['stock_prodoct_brand_id'];
        $output['stock_product_name'] = $product['stock_product_name'];
        $output['stock_product_unit'] = $product['stock_product_unit'];
      }
    }

    echo json_encode($output);
  }

  //Editing product
  if($_POST['btn_action'] == 'Edit')
  {

    $table = 'stock_products';

    $product_name = $_POST['product_name'];  
    $product_unit = $_POST['product_unit'];       
    $category_id = $_POST['category_id'];   
    $stock_product_sub_cat_id = $_POST['sub_cat_id'];        
    $brand_id = $_POST['brand_id'];      
       
    $report[] = '';
    $editId = $_POST['product_id'];
    if($product_name != '' && $category_id != '' && $brand_id != '' && $product_unit != ''){
      $data = array(	
        'stock_product_name' => $product_name, 	
        'stock_product_unit' => $product_unit, 	
        'stock_product_category_id' => $category_id,
        'stock_product_sub_cat_id' => $stock_product_sub_cat_id,
        'stock_prodoct_brand_id' => $brand_id
      );
    }
    $cond = "stock_product_id=$editId";

    $result = $Qobject->update($table, $data, $cond);
    if($result)
    {
      $report['success'] = 'Stock Product successfully updated';
      
    }
    else {
      $report['error'] = 'An errror occured, Stock Product could not be added';
    }
    echo json_encode($report);
  }

  if($_POST['btn_action'] == 'delete')
  {
      $table = 'stock_products';
      $status = '1';
      $delId = $_POST['stock_product_id'];
    
      $dispstat = '';
      if($_POST['stock_product_status'] == 1)
      {
        $dispstat = 'inactive';
      }
      else{
        $dispstat = 'active';
      }
      if($_POST['stock_product_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('stock_product_status' => $status );
      $cond = "stock_product_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data["success"] = ' Stock Product status changed ' .$dispstat;
      
      }
      else {
        $data['error'] = ' Stock Product status not updated';
  
      }
      echo json_encode($data);
  }

  //Fetches Data for Datatable
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    
    $query .= "
      SELECT * FROM stock_products INNER JOIN categories ON categories.cat_id = stock_products.stock_product_category_id  INNER JOIN sub_categories on sub_categories.sub_category_id = stock_products.stock_product_sub_cat_id INNER JOIN brands on brands.brand_id = stock_products.stock_prodoct_brand_id INNER JOIN users on users.user_id = stock_products.stock_product_created_by
    ";
   
    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE stock_product_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR cat_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR sub_category_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR brand_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR stock_product_created_at  LIKE "%'.$_POST["search"]["value"].'%" ';

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY stock_product_id DESC ';
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
      $status = '';
      if($row['stock_product_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Active</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
      }
                                       
    	$sub_array = array();  
      $sub_array[] = $id++;
      $sub_array[] = $row['stock_product_name'];
    	$sub_array[] = $row['brand_name'];
      $sub_array[] = $row['sub_category_name'];
      $sub_array[] = $row['cat_name'];

      $sub_array[] = '<span class="badge bg-xs bg-success">'.$row['stock_product_unit'].'</span>';
    
      
    
      $sub_array[] = $Qobject->date_string($row['stock_product_created_at']);

      $sub_array[] = $status;

      $sub_array[] = '<button name="update" id="'.$row["stock_product_id"].'" class="btn btn-xs btn-success view" >View <i class="fas fa-eye"></i></button>';
     
      $sub_array[] = '<button name="update" id="'.$row["stock_product_id"].'" class="btn btn-xs btn-warning update" >Update <i class="fas fa-pen"></i></button>';
      $sub_array[] = '<button name="delete" id="'.$row["stock_product_id"].'" class="btn btn-xs btn-danger delete"  data-status="'.$row["stock_product_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM stock_products";
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
