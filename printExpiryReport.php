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

    $pdf->Cell(100, 5, 'MUTTYFEM SUPERMARKET',0, 0);
    $pdf->Cell(89, 5, 'Expiry Date Report', 0, 1); //end of line
    $pdf->Cell(189, 5, ' ', 0, 1); //end of line
    //Setting font 
    $pdf->SetFont('Arial','',12);

    $pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA  ROAD]', 0, 1); //end of line
    
    $pdf->Cell(130, 5, '[OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA]', 0, 1);
    $pdf->Cell(25, 5, 'Date', 0, 0); 
    $pdf->Cell(60, 5, $Qobject->date_string(date('Y-m-d H:i:s'), 0, 1)); //end of line

    $pdf->Cell(120, 5, 'PHONE[08138331990, 08138333190]', 0, 1);
    $pdf->Cell(189, 5, ' ', 0, 1); //end of line
    
    $pdf->Cell(189, 5, ' ', 0, 1); //end of line

   
    
    

    //make a dummy empty cell as a vertical spacer
    $pdf->Cell(189,10,'',0,1); //end of line

    

    //invoice contents
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10, 5, '#', 1, 0,'C');
    $pdf->Cell(60, 5, 'Product Name', 1); 
    $pdf->Cell(20, 5, 'QTY', 1); 
    $pdf->Cell(25, 5, ' PODD', 1, 0, 'C');
    $pdf->Cell(25, 5, ' ED', 1, 0, 'C');
    $pdf->Cell(25, 5, ' E-P (days)', 1, 0, 'C');
    $pdf->Cell(24, 5, ' Day(s) to Exp', 1, 1, 'C');
    
    $query = "
        SELECT product_name, pod_product_quantity, date(pod_date) as podd, exp_date, DATEDIFF(exp_date, date(pod_date) ) as date_diff, DATEDIFF(exp_date, CURRENT_DATE) as date_to_exp FROM `proof_of_delivery_products` INNER JOIN products on products.product_id = proof_of_delivery_products.pod_rec_prod_id WHERE DATEDIFF(exp_date, CURRENT_DATE) <= 30
    ";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $id = 1;
   
    foreach ($result as $row => $product_hist) {
        $pdf->SetFont('Arial','',8);
       	
        //Numbers are right-aligned so we give 'R' after new line parameter
        $pdf->Cell(10, 5, $id++, 1, 0,'C');
        $pdf->Cell(60, 5, $product_hist["product_name"], 1, 0);
        $pdf->Cell(20, 5, $product_hist["pod_product_quantity"], 1, 0, 'C');
        $pdf->Cell(25, 5, $product_hist["podd"], 1, 0, 'C'); //end of line
        $pdf->Cell(25, 5, $product_hist["exp_date"], 1, 0);
        $pdf->Cell(25, 5, $product_hist["date_diff"], 1, 0, 'C');
        $pdf->Cell(24, 5, $product_hist["date_to_exp"], 1, 1, 'C'); //end of line
    }

    //make a dummy empty cell as a vertical spacer
    $pdf->Cell(189,10,'',0,1); //end of line

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(189, 5, 'NOTE', 0, 1); //end of line
    $pdf->Cell(189, 5, 'QTY: Quantity Purchased', 0, 1); //end of line
    $pdf->Cell(189, 5, 'PODD: Proof of delivery date', 0, 1); //end of line
    $pdf->Cell(189, 5, 'ED: Expiry Date', 0, 1); //end of line
    $pdf->Cell(189, 5, 'E-P: Difference between PODD and ED', 0, 1); //end of line
    $pdf->Cell(189, 5, 'Day to Exp: Difference between ED and Current Date', 0, 1); //end of line
   

    $pdf->Output('D', $Qobject->date_string(date('Y-m-d H:i:s'), 0, 1).'Expiry Report.pdf');
        

?>