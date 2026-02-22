<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

 
  //load sub_cateogories
  if($_POST['btn_action'] == 'load_sub_cat')
  {
    $cat_id =  $_POST['cat_id'];
    echo $Qobject->loadSubcategories($cat_id);
  }
 

  //Adding products to the database
  if($_POST['btn_action'] == 'Add')
  {
    
    $get_item = 'product_barcode';
    $table = 'products';
    $cond = 'product_id';
    $limit = 1;

    $query = "SELECT * FROM $table";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    $product_name 	= $_POST['product_name'];
    $product_category_id 	= $_POST['category'];
    $product_sub_category_id 	= $_POST['sub_cat'];
    $product_vendor_id 	= $_POST['vendor'];
    $product_barcode 	= $Qobject->setBarcode($get_item,$table, $cond,$limit);
    $manufacturer_barcode 	= $_POST['manufacturer_barcode'];
    $product_description 	= $_POST['description'];
    $product_unit 	= $_POST['product_unit'];
    //$pack_size 	= $_POST['pack_size'];
    $retail_price = $_POST['retailPrice'];
    $product_cost_price 	= $_POST['product_cost'];
    $vat 	= $_POST['vat'];
    $product_markup 	= $_POST['product_markup'];
    $product_entered_by 	= $_POST['entered_by'];
    $product_status 	= 1;
    $product_date 	= date('Y-m-d H:i:s');
    
    $message[] = '';

    $data = array(
      'product_name' 	=> $product_name,
      'product_category_id' =>	$product_category_id,
      'product_sub_category_id' => $product_sub_category_id,
      'product_vendor_id' =>	$product_vendor_id,
      'product_barcode' =>	$product_barcode,
      'manufacturer_barcode' 	=> $manufacturer_barcode,
      'product_description' =>	$product_description,
      'product_unit' =>	$product_unit,
      //'pack_size' => $pack_size,
      'retail_price' => $retail_price, 
      'product_cost_price' =>	$product_cost_price,
      'vat' =>	$vat,
      'product_markup' =>	$product_markup,
      'product_entered_by' =>	$product_entered_by,
      'product_status' =>	$product_status,
      'product_date' => $product_date
    );
    $result = $Qobject->insert($table, $data);
    if($result)
    {
      $message['success'] = 'Congrats! New Product Added';
      
    }
    else {
        $message['error'] = 'Product could not be added, an error occured';
        
    }

    echo json_encode($message);
  }

  //fetchomg product details to be viewed
  if($_POST['btn_action'] == 'product_details')
  {
    $sqlBind = $_POST['product_id'];
    $query = "
    SELECT * FROM products INNER JOIN categories on categories.cat_id = products.product_category_id INNER JOIN sub_categories on sub_categories.sub_category_id = products.product_sub_category_id INNER JOIN suppliers on suppliers.supplier_id = products.product_vendor_id INNER JOIN users on users.user_id = products.product_entered_by WHERE product_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $output = '';
    if($count > 0)
    {
      	 	 	 	 	 	 		 	 
      foreach ($result as $row => $product) {
        $status = '';
        if($product['product_status'] == 1)
        {
          $status = '<span class="badge bg-xs bg-success">Active</span>';
        }
        else {
          $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
        }

        

        $price = $product['product_cost_price'] + ($product['product_cost_price'] * ($product['product_markup']/100)) + ($product['product_cost_price'] * ($product['vat']/100));
        
        $recordedLevel = '';

        if($product['recorded_level'] == 0)
        {
          $recordedLevel = '<span class="badge bg-xs bg-danger">'.$product['recorded_level'].'</span>';
        }
        else if($product['recorded_level'] <= 10 ){
          $recordedLevel = '<span class="badge bg-xs bg-warning">'.$product['recorded_level'].'</span>';
        }
        else if($product['recorded_level'] <= 20  ){
          $recordedLevel = '<span class="badge bg-xs bg-primary">'.$product['recorded_level'].'</span>';
        }
        else if($product['recorded_level']  > 20 ){
          $recordedLevel = '<span class="badge bg-xs bg-success">'.$product['recorded_level'].'</span>';
        }                
                    
        

        $output.= '<div class="col-6">PRODUCT NAME: '.$product['product_name'].'</div>
            <div class="col-6">PRODUCT SUPPLIER/Vendor: '.$product['supplier_name'].'</div>
            <div class="col-6">PRODUCT CATEGORY: '.$product['cat_name'].'</div>
            <div class="col-6">PRODUCT SUB-CATEGORY: '.$product['sub_category_name'].'</div>
            <div class="col-6">PRODUCT BARCODE: '.$product['product_barcode'].'</div><div class="col-6">MANUFACTURER BARCODE: '.$product['manufacturer_barcode'].'</div>
           
            <div class="col-3">RECORDED LEVEL: '.$recordedLevel.'</div>
            <div class="col-3">PRODUCT UNIT: '.$product['product_unit'].'</div>
            <div class="col-3">RETAIL PRICE: '.$product['retail_price'].'</div>
            <div class="col-3">COST PRICE: '.$product['product_cost_price'].' </div>
            <div class="col-3">MARK UP: '.$product['product_markup'].' </div>
            <div class="col-3">VAT: '.$product['vat'].' </div>
            <div class="col-3">SUGGESTED RETAIL PRICE: '.number_format($price,2).' </div>
            <div class="col-3">STATUS: '.$status.' </div>
            <div class="col-4">ENTERED BY: '.$product['user_name'].'</div>
            <div class="col-6">CREATED AT: '. $Qobject->date_string($product['product_date']).'</div>
            <div class="col-12">DESCRIPTION: '.$product['product_description'].' </div>';
      }
    }

    echo $output;
  }
  
  //Fetch single product details for update
  if($_POST['btn_action'] == 'fetch_single')
  {
    $sqlBind  = $_POST['product_id'];
    $query = "SELECT * FROM products WHERE product_id = $sqlBind";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if ($count > 0)
    {
      
      foreach ($result as $row => $product) {
        $output['product_id'] = $product['product_id'];
        $output['product_name'] = $product['product_name'];

        $output['product_vendor_id'] = $product['product_vendor_id'];
       
        $output['manufacturer_barcode'] = $product['manufacturer_barcode'];
        $output['product_description'] = $product['product_description'];
      
        $output['product_unit'] = $product['product_unit'];
        //$output['pack_size'] = $product['pack_size'];
        $output['retail_price'] = $product['retail_price'];
        $output['product_cost_price'] = $product['product_cost_price'];
        $output['vat'] = $product['vat'];
        $output['product_markup'] = $product['product_markup'];
        $output['product_entered_by'] = $product['product_entered_by'];
      }
    }

    echo json_encode($output);
  }

  //Editing product
  if($_POST['btn_action'] == 'Edit')
  {

    $table = 'products';

    $product_name 	= $_POST['product_name'];
    $product_category_id 	= $_POST['category'];
    $product_sub_category_id 	= $_POST['sub_cat'];
    $product_vendor_id 	= $_POST['vendor'];
    $manufacturer_barcode 	= $_POST['manufacturer_barcode'];
    $product_description 	= $_POST['description'];
    $product_unit 	= $_POST['product_unit'];
    //$pack_size 	= $_POST['pack_size'];
    $retail_price = $_POST['retailPrice'];
    $product_cost_price 	= $_POST['product_cost'];
    $vat 	= $_POST['vat'];
    $product_markup 	= $_POST['product_markup'];
    $product_updated_by 	= $_POST['entered_by'];
       
    $message[] = '';
    $editId = $_POST['product_id'];

    $data = array(
      'product_name' 	=> $product_name,
      'product_category_id' =>	$product_category_id,
      'product_sub_category_id' => $product_sub_category_id,
      'product_vendor_id' =>	$product_vendor_id,
      'manufacturer_barcode' 	=> $manufacturer_barcode,
      'product_description' =>	$product_description,
      'product_unit' =>	$product_unit,
      //'pack_size' => $pack_size,
      'retail_price' => $retail_price,
      'product_cost_price' =>	$product_cost_price,
      'vat' =>	$vat,
      'product_markup' =>	$product_markup,
    
    );    
  
    $cond = "product_id=$editId";
      
    $result = $Qobject->update($table, $data, $cond);
    if($result)
    {
     	
      $table2 = 'update_history';
      $product_updated_by = $product_updated_by;

      
      $update_content = $product_name.' '. $product_cost_price.' '.$vat.' '.$product_markup.' '.$product_vendor_id.' '.$product_category_id.' '. $product_sub_category_id.' '.$product_unit.' '.$retail_price.' '.$product_description;
      
      $update_time = date('Y-m-d H:i:s');

      $data2 = array(
        'product_updated_by' 	=> $product_updated_by,
        'update_content' =>	$update_content,
        'update_time' => $update_time
      );
      $result2 = $Qobject->insert($table2, $data2);
      if($result2)
      {
        $message['success'] = 'Product successfully Updated';  
      }
     
    }
    else {
      $message['error'] = 'An errror occured, Product could not be Updated';
    }
    echo json_encode($message);
  }

  if($_POST['btn_action'] == 'delete')
  {
      $table = 'products';
      $status = '1';
      $delId = $_POST['product_id'];
      
      $dispstat = '';
      if($_POST['product_status'] == 1)
      {
        $dispstat = 'inactive';
      }
      else{
        $dispstat = 'active';
      }
      if($_POST['product_status'] == 1)
      {
        $status = '0';
      }

      $data =  array('product_status' => $status );
      $cond = "product_id=$delId";
      $result = $Qobject->update($table, $data, $cond);
      if(isset($result))
      {
        $data["success"] = ' Brand status changed ' .$dispstat;
      
      }
      else {
        $data['error'] = ' Player status not updated';
  
      }
      echo json_encode($data);
  }

  //Generate Barcode
  if($_POST['btn_action'] == 'barcode'){
      include 'includes/barcode128.php';
     
      $product_id = $_POST['barcode'];
      $rate = $_POST['price'];
      $output = '';

        $output .= '<style type="text/css" media="print">
            @page 
            {
                size: auto;   
                margin: 0mm;  
        
            }
        </style>';
        $output .= "<div style='margin-left: 5%'><p class='inline'>".bar128(stripcslashes($_POST['barcode']))."<span style='font-size: 13px;' ><b>Price: ".number_format($rate,2)." </b><span></p></div>";

      echo $output;
      
  }


  //Fetches Data for Datatable
  if($_POST['btn_action'] == 'load_table')
  {
    $query = '';

    $output = array();
    
    $query .= "
      SELECT * FROM products  INNER JOIN suppliers on suppliers.supplier_id = products.product_vendor_id INNER JOIN users on users.user_id = products.product_entered_by
    ";
   
    if(isset($_POST["search"]["value"]))
    {
    	$query .= 'WHERE product_name LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR product_vendor_id LIKE "%'.$_POST["search"]["value"].'%" ';
      $query .= 'OR product_date  LIKE "%'.$_POST["search"]["value"].'%" ';

    }

    if(isset($_POST['order']))
    {
    	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
    	$query .= 'ORDER BY product_id DESC ';
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
      if($row['product_status'] == 1)
      {
        $status = '<span class="badge bg-xs bg-success">Active</span>';
      }
      else {
        $status = '<span class="badge bg-xs bg-danger">Inactive</span>';
      }
      
      $recordedLevel = '';

      if($row['recorded_level'] == 0)
      {
        $recordedLevel = '<span class="badge bg-xs bg-danger">'.$row['recorded_level'].'</span>';
      }
      else if($row['recorded_level'] <= 10 ){
        $recordedLevel = '<span class="badge bg-xs bg-warning">'.$row['recorded_level'].'</span>';
      }
      else if($row['recorded_level'] <= 20  ){
        $recordedLevel = '<span class="badge bg-xs bg-primary">'.$row['recorded_level'].'</span>';
      }
      else if($row['recorded_level']  > 20 ){
        $recordedLevel = '<span class="badge bg-xs bg-success">'.$row['recorded_level'].'</span>';
      }                    
                                       
    	$sub_array = array();  
      $sub_array[] = $id++;
      $sub_array[] = $row['product_name'];
    	$sub_array[] = $row['supplier_name'];
      $sub_array[] = $recordedLevel;
      $sub_array[] = number_format($row['retail_price'],2);

      
    
      $sub_array[] = $Qobject->date_string($row['product_date']);

      $sub_array[] = $status;

      $sub_array[] = '<button name="update" id="'.$row["product_id"].'" class="btn btn-xs btn-success view" >View <i class="fas fa-eye"></i></button>';
      $sub_array[] = '<a href="sticker.php?prodNum=' . $row["product_barcode"] .'" target="_blank" class="btn btn-primary ">Barcode <i class="fas fa-barcode"></a>';

   
      $sub_array[] = '<button name="update" id="'.$row["product_id"].'" class="btn btn-xs btn-warning update" >Update <i class="fas fa-pen"></i></button>';
      $sub_array[] = '<button name="delete" id="'.$row["product_id"].'" class="btn btn-xs btn-danger delete"  data-status="'.$row["product_status"].'" >Delete <i class="fas fa-times-circle"></i></button>';
    	
    	$data[] = $sub_array;
    }

    $sql = "SELECT * FROM products";
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
