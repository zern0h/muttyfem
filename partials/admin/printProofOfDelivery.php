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
    $pdf->Cell(59, 5, 'PROOF OF DELIVERY', 0, 1); //end of line
    $pdf->Cell(189, 5, ' ', 0, 1); //end of line
    //Setting font 
    $pdf->SetFont('Arial','',12);
    $sqlBind = $_GET['podNum'];
    
    $query = "
    SELECT * FROM proof_of_delivery  INNER JOIN suppliers on suppliers.supplier_id = proof_of_delivery.company_id INNER JOIN purchase_order_overview on purchase_order_overview.po_overview_id = proof_of_delivery.p_order_id INNER JOIN users on users.user_id = proof_of_delivery.receiving_staff_id WHERE pod_overview_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if($count > 0)
    {
                                        
        foreach ($result as $row => $purchase_order) {
        
            $status= '';
            if($purchase_order['pod_overview_status'] == 1)
            {
              $status = 'PAID';
            }
            else {
              $status = 'OUTSTANDING';
            } 	 	
            $pdf->Cell(189, 5, '[1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA  ROAD]', 0, 1); //end of line
            
            $pdf->Cell(130, 5, '[OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA]', 0, 1);
            $pdf->Cell(25, 5, 'Date', 0, 0); 
            $pdf->Cell(60, 5, $Qobject->date_string($purchase_order["po_creation_time"], 0, 1)); //end of line

            $pdf->Cell(120, 5, 'PHONE[08138331990, 08138333190]', 0, 1);
            $pdf->Cell(189, 5, ' ', 0, 1); //end of line
            $pdf->Cell(50, 5, 'PROOF OF DELIVERY #', 0, 0); 
            $pdf->Cell(34, 5, $purchase_order["pod_number"] , 0, 1); //end of line
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
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(10, 5, '#', 1, 0,'C');
            $pdf->Cell(40, 5, 'P_N0', 1);
            $pdf->Cell(75, 5, 'ITEM', 1, 0);
            $pdf->Cell(20, 5, 'UNCOST', 1, 0,);
            $pdf->Cell(20, 5, 'QTY', 1, 0,'C'); 
            $pdf->Cell(24, 5, 'TOTAL', 1, 1, 'C'); //end of line

            $query2 = "
            SELECT * FROM proof_of_delivery_products INNER JOIN products ON products.product_id = proof_of_delivery_products.pod_rec_prod_id WHERE pod_overview_number = $sqlBind
            ";

            $result2 = $Qobject->select($query2);
            $count2 = $Qobject->table_row_count($query2);
            $id = 1;
            $total_prod = 0;
            foreach ($result2 as $row => $item) {
                $pdf->SetFont('Arial','',10);
                $total_prod += $item["pod_product_quantity"];
                //Numbers are right-aligned so we give 'R' after new line parameter
                $pdf->Cell(10, 5, $id++, 1, 0,'C');
                $pdf->Cell(40, 5, $item["product_barcode"], 1, 0,);
                $pdf->Cell(75, 5, $item["product_name"], 1, 0,);
                $pdf->Cell(20, 5, $item["pod_overview_unit_cost"], 1, 0,);
                $pdf->Cell(20, 5, $item["pod_product_quantity"], 1, 0,'C'); 
                $pdf->Cell(24, 5, $item["pod_total_cost"], 1, 1,'C'); //end of line
            }

            //Summary
        
            $pdf->SetFont('Arial','B',12);
           
            $pdf->Cell(145, 5, 'TOTAL', 0, 0,'R'); 
            $pdf->Cell(20, 5,$total_prod, 1, 0,'C');
            $pdf->Cell(24, 5, number_format($purchase_order["pod_overview_total"],2), 1, 1,'R');//end of line
            
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(80, 5,'[TOTAL]', 0, 0,);
            $pdf->Cell(50, 5, number_format($purchase_order["pod_overview_total"],2), 0, 1,'L');
            $pdf->Cell(80, 5,'[TOTAL PAID]', 0, 0,);
            $pdf->Cell(50, 5,number_format($purchase_order["payment_made"],2), 0, 1,);
            $pdf->Cell(80, 5,'[OUTSTANDING PAYMENT]', 0, 0,);
            $pdf->Cell(50, 5,number_format(($purchase_order["pod_overview_total"] - $purchase_order["payment_made"]),2), 0, 1,);
            $pdf->Cell(80, 5,'[PAYMENT STATUS]', 0, 0,);
            $pdf->Cell(50, 5, $status, 0, 1,'L');
            $pdf->Cell(189, 5, '', 0, 1); //end of line
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(55, 5,'[DELIVERY RECEIVED BY]', 0, 0,);
            $pdf->Cell(50, 5,$purchase_order["user_name"], 0, 1,);
            $pdf->Cell(55, 5,'[ORDER RAISED BY]', 0, 0,);
            $pdf->Cell(50, 5,$purchase_order["po_gen_by"], 0, 1,);
            $pdf->Cell(189, 5, '', 0, 1); //end of line
            $pdf->Cell(189, 5, '[PROOF OF DELIVERY GENERATED] '.$Qobject->date_string(date('Y-m-d H:i:s')), 0, 1); //end of line

            $pdf->Output('D', $purchase_order["po_number"].'.pdf');
        }

    }

?>