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
    $pdf->Cell(89, 5, 'LOW STOCK REPORT', 0, 1); //end of line
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
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(10, 5, '#', 1, 0,'C');
    $pdf->Cell(100, 5, 'Product Name', 1); 
    $pdf->Cell(79, 5, 'Recorded Level', 1, 1, 'C');
    

    
    $query = "
        SELECT * FROM products WHERE recorded_level <= 5
    ";
    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);
    $id = 1;
   
    foreach ($result as $row => $product_hist) {
        $pdf->SetFont('Arial','',10);
        
        //Numbers are right-aligned so we give 'R' after new line parameter
        $pdf->Cell(10, 5, $id++, 1, 0,'C');
        $pdf->Cell(100, 5, $product_hist["product_name"], 1, 0);
        $pdf->Cell(79, 5, $product_hist["recorded_level"], 1, 1, 'C'); //end of line
    }

    //make a dummy empty cell as a vertical spacer
    $pdf->Cell(189,10,'',0,1); //end of line

    $pdf->SetFont('Arial','B',10);
  
   

    $pdf->Output('D', $Qobject->date_string(date('Y-m-d H:i:s'), 0, 1).'Low Stock Report.pdf');
        

?>