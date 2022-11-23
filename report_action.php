
<?php
include 'includes/DB.php';
include 'includes/Query.php';
require('../fpdf/fpdf.php');

$Qobject = new Query;


if(isset($_POST['btn_action']))
{

    if($_POST['btn_action'] == 'Daily Sales')
    {
        $sqlBind = $_POST['dailyDate'];
        $query = "SELECT inventory_price,inventory_product_id, SUM(inventory_total_price) as TptalSum, SUM(inventory_quantity) as ToalQty, products.product_name as product_name from inventory_order_product LEFT JOIN products ON products.product_id = inventory_order_product.inventory_product_id WHERE date(inventory_order_date) = '$sqlBind' GROUP BY inventory_product_id  ORDER BY ToalQty DESC";

        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $id = 1;
        $total = 0;

        $pdf = new FPDF('p', 'mm', 'A4');
    
        $pdf ->AddPage();
        
        //set font to arial, bold, 14pt
    
        $pdf->SetFont('Arial','B',14);
    
        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];
    
        $pdf->Cell(130, 5, 'MUTTYFEM SUPERMARKET',0, 0);
        $pdf->Cell(59, 5, 'Sales Report', 0, 1); //end of line
    
        //Setting font 
        $pdf->SetFont('Arial','',12);
    
        $pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP OPPOSITE VANGUARD PHARMACY ELEYELE/ERUWA  ROAD]', 0, 1); //end of line
            
        $pdf->Cell(130, 5, '[OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA]', 0, 1);
    
        $pdf->Cell(120, 5, 'Phone[08138331990, 08138333190]', 0, 1);
        $pdf->Cell(100, 5, 'Sales Report For', 0, 0); 
        $pdf->Cell(50, 5,$Qobject->date_string($sqlBind), 0, 1); //end of line
    
        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line
    
        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line
    
        //invoice contents
        $pdf->SetFont('Arial','B',11.5);
        $pdf->Cell(20, 5, '#', 1, 0);
        $pdf->Cell(75, 5, 'Item', 1, 0);
        $pdf->Cell(21, 5, 'Quantity', 1, 0); 
        $pdf->Cell(24, 5, 'Price', 1, 0, 'C'); 
        $pdf->Cell(34, 5, 'Amount (NGN)', 1, 1, 'C');//end of line         
        if($count > 0)
        {
                               
            foreach ($result as $row => $inventory) {
            
                $total += $inventory["TptalSum"];
                
                $pdf->SetFont('Arial','',12);
    
                //Numbers are right-aligned so we give 'R' after new line parameter
                $pdf->Cell(20, 5, $id++, 1, 0,);
                $pdf->Cell(75, 5, $inventory["product_name"], 1, 0,);
                $pdf->Cell(21, 5, $inventory["ToalQty"], 1, 0,'C'); 
                $pdf->Cell(24, 5, number_format($inventory["inventory_price"],2), 1, 0,'C'); 
                $pdf->Cell(34, 5, number_format($inventory["TptalSum"],2) , 1, 1,'R');//end of line
    
            }
    
        }else{
            $pdf->SetFont('Arial','',12);
    
            //Numbers are right-aligned so we give 'R' after new line parameter
            $pdf->Cell(20, 5, $id++, 1, 0,);
            $pdf->Cell(75, 5, '-', 1, 0,'C');
            $pdf->Cell(21, 5, '0', 1, 0,'C'); 
            $pdf->Cell(24, 5, number_format(0,2), 1, 0,'C'); 
            $pdf->Cell(34, 5, number_format(0,2) , 1, 1,'R');//end of line
        }

        //Summary
        
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(116, 5, '', 0, 0);
        $pdf->Cell(24, 5, 'Total', 0, 0); 
        
        $pdf->Cell(34, 5, number_format($total,2), 1, 1,'R');//end of line
        $pdf->SetFont('Arial','',12);
    
        $pdf->Cell(189, 5, '[Sales Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', 'SalesReport.'.$sqlBind.'.pdf');

    }

    if($_POST['btn_action'] == 'Daily Finance')
    {
        $sqlBind = $_POST['dailyDate'];
        
        $query = "SELECT SUM(inventory_total_price) as sales FROM inventory_order_product WHERE date(inventory_order_date) = '$sqlBind'";

        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $id = 1;
        $total = 0;

        $pdf = new FPDF('p', 'mm', 'A4');

        $pdf ->AddPage();

    
        //set font to arial, bold, 14pt

        $pdf->SetFont('Arial','B',14);

        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

        $pdf->Cell(130, 5, 'MUTTYFEM SUPERMARKET',0, 0);
        $pdf->Cell(59, 5, 'Financial Report', 0, 1); //end of line

        //Setting font 
        $pdf->SetFont('Arial','',12);

       $pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP OPPOSITE VANGUARD PHARMACY ELEYELE/ERUWA  ROAD]', 0, 1); //end of line
            
        $pdf->Cell(130, 5, '[OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA]', 0, 1); //end of line

        $pdf->Cell(120, 5, 'PHONE[08138331990, 08138333190]', 0, 1);
        $pdf->Cell(100, 5, 'Financial Report For', 0, 0); 
        $pdf->Cell(50, 5,$Qobject->date_string($sqlBind), 0, 1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Income',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);
    
        $pdf->Cell(80, 5, 'Item', 1, 0);
    
        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line                   
        if($count > 0)
        {
                     
            foreach ($result as $row => $inventory) {
            
                $total += $inventory["sales"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, 'Sales', 1, 0,);
                $pdf->Cell(40, 5, number_format($inventory["sales"],2), 1, 1,'C'); 
            }
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5, 'Sales', 1, 0,);
            $pdf->Cell(40, 5, number_format(0,2), 1, 1,'C');
        }

        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter
        
        $pdf->Cell(80, 5, 'Gross Income', 1, 0,);
        $pdf->Cell(40, 5, number_format($total,2), 1, 1,'C');

        $query2 = "SELECT procurement_item,procurement_amount FROM procurements WHERE date(procurement_date) = '$sqlBind' ";
        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $total_expen = 0;
        $total_pro = 0;
        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Expenditure',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);
    
        $pdf->Cell(80, 5, 'Item', 1, 0);
    
        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line 
        
        if($count2 > 0){
            foreach ($result2 as $row => $procurement) {
        
                $total_pro += $procurement["procurement_amount"];
                
                $pdf->SetFont('Arial','',12);
    
                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, $procurement['procurement_item'], 1, 0,);
                $pdf->Cell(40, 5,number_format($procurement['procurement_amount'],2), 1, 1,'C'); 
            }
        }else{
            $pdf->SetFont('Arial','',12);
    
            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5, 'Procurement', 1, 0,);
            $pdf->Cell(40, 5,number_format(0,2), 1, 1,'C'); 
        }
        //SELECT * from inventory_order_product INNER JOIN products ON products.product_id = inventory_product_id  where date(inventory_order_date) = '2020-11-10'
        $query3 = "SELECT SUM(inventory_total_price) as cost FROM inventory_order_product where date(inventory_order_date) = '$sqlBind'";
        $result3 = $Qobject->select($query3);
        $count3 = $Qobject->table_row_count($query3);
        $cost = 0;
        if($count3 > 0){
            foreach( $result3 as $row){
                $cost += $row["cost"];   
            }
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80, 5, 'Cost of Goods', 1, 0,);
            $pdf->Cell(40, 5,  number_format($cost,2), 1, 1,'C'); 
        }else{
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80, 5, 'Cost of Goods', 1, 0,);
            $pdf->Cell(40, 5,  number_format($cost,2), 1, 1,'C'); 
        }
        
        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter
        $total_expen = $total_pro + $cost;
        $pdf->Cell(80, 5, 'Total Expenditure', 1, 0,);
        $pdf->Cell(40, 5, number_format($total_expen,2), 1, 1,'C');

        //Summary
        $pdf->Cell(189,10,'',0,1); //end of line
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(100, 5, 'Net Income (NGN)', 0, 0);
        $pdf->Cell(40, 5, number_format($total - $total_expen,2), 0, 1); 
        
        $pdf->Cell(189, 5, '[Financial Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', 'Financial Report.'.$sqlBind.'.pdf');
    
    }

    if($_POST['btn_action'] == 'Monthly Sales')
    {
        $year  =  $_POST['monthYear'];
        $month  =  $_POST['month'];

        $sqlBind = $year .'-'. $month;   
        
        $first_day = $sqlBind . "-01";
        $query = "SELECT inventory_price,inventory_product_id, SUM(inventory_total_price) as TptalSum, SUM(inventory_quantity) as ToalQty, products.product_name as product_name from inventory_order_product LEFT JOIN products ON products.product_id = inventory_order_product.inventory_product_id WHERE inventory_order_date between '$first_day'AND LAST_DAY('$first_day')  GROUP BY inventory_product_id ORDER BY ToalQty DESC";

        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $id = 1;
        $total = 0;

        $pdf = new FPDF('p', 'mm', 'A4');

        $pdf ->AddPage();

    
        //set font to arial, bold, 14pt

        $pdf->SetFont('Arial','B',14);

        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

        $pdf->Cell(130, 5, 'Clover Cuties',0, 0);
        $pdf->Cell(59, 5, 'Sales Report', 0, 1); //end of line

        //Setting font 
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[No.37 Oyo Road, Opposite Providence Court, Sango Mokola Road,]', 0, 1); //end of line

        $pdf->Cell(130, 5, '[Ibadan, Oyo State, Nigeria]', 0, 1); //end of line

        $pdf->Cell(120, 5, 'Phone[+2348027111853]', 0, 1);
        $pdf->Cell(100, 5, 'Sales Report For', 0, 0); 
        $pdf->Cell(50, 5,$Qobject->date_string($sqlBind), 0, 1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //invoice contents
        $pdf->SetFont('Arial','B',11.5);
        $pdf->Cell(20, 5, '#', 1, 0);
        $pdf->Cell(75, 5, 'Item', 1, 0);
        $pdf->Cell(21, 5, 'Quantity', 1, 0); 
        $pdf->Cell(24, 5, 'Price', 1, 0, 'C'); 
        $pdf->Cell(34, 5, 'Amount (NGN)', 1, 1, 'C');//end of line                            

        if($count > 0)
        {
            foreach ($result as $row => $inventory) {
            
                $total += $inventory["TptalSum"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                $pdf->Cell(20, 5, $id++, 1, 0,);
                $pdf->Cell(75, 5, $inventory["product_name"], 1, 0,);
                $pdf->Cell(21, 5, $inventory["ToalQty"], 1, 0,'C'); 
                $pdf->Cell(24, 5, number_format($inventory["inventory_price"],2), 1, 0,'C'); 
                $pdf->Cell(34, 5, number_format($inventory["TptalSum"],2) , 1, 1,'R');//end of line

            }
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            $pdf->Cell(20, 5, $id++, 1, 0,);
            $pdf->Cell(75, 5, '-', 1, 0,);
            $pdf->Cell(21, 5, '0', 1, 0,'C'); 
            $pdf->Cell(24, 5, number_format(0,2), 1, 0,'C'); 
            $pdf->Cell(34, 5, number_format(0,2) , 1, 1,'R');//end of line
        }
        //Summary
        
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(116, 5, '', 0, 0);
        $pdf->Cell(24, 5, 'Total', 0, 0); 
        
        $pdf->Cell(34, 5, number_format($total,2), 1, 1,'R');//end of line
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[Sales Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', 'SalesReport.'.$sqlBind.'.pdf');
       
    }

    if($_POST['btn_action'] == 'Monthly Finance')
    {
        $year  =  $_POST['monthYear'];
        $month  =  $_POST['month'];

        $sqlBind = $year .'-'. $month;     
        $first_day = $sqlBind . "-01";

        $query = " SELECT SUM(inventory_total_price) as sales FROM inventory_order_product WHERE inventory_order_date between '$first_day'AND LAST_DAY('$first_day')";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $total = 0;

        $pdf = new FPDF('p', 'mm', 'A4');

        $pdf ->AddPage();

        //set font to arial, bold, 14pt

        $pdf->SetFont('Arial','B',14);

        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

        $pdf->Cell(130, 5, 'Clover Cuties',0, 0);
        $pdf->Cell(59, 5, 'Financial Report', 0, 1); //end of line

        //Setting font 
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[No.37 Oyo Road, Opposite Providence Court, Sango Mokola Road,]', 0, 1); //end of line

        $pdf->Cell(130, 5, '[Ibadan, Oyo State, Nigeria]', 0, 1); //end of line

        $pdf->Cell(120, 5, 'Phone[+2348027111853]', 0, 1);
        $pdf->Cell(100, 5, 'Financial Report For', 0, 0); 
        $pdf->Cell(50, 5,$Qobject->date_string($sqlBind), 0, 1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Income',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(80, 5, 'Item', 1, 0);

        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line                  
        if($count > 0)
        {
                     
            foreach ($result as $row => $inventory) {
            
                $total += $inventory["sales"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, 'Sales', 1, 0,);
                $pdf->Cell(40, 5, number_format($inventory["sales"],2), 1, 1,'C'); 
            }
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5, 'Sales', 1, 0,);
            $pdf->Cell(40, 5, number_format(0,2), 1, 1,'C'); 
        }
        //Summary
        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter
        
        $pdf->Cell(80, 5, 'Gross Income', 1, 0,);
        $pdf->Cell(40, 5, number_format($total,2), 1, 1,'C');

        $query2 = "SELECT procurement_item,procurement_amount FROM procurements WHERE procurement_date between '$first_day'AND LAST_DAY('$first_day')";
        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $total_expen = 0;
        $total_pro = 0;
        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Expenditure',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(80, 5, 'Item', 1, 0);

        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line   
        if($count2 > 0){
            foreach ($result2 as $row => $procurement) {
        
                $total_pro += $procurement["procurement_amount"];
                
                $pdf->SetFont('Arial','',12);
    
                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, $procurement['procurement_item'], 1, 0,);
                $pdf->Cell(40, 5,number_format($procurement['procurement_amount'],2), 1, 1,'C'); 
            }
        }else{
            $pdf->SetFont('Arial','',12);
    
            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5,'Procurement', 1, 0,);
            $pdf->Cell(40, 5,number_format(0,2), 1, 1,'C'); 
        }                      
        
        $query3 = "SELECT SUM(inventory_cost_price * inventory_quantity) as cost FROM inventory_order_product WHERE inventory_order_date between '$first_day'AND LAST_DAY('$first_day')";
        $result3 = $Qobject->select($query3);
        $count3 = $Qobject->table_row_count($query3);
        $cost = 0;
        if($count3 > 0){
            foreach( $result3 as $row){
                $cost += $row["cost"]; 
            }
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80, 5, 'Cost of Goods', 1, 0,);
            $pdf->Cell(40, 5,  number_format($cost,2), 1, 1,'C'); 
        }else{
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80, 5, 'Cost of Goods', 1, 0,);
            $pdf->Cell(40, 5,  number_format($cost,2), 1, 1,'C'); 
        }
          
        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter
        $total_expen = $total_pro + $cost;
        $pdf->Cell(80, 5, 'Total Expenditure', 1, 0,);
        $pdf->Cell(40, 5, number_format($total_expen,2), 1, 1,'C');

        //Summary
        $pdf->Cell(189,10,'',0,1); //end of line
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(100, 5, 'Net Income (NGN)', 0, 0);
        $pdf->Cell(40, 5, number_format($total - $total_expen,2), 0, 1); 
        
        $pdf->Cell(189, 5, '[Financial Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', 'Financial Report.'.$sqlBind.'.pdf');
        
    }

    if($_POST['btn_action'] == 'Weekly Sales')
    {
        $firstDate  =  $_POST['firstDate'];
        $lastDate  =  $_POST['lastDate'];   

        $year = $firstDate.' to '.$lastDate;
        $query = "SELECT inventory_price,inventory_product_id, SUM(inventory_total_price) as TptalSum, SUM(inventory_quantity) as ToalQty, products.product_name as product_name from inventory_order_product LEFT JOIN products ON products.product_id = inventory_order_product.inventory_product_id WHERE date(inventory_order_date) between '$firstDate'AND '$lastDate'  GROUP BY inventory_product_id ORDER BY ToalQty DESC";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $id = 1;
        $total = 0;
        $pdf = new FPDF('p', 'mm', 'A4');

        $pdf ->AddPage();

        //set font to arial, bold, 14pt

        $pdf->SetFont('Arial','B',14);

        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

        $pdf->Cell(130, 5, 'Clover Cuties',0, 0);
        $pdf->Cell(59, 5, 'Sales Report', 0, 1); //end of line

        //Setting font 
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[No.37 Oyo Road, Opposite Providence Court, Sango Mokola Road,]', 0, 1); //end of line

        $pdf->Cell(130, 5, '[Ibadan, Oyo State, Nigeria]', 0, 1); //end of line

        $pdf->Cell(120, 5, 'Phone[+2348027111853]', 0, 1);
        $pdf->Cell(100, 5, 'Sales Report For', 0, 0); 
        $pdf->Cell(50, 5,$year, 0, 1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //invoice contents
        $pdf->SetFont('Arial','B',11.5);
        $pdf->Cell(20, 5, '#', 1, 0);
        $pdf->Cell(75, 5, 'Item', 1, 0);
        $pdf->Cell(21, 5, 'Quantity', 1, 0); 
        $pdf->Cell(24, 5, 'Price', 1, 0, 'C'); 
        $pdf->Cell(34, 5, 'Amount (NGN)', 1, 1, 'C');//end of line                     
        if($count > 0)
        {
                   
            foreach ($result as $row => $inventory) {
            
                $total += $inventory["TptalSum"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                $pdf->Cell(20, 5, $id++, 1, 0,);
                $pdf->Cell(75, 5, $inventory["product_name"], 1, 0,);
                $pdf->Cell(21, 5, $inventory["ToalQty"], 1, 0,'C'); 
                $pdf->Cell(24, 5, number_format($inventory["inventory_price"],2), 1, 0,'C'); 
                $pdf->Cell(34, 5, number_format($inventory["TptalSum"],2) , 1, 1,'R');//end of line

            }

            
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            $pdf->Cell(20, 5, $id++, 1, 0,);
            $pdf->Cell(75, 5, '-', 1, 0,);
            $pdf->Cell(21, 5, '-', 1, 0,'C'); 
            $pdf->Cell(24, 5, number_format(0,2), 1, 0,'C'); 
            $pdf->Cell(34, 5, number_format(0,2) , 1, 1,'R');//end of line
        }
        //Summary
            
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(116, 5, '', 0, 0);
        $pdf->Cell(24, 5, 'Total', 0, 0); 
        
        $pdf->Cell(34, 5, number_format($total,2), 1, 1,'R');//end of line
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[Sales Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', 'SalesReport.'.$year.'.pdf');
    }

    if($_POST['btn_action'] == 'Weekly Finance')
    {  
        $firstDate  =  $_POST['firstDate'];
        $lastDate  =  $_POST['lastDate']; 
        $year = $firstDate.' to '.$lastDate;

        $query = " SELECT SUM(inventory_total_price) as sales FROM inventory_order_product WHERE date(inventory_order_date) between '$firstDate'AND '$lastDate' ";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $total = 0;
        $pdf = new FPDF('p', 'mm', 'A4');

        $pdf ->AddPage();

        //set font to arial, bold, 14pt

        $pdf->SetFont('Arial','B',14);

        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

        $pdf->Cell(130, 5, 'Clover Cuties',0, 0);
        $pdf->Cell(59, 5, 'Financial Report', 0, 1); //end of line

        //Setting font 
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[No.37 Oyo Road, Opposite Providence Court, Sango Mokola Road,]', 0, 1); //end of line

        $pdf->Cell(130, 5, '[Ibadan, Oyo State, Nigeria]', 0, 1); //end of line

        $pdf->Cell(120, 5, 'Phone[+2348027111853]', 0, 1);
        $pdf->Cell(100, 5, 'Financial Report For', 0, 0); 
        $pdf->Cell(50, 5,$year, 0, 1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Income',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(80, 5, 'Item', 1, 0);

        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line                            
        if($count > 0)
        {
            
            foreach ($result as $row => $inventory) {
            
                $total += $inventory["sales"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, 'Sales', 1, 0,);
                $pdf->Cell(40, 5, number_format($inventory["sales"],2), 1, 1,'C'); 
            }

        
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5, 'Sales', 1, 0,);
            $pdf->Cell(40, 5, number_format($total,2), 1, 1,'C'); 
        }
        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter

        $pdf->Cell(80, 5, 'Gross Income', 1, 0,);
        $pdf->Cell(40, 5, number_format($total,2), 1, 1,'C');

        //Summary

        $query2 = "SELECT procurement_item,procurement_amount FROM procurements WHERE date(procurement_date) between '$firstDate'AND '$lastDate'";
        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $total_expen = 0;
        $total_pro = 0;
        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Expenditure',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(80, 5, 'Item', 1, 0);

        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line      
        if ($count2 > 0){
            foreach ($result2 as $row => $procurement) {

                $total_pro += $procurement["procurement_amount"];
                
                $pdf->SetFont('Arial','',12);
            
                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, $procurement['procurement_item'], 1, 0,);
                $pdf->Cell(40, 5,number_format($procurement['procurement_amount'],2), 1, 1,'C'); 
            }
        }else{
            $pdf->Cell(80, 5, 'Procurements', 1, 0,);
            $pdf->Cell(40, 5, number_format($total_pro,2), 1, 1,'C'); 
        }                  


        $query3 = "SELECT SUM(inventory_cost_price * inventory_quantity) as cost FROM inventory_order_product WHERE date(inventory_order_date) between '$firstDate'AND '$lastDate'";
        $result3 = $Qobject->select($query3);
        $count3 = $Qobject->table_row_count($query3);
        $cost = 0;
        if($count3 > 0){
            foreach( $result3 as $row){
                $cost += $row["cost"];
                
            }
            
            $pdf->Cell(80, 5, 'Cost of Goods', 1, 0,);
            $pdf->Cell(40, 5,  number_format($cost,2), 1, 1,'C'); 
        }else{
            $pdf->Cell(80, 5, 'Cost of Goods', 1, 0,);
            $pdf->Cell(40, 5,  number_format($cost,2), 1, 1,'C'); 
        }

        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter
        $total_expen = $total_pro + $cost;
        $pdf->Cell(80, 5, 'Total Expenditure', 1, 0,);
        $pdf->Cell(40, 5, number_format($total_expen,2), 1, 1,'C');


        //Summary
        $pdf->Cell(189,10,'',0,1); //end of line
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(100, 5, 'Net Income (NGN)', 0, 0);
        $pdf->Cell(40, 5, number_format($total - $total_expen,2), 0, 1); 


        $pdf->Cell(189, 5, '[Financial Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', 'Financial Report.'.$year.'.pdf');

    }
  
    if($_POST['btn_action'] == 'Yearly Sales')
    {
        $year = $_POST['year'];
       
        $query = "SELECT inventory_price,inventory_product_id, SUM(inventory_total_price) as TptalSum, SUM(inventory_quantity) as ToalQty, products.product_name as product_name from inventory_order_product LEFT JOIN products ON products.product_id = inventory_order_product.inventory_product_id WHERE YEAR(inventory_order_date) = '$year'  GROUP BY inventory_product_id ORDER BY ToalQty DESC";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $id = 1;
        $total = 0;
        $pdf = new FPDF('p', 'mm', 'A4');

        $pdf ->AddPage();

        //set font to arial, bold, 14pt

        $pdf->SetFont('Arial','B',14);

        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

        $pdf->Cell(130, 5, 'Clover Cuties',0, 0);
        $pdf->Cell(59, 5, 'Sales Report', 0, 1); //end of line

        //Setting font 
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[No.37 Oyo Road, Opposite Providence Court, Sango Mokola Road,]', 0, 1); //end of line

        $pdf->Cell(130, 5, '[Ibadan, Oyo State, Nigeria]', 0, 1); //end of line

        $pdf->Cell(120, 5, 'Phone[+2348027111853]', 0, 1);
        $pdf->Cell(100, 5, 'Sales Report For', 0, 0); 
        $pdf->Cell(50, 5,$year, 0, 1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //invoice contents
        $pdf->SetFont('Arial','B',11.5);
        $pdf->Cell(20, 5, '#', 1, 0);
        $pdf->Cell(75, 5, 'Item', 1, 0);
        $pdf->Cell(21, 5, 'Quantity', 1, 0); 
        $pdf->Cell(24, 5, 'Price', 1, 0, 'C'); 
        $pdf->Cell(34, 5, 'Amount (NGN)', 1, 1, 'C');//end of line             
        if($count > 0)
        {
                           
            foreach ($result as $row => $inventory) {
            
                $total += $inventory["TptalSum"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                $pdf->Cell(20, 5, $id++, 1, 0,);
                $pdf->Cell(75, 5, $inventory["product_name"], 1, 0,);
                $pdf->Cell(21, 5, $inventory["ToalQty"], 1, 0,'C'); 
                $pdf->Cell(24, 5, number_format($inventory["inventory_price"],2), 1, 0,'C'); 
                $pdf->Cell(34, 5, number_format($inventory["TptalSum"],2) , 1, 1,'R');//end of line

            }

           
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            $pdf->Cell(20, 5, $id++, 1, 0,);
            $pdf->Cell(75, 5, '-', 1, 0,);
            $pdf->Cell(21, 5, '0', 1, 0,'C'); 
            $pdf->Cell(24, 5, number_format(0,2), 1, 0,'C'); 
            $pdf->Cell(34, 5, number_format(0,2) , 1, 1,'R');//end of line
        }

         //Summary
            
         $pdf->SetFont('Arial','B',14);
         $pdf->Cell(116, 5, '', 0, 0);
         $pdf->Cell(24, 5, 'Total', 0, 0); 
         
         $pdf->Cell(34, 5, number_format($total,2), 1, 1,'R');//end of line
         $pdf->SetFont('Arial','',12);

         $pdf->Cell(189, 5, '[Sales Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

         $pdf->Output('D', 'SalesReport.'.$year.'.pdf');
    
    }

    if($_POST['btn_action'] == 'Yearly Finance')
    {
        $year = $_POST['year'];

        $query = "SELECT SUM(inventory_total_price) as sales,DATE_FORMAT(inventory_order_date, '%Y, %M') as date_month FROM inventory_order_product WHERE YEAR(inventory_order_date) = $year GROUP BY Month(inventory_order_date)";
        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        $sales = 0;

        $pdf = new FPDF('p', 'mm', 'A4');

        $pdf ->AddPage();

        //set font to arial, bold, 14pt

        $pdf->SetFont('Arial','B',14);

        //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

        $pdf->Cell(130, 5, 'Clover Cuties',0, 0);
        $pdf->Cell(59, 5, 'Financial Report', 0, 1); //end of line

        //Setting font 
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(189, 5, '[No.37 Oyo Road, Opposite Providence Court, Sango Mokola Road,]', 0, 1); //end of line

        $pdf->Cell(130, 5, '[Ibadan, Oyo State, Nigeria]', 0, 1); //end of line

        $pdf->Cell(120, 5, 'Phone[+2348027111853]', 0, 1);
        $pdf->Cell(100, 5, 'Financial Report For', 0, 0); 
        $pdf->Cell(50, 5, $year, 0, 1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Income',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(80, 5, 'Item', 1, 0);

        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line                

        if($count > 0)
        {
                        
            foreach ($result as $row => $inventory) {
            
                $sales += $inventory["sales"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, $inventory['date_month'], 1, 0,);
                $pdf->Cell(40, 5, number_format($inventory["sales"],2), 1, 1,'C'); 
            }
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5, '-', 1, 0,);
            $pdf->Cell(40, 5, number_format(0,2), 1, 1,'C'); 
        }

        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter
        //Summary

        $pdf->Cell(80, 5, 'Gross Income', 1, 0,);
        $pdf->Cell(40, 5, number_format($sales,2), 1, 1,'C');
         
        $query2 = "SELECT SUM(procurement_amount) as procurement_amount, DATE_FORMAT(procurement_date, '%Y, %M') as date_month FROM procurements WHERE YEAR(procurement_date) = $year GROUP BY Month(procurement_date)";
        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $total_expen = 0;
        $total_pro = 0;
        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(189,10,'Expenditure',0,1); 
        //invoice contents
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(80, 5, 'Item', 1, 0);

        $pdf->Cell(40, 5, 'Amount (NGN)', 1, 1, 'C');//end of line  
        
        $pdf->SetFont('Arial','B',11.5);
        $pdf->Cell(80, 5, 'Monthly Procurement', 1, 0,);
        $pdf->Cell(40, 5,'Amount(NGN)', 1, 1,'C');   
        if($count2 > 0){
                                    
            foreach ($result2 as $row => $procurement) {
            
                $total_pro += $procurement["procurement_amount"];
                
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                
                $pdf->Cell(80, 5, $procurement['date_month'], 1, 0,);
                $pdf->Cell(40, 5,number_format($procurement['procurement_amount'],2), 1, 1,'C'); 
            }
        }else{
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5, '-', 1, 0,);
            $pdf->Cell(40, 5,number_format(0,2), 1, 1,'C'); 
        }

        $query3 = "SELECT SUM(inventory_cost_price * inventory_quantity) as cost , DATE_FORMAT(inventory_order_date, '%Y, %M') as date_month FROM inventory_order_product WHERE YEAR(inventory_order_date) = $year GROUP BY Month(inventory_order_date)";
        $result3 = $Qobject->select($query3);
        $count3 = $Qobject->table_row_count($query3);
        $cost = 0;
        $pdf->SetFont('Arial','B',11.5);
        $pdf->Cell(80, 5, 'Monthly Cost of Goods', 1, 0,);
        $pdf->Cell(40, 5,'Amount(NGN)', 1, 1,'C');
        if($count3 > 0){
            
            foreach( $result3 as $row){
                $cost += $row["cost"];
                $pdf->SetFont('Arial','',12);
               
                $pdf->Cell(80, 5, $row['date_month'], 1, 0,);
                $pdf->Cell(40, 5,number_format($row['cost'],2), 1, 1,'C'); 
            }
        }else{
            $pdf->SetFont('Arial','',12);
               
            $pdf->Cell(80, 5,'-', 1, 0,);
            $pdf->Cell(40, 5,number_format(0,2), 1, 1,'C'); 
        }
        

        $pdf->SetFont('Arial','B',12);

        //Numbers are right-aligned so we give 'R' after new line parameter
        $total_expen = $total_pro + $cost;
        $pdf->Cell(80, 5, 'Total Expenditure', 1, 0,);
        $pdf->Cell(40, 5, number_format($total_expen,2), 1, 1,'C');

        
        //Summary
        $pdf->Cell(189,10,'',0,1); //end of line
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(100, 5, 'Net Income (NGN)', 0, 0);
        $pdf->Cell(40, 5, number_format($sales - $total_expen,2), 0, 1); 
        

        $pdf->Cell(189, 5, '[Financial Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', 'Financial Report.'.$year.'.pdf');
        
    }

}



?>

