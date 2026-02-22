<?php
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;

require('fpdf/fpdf.php');
    //A4 width: 219mm
    //default margin: 10mm on eachside
    //writable horizontal: '219 - (10*2) = 189mm

    $pdf = new FPDF('p', 'mm', 'A4');

    $pdf ->AddPage();

    //set font to arial, bold, 14pt

    $pdf->SetFont('Arial','B',14);

    //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

    $pdf->Cell(130, 5, 'MUTTYFEM SUPERMARKET',0, 0);
    $pdf->Cell(59, 5, 'ARTICLE DOC LIST', 0, 1); //end of line
    $pdf->Cell(189, 5, ' ', 0, 1); //end of line
    //Setting font 
    $pdf->SetFont('Arial','',12);
    $sqlBind = $_GET['productNumber'];
    
    $query = "
        SELECT * FROM `product_history` INNER JOIN products on products.product_id = product_history.prod_hist_prod_id INNER JOIN users on users.user_id = product_history.action_by_id WHERE prod_hist_prod_id = '$sqlBind' LIMIT 1
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if($count > 0)
    {
                                        
        foreach ($result as $row => $transaction) {
        
          
            $pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA  ROAD]', 0, 1); //end of line
            
            $pdf->Cell(130, 5, '[OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA]', 0, 1);
            $pdf->Cell(25, 5, 'Date', 0, 0); 
            $pdf->Cell(60, 5, $Qobject->date_string(date('Y-m-d H:i:s'), 0, 1)); //end of line

            $pdf->Cell(120, 5, 'PHONE[08138331990, 08138333190]', 0, 1);
            $pdf->Cell(189, 5, ' ', 0, 1); //end of line
           
            $pdf->Cell(189, 5, ' ', 0, 1); //end of line

            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(59, 5, ' PRODUCT NAME',0, 0);
            $pdf->Cell(130, 5, $transaction["product_name"], 0, 1); //end of line
            $pdf->SetFont('Arial','B',10);  
            $pdf->Cell(40, 5, 'PRODUCT CODE',0, 0);
            $pdf->Cell(39, 5, $transaction["product_barcode"], 0, 0); 
            $pdf->Cell(60, 5, "MANUFACTURER'/S BARCODE",0, 0);
            $pdf->Cell(48, 5, $transaction["manufacturer_barcode"], 0, 1);//end of line
           
           

            //make a dummy empty cell as a vertical spacer
            $pdf->Cell(189,10,'',0,1); //end of line

           

            //invoice contents
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(10, 5, '#', 1, 0,'C');
            $pdf->Cell(30, 5, 'ACTION', 1); 
            $pdf->Cell(10, 5, 'FL', 1, 0);
            $pdf->Cell(10, 5, 'CL', 1, 0);
            $pdf->Cell(80, 5, 'AB', 1, 0,'C'); 
            $pdf->Cell(49, 5, 'DAO', 1, 1, 'C'); //end of line

           
            $query2 = "
                SELECT * FROM `product_history` INNER JOIN products on products.product_id = product_history.prod_hist_prod_id INNER JOIN users on users.user_id = product_history.action_by_id WHERE prod_hist_prod_id = '$sqlBind' 
            ";
            $result2 = $Qobject->select($query2);
            $count2 = $Qobject->table_row_count($query2);
            $id = 1;
            $total_prod = 0;
            foreach ($result2 as $row => $product_hist) {
                $pdf->SetFont('Arial','',10);
                
                //Numbers are right-aligned so we give 'R' after new line parameter
                $pdf->Cell(10, 5, $id++, 1, 0,'C');
                $pdf->Cell(30, 5, $product_hist["hist_action"], 1, 0);
                $pdf->Cell(10, 5, $product_hist["former_level"], 1, 0);
                $pdf->Cell(10, 5, $product_hist["current_level"], 1, 0);
                $pdf->Cell(80, 5, $product_hist["user_name"], 1, 0,'C'); 
                $pdf->Cell(49, 5,$Qobject->date_string ($product_hist["prod_hist_date"]), 1, 1,'C'); //end of line
            }

            //make a dummy empty cell as a vertical spacer
            $pdf->Cell(189,10,'',0,1); //end of line

            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(189, 5, 'NOTE', 0, 1); //end of line
            $pdf->Cell(189, 5, 'FL: Former Level', 0, 1); //end of line
            $pdf->Cell(189, 5, 'CL: Current Level', 0, 1); //end of line
            $pdf->Cell(189, 5, 'AB: Action Carried Out By', 0, 1); //end of line
            $pdf->Cell(189, 5, 'Date Action Occured', 0, 1); //end of line
            




            $pdf->Output('D', 'Article Doc List.pdf');
        }

    }

?>