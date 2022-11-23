<?php

/**
 *
 */
class Query extends DB
{

  public function select($sql)
  {
    $stmt = $this->DBconnect->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($table, $data)
  {
    $keys = implode(',', array_keys($data));
    $values = ":".implode(",:", array_keys($data));

    $sql = "INSERT INTO $table($keys) VALUES ($values)";
    $stmt = $this->DBconnect->prepare($sql);

    foreach ($data as $key => $value) {
      $stmt->bindValue(":$key",$value);
    }
    return $stmt->execute();
  }

  public function update($table, $data, $cond)
  {
    $keys = '';
    foreach ($data as $key => $value) {
      $keys .= "$key=:$key,";
    }
    $keys = rtrim($keys,",");

    $sql = "UPDATE $table SET $keys WHERE $cond ";
    $stmt = $this->DBconnect->prepare($sql);
    foreach ($data as $key => $value) {
      $stmt->bindValue(":$key",$value);
    }
    return $stmt->execute();
  }

  public function table_row_count($sql)
  {
    $stmt = $this->DBconnect->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
  }
  
  public function delete($sql){
    $stmt = $this->DBconnect->prepare($sql);
    return $stmt->execute();
  }

  public function image_upload($getFile, $randno)
  {
    $getExtension = strtolower(pathinfo($getFile['name'], PATHINFO_EXTENSION));
    $getNewName = $randno.'.'.$getExtension;
    $getDestination = $_SERVER['DOCUMENT_ROOT'] . '/spurpod/upload/'.$getNewName;
    move_uploaded_file($getFile['tmp_name'], $getDestination);
    return $getNewName;
  }

  public function validateImage($getFile)
  {
    $error = array();
    $getExtension = strtolower(pathinfo($getFile['name'], PATHINFO_EXTENSION));
    $extension = array('png', 'jpg', 'jpeg');
    if(!in_array($getExtension, $extension))
    {
      $error[] = 'Invalid extension';
    }
    if($getFile['name'] == '')
    {
      $error[] = 'Image cannot be empty';
    }
    return $error;
  }

  public function playerNumber()
  {
    $num = mt_rand(1,10000000);
    $str_length = 8;
    $str = substr(str_repeat(0, $str_length) . $num, -$str_length);
    return 'VS'.$str;
  }

  public function dateConvert($date)
  {
    $parts = explode('/', $date);
    $newDate ="$parts[2]-$parts[1]-$parts[0]";
    return $newDate;
  }

  //Setting Barcode
  public function setBarcode($data,$table, $condition,$limit){
   
    $query = "SELECT $data FROM $table order by $condition desc LIMIT $limit";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $count = $this->table_row_count($query);


    foreach( $result as $row){
        $last_id = $row["product_barcode"];
        $num = substr($last_id, 4);
    }

    if($count > 0){
        $product_code = 'PROD'.str_pad($num + 1, 7, 0, STR_PAD_LEFT);
    }
    else{
        $product_code = 'PROD0000001';
    }
   

    return $product_code;
  }

  //Setting invoice number
  public function invoiceNumber($data,$table, $condition,$limit){
   
    $query = "SELECT $data FROM $table order by $condition desc LIMIT $limit";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $count = $this->table_row_count($query);

    foreach( $result as $row){
        $last_id = $row["inventory_number"]; 
        $num = substr($last_id, 3);
    }

    if($count > 0){
        $barcode = 'INV'.str_pad($num + 1, 7, 0, STR_PAD_LEFT);
    }
    else{
        $barcode = 'INV0000001';
    }
    return $barcode;
  }

  //Setting supplier number
  public function supplierNumber($data,$table, $condition,$limit){
   
    $query = "SELECT $data FROM $table order by $condition desc LIMIT $limit";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $count = $this->table_row_count($query);


    foreach( $result as $row){
        $last_id = $row["supplier_code"];
        $num = substr($last_id, 3);
        
    }

    if($count > 0){
        $supplier_num = 'SUP'.str_pad($num + 1, 7, 0, STR_PAD_LEFT);
    }
    else{
        $supplier_num = 'SUP0000001';
    }
    return $supplier_num;
  }

  //Setting Purchase Order ID
  public function purchaseOrderNumber($data,$table, $condition,$limit){
   
    $query = "SELECT $data FROM $table order by $condition desc LIMIT $limit";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $count = $this->table_row_count($query);


    foreach( $result as $row){
        $last_id = $row["po_number"];
        $num = substr($last_id, 4);
        
    }

    if($count > 0){
        $purchase_order_num = 'PORD'.str_pad($num + 1, 7, 0, STR_PAD_LEFT);
    }
    else{
        $purchase_order_num = 'PORD0000001';
    }
    return $purchase_order_num;
  }

  public function podNumber($data,$table, $condition,$limit){
   
    $query = "SELECT $data FROM $table order by $condition desc LIMIT $limit";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $count = $this->table_row_count($query);


    foreach( $result as $row){
        $last_id = $row["pod_number"];
        $num = substr($last_id, 3);
        
    }

    if($count > 0){
        $proof_of_delivery_num = 'POD'.str_pad($num + 1, 7, 0, STR_PAD_LEFT);
    }
    else{
        $proof_of_delivery_num = 'POD0000001';
    }
    return $proof_of_delivery_num;
  } 

  public function hamperNum($data,$table, $condition,$limit){
   
    $query = "SELECT $data FROM $table order by $condition desc LIMIT $limit";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $count = $this->table_row_count($query);


    foreach( $result as $row){
        $last_id = $row["hamper_code"];
        $num = substr($last_id, 3);
        
    }

    if($count > 0){
        $proof_of_delivery_num = 'HAM'.str_pad($num + 1, 7, 0, STR_PAD_LEFT);
    }
    else{
        $proof_of_delivery_num = 'HAM0000001';
    }
    return $proof_of_delivery_num;
  }

  public function hamperSaleNum($data,$table, $condition,$limit){
   
    $query = "SELECT $data FROM $table order by $condition desc LIMIT $limit";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $count = $this->table_row_count($query);


    foreach( $result as $row){
        $last_id = $row["hamper_sales_number"];
        $num = substr($last_id, 3);
        
    }

    if($count > 0){
        $proof_of_delivery_num = 'HPS'.str_pad($num + 1, 7, 0, STR_PAD_LEFT);
    }
    else{
        $proof_of_delivery_num = 'HPS0000001';
    }
    return $proof_of_delivery_num;
  }

  public function date_string ($time)
  {
    $now = new DateTime($time);
    $ymdNow = $now->format("M,d,Y h:i:s A");
    return $ymdNow;
  }

  //Load categories as select options
  public function loadCategories(){
    $query = "SELECT * FROM categories";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = $this->table_row_count($query);

    $output = '';
    if ($count > 0)
    {
      foreach ($result as $row => $category) {
        $output.= '<option value="'.$category["cat_id"].'">'.$category["cat_name"].'</option>';
      }
    }

    return $output;
  }

  //Load brands as options
  public function loadBrand($cat_id){
    $query = "SELECT * FROM sub_categories WHERE category_id =  $cat_id";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = $this->table_row_count($query);

    $output = '<option value="">Choose Sub Categories</option>';
    if ($count > 0)
    {
      foreach ($result as $row => $sub_cat) {
        $output.= '<option value="'.$sub_cat["sub_category_id"].'">'.$sub_cat["sub_category_name"].'</option>';
      }
    }

    return $output;
  }
  
  //Load sub_categories as options
  public function loadSubcategories($cat_id){
    $query = "SELECT * FROM sub_categories WHERE category_id =  $cat_id";
    $stmt = $this->DBconnect->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = $this->table_row_count($query);

    $output = '<option value="">CHOOSE SUB CATEGORIES</option>';
    if ($count > 0)
    {
      foreach ($result as $row => $sub_cat) {
        $output.= '<option value="'.$sub_cat["sub_category_id"].'">'.$sub_cat["sub_category_name"].'</option>';
      }
    }

    return $output;
  }
  public function title()
  {
      global $title;
      if(isset($title)){
          echo $title;
      }
      else{
          echo 'Default';
      }
  }

}



?>
