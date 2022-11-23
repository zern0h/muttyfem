<?php

include 'includes/DB.php';
include 'includes/Query.php';
$Qobject = new Query;

require('../fpdf/fpdf.php');
//A4 width: 219mm
//default margin: 10mm on eachside
//writable horizontal: '219 - (10*2) = 189mm


$firstDate  =  $_GET['firstDate'];
$lastDate  =  $_GET['lastDate']; 
$year = $firstDate.' to '.$lastDate;


$query = "SELECT inventory_price,inventory_product_id, SUM(inventory_total_price) as TptalSum, SUM(inventory_quantity) as ToalQty, products.product_name as product_name,products.product_barcode as barcode from inventory_order_product LEFT JOIN products ON products.product_id = inventory_order_product.inventory_product_id WHERE date(inventory_order_date) between '$firstDate'AND '$lastDate'  GROUP BY inventory_product_id ORDER BY ToalQty DESC"; 


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

$pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA  ROAD]', 0, 1); //end of line
    
$pdf->Cell(130, 5, '[OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA]', 0, 1);

$pdf->Cell(120, 5, 'Phone[08138331990, 08138333190]', 0, 1);
$pdf->Cell(100, 5, 'Sales Report For', 0, 0); 
$pdf->Cell(50, 5,$year, 0, 1); //end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189,10,'',0,1); //end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189,10,'',0,1); //end of line

//invoice contents
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10, 5, '#', 1, 0);
$pdf->Cell(30,5,'Barcode',1,0);
$pdf->Cell(65, 5, 'Item', 1, 0);
$pdf->Cell(21, 5, 'Quantity', 1, 0); 
$pdf->Cell(24, 5, 'Price', 1, 0, 'C'); 
$pdf->Cell(34, 5, 'Amount (NGN)', 1, 1, 'C');//end of line         
if($count > 0)
{
                        
    foreach ($result as $row => $inventory) {
    
        $total += $inventory["TptalSum"];
        
        $pdf->SetFont('Arial','',9);

        //Numbers are right-aligned so we give 'R' after new line parameter
        $pdf->Cell(10, 5, $id++, 1, 0,);
        $pdf->Cell(30, 5, $inventory["barcode"], 1, 0,);
        $pdf->Cell(65, 5, $inventory["product_name"], 1, 0,);
        $pdf->Cell(21, 5, $inventory["ToalQty"], 1, 0,'C'); 
        $pdf->Cell(24, 5, number_format($inventory["inventory_price"],2), 1, 0,'C'); 
        $pdf->Cell(34, 5, number_format($inventory["TptalSum"],2) , 1, 1,'R');//end of line

    }

}else{
    $pdf->SetFont('Arial','',12);

    //Numbers are right-aligned so we give 'R' after new line parameter
    $pdf->Cell(10, 5, $id++, 1, 0,);
    $pdf->Cell(30, 5, $id++, 1, 0,);
    $pdf->Cell(65, 5, '-', 1, 0,'C');
    $pdf->Cell(21, 5, '0', 1, 0,'C'); 
    $pdf->Cell(24, 5, number_format(0,2), 1, 0,'C'); 
    $pdf->Cell(34, 5, number_format(0,2) , 1, 1,'R');//end of line
}

//Summary

$pdf->SetFont('Arial','B',14);
$pdf->Cell(115, 5, '', 0, 0);
$pdf->Cell(24, 5, 'Total', 0, 0); 

$pdf->Cell(45, 5, number_format($total,2), 1, 1,'R');//end of line
$pdf->SetFont('Arial','',12);

$pdf->Cell(189, 5, '[Sales Reports generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

$pdf->Output('D', 'SalesReport.'.$year.'.pdf');

?>