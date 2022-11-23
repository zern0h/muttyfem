<?php

/**
 *
 */
class Loader extends DB
{

    public function table_row_count($sql)
    {
        $stmt = $this->DBconnect->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
    //Load Products
    public function loadProduct(){
        $query = "SELECT * FROM products where product_status = 1 ORDER BY product_name";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '<option selected disabled>Choose Product</option>';
        if ($count > 0)
        {
            foreach ($result as $row => $stock_product) {
                $output.= '<option data-tokens="'.$stock_product["product_name"].'" value="'.$stock_product["product_id"].'">'.$stock_product["product_name"].' '.$stock_product["product_unit"].'</option>';
            }
        }

        return $output;
    }

      //Load Products
    public function loadHamper(){
        $query = "SELECT * FROM hamper_overview where hamper_status = 1 ORDER BY hamper_name";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '<option selected disabled>Choose Hamper</option>';
        if ($count > 0)
        {
            foreach ($result as $row => $stock_product) {
                $output.= '<option data-tokens="'.$stock_product["hamper_name"].'" value="'.$stock_product["hamper_overview_id"].'">'.$stock_product["hamper_name"].'</option>';
            }
        }

        return $output;
    }

    public function loadCategories(){
        $query = "SELECT * FROM categories where cat_status = 1 ORDER BY cat_name";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '<option selected disabled>Choose Categories</option>';
        if ($count > 0)
        {
            foreach ($result as $row => $cat) {
                $output.= '<option data-tokens="'.$cat["cat_name"].'" value="'.$cat["cat_id"].'">'.$cat["cat_name"].'</option>';
            }
        }

        return $output;
    }

    //loading vendors from the vendor table
    public function loadVendors(){
        $query = "SELECT * FROM suppliers where supplier_status = 1 ORDER BY supplier_name";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '<option selected disabled>Choose SUPPLIER/VENDOR</option>';
        if ($count > 0)
        {
            foreach ($result as $row => $supplier) {
                $output.= '<option data-tokens="'.$supplier["supplier_name"].'" value="'.$supplier["supplier_id"].'">'.$supplier["supplier_name"].'</option>';
            }
        }

        return $output;
    }

    //loading products for refund table
    public function loadProductRefund(){
        $query = "SELECT * FROM products";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '';
        if ($count > 0)
        {
            foreach ($result as $row => $products) {
                $output.= '<option value="'.$products["product_id"].'">'.$products["product_name"].'</option>';
            }
        }

        return $output;
    }

    //loading stock products
    public function stockProducts(){
        $query = "SELECT * FROM stock_products WHERE stock_product_status = 1 ORDER BY stock_product_name";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '<option selected disabled>Choose Stock Product</option>';
        if ($count > 0)
        {
            foreach ($result as $row => $products) {
                $output.= '<option  data-tokens="'.$products["stock_product_name"].'"   value="'.$products["stock_product_id"].'">'.$products["stock_product_name"].'</option>';
            }
        }

        return $output;
    }

    //Load Invoices for refund select box
    public function loadInvoice(){
        $query = "SELECT * FROM inventory_overview";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '';
        if ($count > 0)
        {
            foreach ($result as $row => $invoice) {
                $output.= '<option value="'.$invoice["inventory_overview_id"].'">'.$invoice["inventory_number"].'</option>';
            }
        }

        return $output;
    }

    //fetch suppliercode as dropdown
    public function loadSupplierCode(){
        $query = "SELECT supplier_code FROM suppliers WHERE supplier_status = 1";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '<option value="default">';
        if ($count > 0)
        {
        foreach ($result as $row => $supplier) {
            $output.= '<option value="'.$supplier["supplier_code"].'">';
        }
        }

        return $output;
    }

    //fetch suppliercode as dropdown
    public function loadPurchaseOrder(){
        $query = "SELECT po_number FROM purchase_order_overview WHERE po_overview_status = 0";
        $stmt = $this->DBconnect->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $this->table_row_count($query);

        $output = '<option value="default">';
        if ($count > 0)
            {
            foreach ($result as $row => $porder) {
                $output.= '<option value="'.$porder["po_number"].'">';
            }
        }

        return $output;
    }
 

}



?>
