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
    $pdf->Cell(59, 5, 'PURCHASE ORDER', 0, 1); //end of line
    $pdf->Cell(189, 5, ' ', 0, 1); //end of line
    //Setting font 
    $pdf->SetFont('Arial','',12);
    $sqlBind = $_GET['orderNum'];
    
    $query = "
    SELECT * FROM purchase_order_overview INNER JOIN users ON users.user_id = purchase_order_overview.po_created_by INNER JOIN suppliers on suppliers.supplier_id = purchase_order_overview.po_company WHERE po_overview_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if($count > 0)
    {
                                        
        foreach ($result as $row => $purchase_order) {
        
            $status= '';
            if($purchase_order['po_overview_status'] == 1)
            {
              $status = 'CLOSED';
            }
            else {
              $status = 'OPEN';
            }
            $pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA  ROAD]', 0, 1); //end of line
            
            $pdf->Cell(130, 5, '[OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA]', 0, 1);
            $pdf->Cell(25, 5, 'Date', 0, 0); 
            $pdf->Cell(60, 5, $Qobject->date_string($purchase_order["po_creation_time"], 0, 1)); //end of line

            $pdf->Cell(120, 5, 'PHONE[08138331990, 08138333190]', 0, 1);
            $pdf->Cell(189, 5, ' ', 0, 1); //end of line
            $pdf->Cell(50, 5, 'PURCHASE ORDER #', 0, 0); 
            $pdf->Cell(34, 5, $purchase_order["po_number"] , 0, 1); //end of line
            $pdf->Cell(189, 5, ' ', 0, 1); //end of line

            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(59, 5, 'SUPPLIER DETAILS',0, 0);
            $pdf->Cell(130, 5, $purchase_order["supplier_name"], 0, 1); //end of line
            $pdf->SetFont('Arial','B',10);  
            $pdf->Cell(70, 5, 'SUPPLIER NUMBER',0, 0);
            $pdf->Cell(110, 5, $purchase_order["supplier_code"], 0, 1); //end of line
           
            $pdf->Cell(59, 5, 'PHONE NUMBER',0, 0);
            $pdf->Cell(130, 5, $purchase_order["phone_number1"] .' '.$purchase_order["phone_number2"], 0, 1); //end of line
            $pdf->Cell(59, 5, 'EMAIL',0, 0);
            $pdf->Cell(130, 5, $purchase_order["supplier_email"], 0, 1); //end of line
            $pdf->Cell(59, 5, 'ADDRESS',0, 1);
            $pdf->Cell(189, 5, $purchase_order["supplier_address1"], 0, 1);
            $pdf->Cell(189, 5, $purchase_order["supplier_address2"], 0, 1);
            $pdf->Cell(189, 5, $purchase_order["supplier_city"].', '.strtoupper($purchase_order["supplier_state"]) .' STATE', 0, 1);
            

            //make a dummy empty cell as a vertical spacer
            $pdf->Cell(189,10,'',0,1); //end of line

           

            //invoice contents
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(15, 5, '#', 1, 0,'C');
            $pdf->Cell(38, 5, 'P_N0', 1);
            $pdf->Cell(102, 5, 'ITEM', 1, 0);
            $pdf->Cell(25, 5, 'QTY', 1, 1,'C'); 
         

            ;//end of line

            $query2 = "
            SELECT * FROM purchase_order_products INNER JOIN products ON products.product_id = purchase_order_products.product_ordered_id INNER JOIN purchase_order_overview ON purchase_order_overview.po_overview_id = purchase_order_products.po_overview_number  WHERE po_overview_number = $sqlBind
            ";

            $result2 = $Qobject->select($query2);
            $count2 = $Qobject->table_row_count($query2);
            $id = 1;
            foreach ($result2 as $row => $item) {
                $pdf->SetFont('Arial','',12);

                //Numbers are right-aligned so we give 'R' after new line parameter
                $pdf->Cell(15, 5, $id++, 1, 0,'C');
                $pdf->Cell(38, 5, $item["product_barcode"], 1, 0,);
                $pdf->Cell(102, 5, $item["product_name"], 1, 0,);
                $pdf->Cell(25, 5, $item["po_product_qty"], 1, 1,'C');  //end of line
            }

            //Summary
        
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(112, 5, '', 0, 0);
            $pdf->Cell(34, 5, 'TOTAL', 0, 0); 
            $pdf->Cell(34, 5, number_format($purchase_order["po_overview_total"],2), 1, 1,'R');//end of line
            
            
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(50, 5,'[STATUS]', 0, 0,);
            $pdf->Cell(30, 5, $status, 0, 1,'L');
            $pdf->Cell(50, 5,'[STAFF\'S NAME]', 0, 0,);
            $pdf->Cell(50, 5,$purchase_order["user_name"], 0, 1,);
            $pdf->Cell(189, 5, '[PURCHASE ORDER PRINTED AT] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

            $pdf->Output('D', $purchase_order["po_number"].'.pdf');
        }

    }

?>