<?php

require('../fpdf/fpdf.php');
include 'includes/DB.php';
include 'includes/Query.php';

$Qobject = new Query;


//A4 width: 219mm
//default margin: 10mm on eachside
//writable horizontal: '219 - (10*2) = 189mm

$pdf = new FPDF('p', 'mm', 'A4');

$pdf ->AddPage();


    //set font to arial, bold, 14pt

    $pdf->SetFont('Arial','B',14);

    //cell width,height, text content, border (0 = no border, 1 = all border), end line = 1. align [optional];

    $pdf->Cell(130, 5, 'Clover Cutties',0, 0);
    $pdf->Cell(59, 5, 'Invoice', 0, 1); //end of line

    //Setting font 
    $pdf->SetFont('Arial','',12);
    //$sqlBind = $_POST['print_code'];
        $sqlBind = 2;
        $query = "
        SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE inventory_number = $sqlBind
        ";

        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);

        if($count > 0)
        {
                                            
            foreach ($result as $row => $invoice) {
            
            
            

                /*$payment_type = $invoice["payment_type"];
                $user_name = $invoice["user_name"];*/
                $pdf->Cell(130, 5, '[Street Address]', 0, 0);
                $pdf->Cell(59, 5, 'Invoice', 0, 1); //end of line

                $pdf->Cell(130, 5, '[City, Country, Zip]', 0, 0);
                $pdf->Cell(25, 5, 'Date', 0, 0); 
                $pdf->Cell(34, 5, $invoice["inventory_order_created_date"], 0, 1); //end of line

                $pdf->Cell(130, 5, 'Phone[+2347063388058]', 0, 0);
                $pdf->Cell(25, 5, 'Invoice #', 0, 0); 
                $pdf->Cell(34, 5, $invoice["inventory_number"] , 0, 1); //end of line

                $pdf->Cell(130, 5, 'Fax[+2347063388058]', 0, 0);
                $pdf->Cell(25, 5, 'Customer ID', 0, 0); 
                $pdf->Cell(34, 5, '[12324567]', 0, 1); //end of line

                //make a dummy empty cell as a vertical spacer
                $pdf->Cell(189,10,'',0,1); //end of line

                //billing address
                $pdf->Cell(189,10,'Bill to',0,1); //end of line

                //add dummy cell at the beginning of each line for indentation
                $pdf->Cell(10, 5, '', 0, 0);
                $pdf->Cell(90, 5, '[Name]', 0, 1);

                $pdf->Cell(10, 5, '', 0, 0);
                $pdf->Cell(90, 5, '[Company Name]', 0, 1);

                $pdf->Cell(10, 5, '', 0, 0);
                $pdf->Cell(90, 5, '[Address]', 0, 1);

                $pdf->Cell(10, 5, '', 0, 0);
                $pdf->Cell(90, 5, '[Phone]', 0, 1);

                //make a dummy empty cell as a vertical spacer
                $pdf->Cell(189,10,'',0,1); //end of line

                //invoice contents
                $pdf->SetFont('Arial','B',12);

                $pdf->Cell(100, 5, 'Item', 1, 0);
                $pdf->Cell(21, 5, 'Qty', 1, 0); 
                $pdf->Cell(24, 5, 'Price', 1, 0); 
                $pdf->Cell(34, 5, 'Amount', 1, 1);//end of line


                $query2 = "
                SELECT * FROM inventory_order_product INNER JOIN products ON inventory_order_product.inventory_product_id = products.product_id WHERE inventory_overview_id =$sqlBind
                ";

                $result2 = $Qobject->select($query2);
                $count2 = $Qobject->table_row_count($query2);
                $id = 1;
                foreach ($result2 as $row => $item) {
                    $pdf->SetFont('Arial','',12);

                    //Numbers are right-aligned so we give 'R' after new line parameter
                    $pdf->Cell(100, 5, $item["product_name"], 1, 0,'R');
                    $pdf->Cell(21, 5, $item["inventory_quantity"], 1, 0,'R'); 
                    $pdf->Cell(24, 5, number_format($item["inventory_price"],2), 1, 0,'R'); 
                    $pdf->Cell(34, 5, number_format($item["inventory_total_price"],2) , 1, 1,'R');//end of line

                
                }

                //Summary
                $pdf->Cell(130, 5, '', 0, 0);
                $pdf->Cell(25, 5, 'Subtotal', 0, 0); 
                $pdf->Cell(4, 5, '$', 1, 0); 
                $pdf->Cell(30, 5, '6,750', 1, 1,'R');//end of line

                $pdf->Cell(130, 5, '', 0, 0);
                $pdf->Cell(25, 5, 'Taxable', 0, 0); 
                $pdf->Cell(4, 5, '$', 1, 0); 
                $pdf->Cell(30, 5, 'A', 1, 1,'R');//end of line

                $pdf->Cell(130, 5, '', 0, 0);
                $pdf->Cell(25, 5, 'Tax rate', 0, 0); 
                $pdf->Cell(4, 5, '', 1, 0); 
                $pdf->Cell(30, 5, '10%', 1, 1,'R');//end of line

                $pdf->Cell(130, 5, '', 0, 0);
                $pdf->Cell(25, 5, 'Total Due', 0, 0); 
                $pdf->Cell(4, 5, '', 1, 0); 
                $pdf->Cell(30, 5, number_format($invoice["inventory_order_total"],2), 1, 1,'R');//end of line

                $pdf->Output('D', $invoice["inventory_number"].'.pdf');
            }

        }
    



   
?>