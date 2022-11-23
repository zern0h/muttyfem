<?php

include 'includes/DB.php';
include 'includes/Query.php';
$Qobject = new Query;

require('../fpdf/fpdf.php');
//A4 width: 219mm
//default margin: 10mm on eachside
//writable horizontal: '219 - (10*2) = 189mm

$pdf = new FPDF('p', 'mm', 'A4');

$pdf ->AddPage();


//set font to arial, bold, 14pt

$pdf->SetFont('Arial','B',14);

//cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

$pdf->Cell(130, 5, 'MUTTYFEM SUPERMARKET',0, 0);
$pdf->Cell(59, 5, 'INVOICE', 0, 1); //end of line
$pdf->Cell(189, 5, '', 0, 1); //end of line
//Setting font 
$pdf->SetFont('Arial','',12);
$sqlBind = $_GET['invoiceNum'];

$query = "
SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE inventory_overview_id = $sqlBind
";

$result = $Qobject->select($query);
$count = $Qobject->table_row_count($query);

if($count > 0)
{
                                    
    foreach ($result as $row => $invoice) {
    
    
        $pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA ROAD,]', 0, 1); //end of line
        $pdf->Cell(130, 5, '[OLOGUNERU AREA,IBADAN, OYO STATE, NIGERIA]', 0, 1);
        $pdf->Cell(25, 5, 'Date', 0, 0); 
        $pdf->Cell(60, 5, $Qobject->date_string($invoice["inventory_order_created_date"], 0, 1)); //end of line

        $pdf->Cell(120, 5, 'PHONE[08138333190]', 0, 1);
        $pdf->Cell(25, 5, 'INVOICE #', 0, 0); 
        $pdf->Cell(34, 5, $invoice["inventory_number"] , 0, 1); //end of line


        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

    

    
        //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189,10,'',0,1); //end of line

        //invoice contents
        $pdf->SetFont('Arial','B',11.5);

        $pdf->Cell(97, 5, 'ITEM', 1, 0);
        $pdf->Cell(24, 5, 'QUANTITY', 1, 0); 
        $pdf->Cell(24, 5, 'PRICE', 1, 0, 'C'); 
        $pdf->Cell(34, 5, 'AMOUNT (NGN)', 1, 1, 'C');//end of line


        $query2 = "
        SELECT * FROM inventory_order_product INNER JOIN products ON inventory_order_product.inventory_product_id = products.product_id WHERE inventory_overview_id =$sqlBind
        ";

        $result2 = $Qobject->select($query2);
        $count2 = $Qobject->table_row_count($query2);
        $id = 1;
        foreach ($result2 as $row => $item) {
            $pdf->SetFont('Arial','',12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            $pdf->Cell(97, 5, $item["product_name"], 1, 0,);
            $pdf->Cell(24, 5, $item["inventory_quantity"], 1, 0,'C'); 
            $pdf->Cell(24, 5, number_format($item["inventory_price"],2), 1, 0,'C'); 
            $pdf->Cell(34, 5, number_format($item["inventory_total_price"],2) , 1, 1,'R');//end of line

        
        }

        //Summary
    
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(111, 5, '', 0, 0);
        $pdf->Cell(34, 5, 'TOTAL DUE', 0, 0); 
        
        $pdf->Cell(34, 5, number_format($invoice["inventory_order_total"],2), 1, 1,'R');//end of line
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(50, 5,'[PAYMENT TYPE]', 0, 0,);
        $pdf->Cell(30, 5, $invoice["payment_type"], 0, 1,'L');
        $pdf->Cell(50, 5,'[CASHIER\'S NAME]', 0, 0,);
        $pdf->Cell(50, 5,$invoice["user_name"], 0, 1,);
        $pdf->Cell(189, 5, '[INVOICE GENERATED AT] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

        $pdf->Output('D', $invoice["inventory_number"].'.pdf');
    }

}

?>