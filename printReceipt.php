<?php
    include 'includes/DB.php';
    include 'includes/Query.php';
    require_once '../dompdf/autoload.inc.php';
    // reference the Dompdf namespace
    use Dompdf\Dompdf;

    $Qobject = new Query;

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
      
    // (Optional) Setup the paper size and orientation
    //$dompdf->setOptions('dpi', 72);
    $dompdf->setPaper(array(0,0,204,500));
    $sqlBind = $_GET['invoiceNUmber'];
    $query = "
    SELECT * FROM inventory_overview INNER JOIN users ON inventory_overview.cashier_id = users.user_id WHERE inventory_overview_id = $sqlBind
    ";

    $result = $Qobject->select($query);
    $count = $Qobject->table_row_count($query);

    if($count > 0)
    {
                                        
        foreach ($result as $row => $invoice) {
            $html = '<html>
                <head>
                    <style>
                        * {
                            font-size: 12px;
                            font-family: Times New Roman;
                        }
                        
                        td,
                        th,
                        tr,
                        table {
                            border-top: 1px solid black;
                            border-collapse: collapse;
                        }
                        
                        td.description,
                        th.description {
                            width: 75px;
                            max-width: 75px;
                        }
                        
                        td.id,
                        th.id {
                            width: 15px;
                            max-width: 15px;
                            word-break: break-all;
                        }

                        td.quantity,
                        th.quantity {
                            width: 25px;
                            max-width: 25px;
                            word-break: break-all;
                        }

                        td.price,
                        th.price {
                            width: 40px;
                            max-width: 40px;
                            word-break: break-all;
                        }
                        
                        .centered {
                            text-align: center;
                            align-content: center;
                        }
                        
                        .ticket {
                            width: 155px;
                            max-width: 155px;
                        }
                        
                        img {
                            max-width: inherit;
                            width: inherit;
                        }
                        
                    </style>
                </head>
                <body>
                    <div class="ticket">
                       <h1 class="centered">MUTTYFEM SUPERMARKET</h1>
                       <p class="centered" style="font-size:8px;">[Address]
                            <br>1 AFIN IYANU BUS STOP MUTTYFEM PLAZA ELEYELE/ERUWA ROAD, OLOGUNERU AREA, IBADAN, OYO STATE, NIGERIA
                            <br>[IG]muttyFem_varieties
                            <br>08138333190
                        </p>
                       
                        <p class="centered"> 
                            <br>Invoice NO: '.$invoice["inventory_number"].'
                            <br>[Date]: '.$Qobject->date_string($invoice["inventory_order_created_date"]).' 
                            </p>
                        <table >
                            <thead>
                                <tr>
                                    <th style="font-size:10px;" class="quantity">QTY</th>
                                    <th class="description" style="font-size:10px;">Item</th>
                                    <th class="price" style="font-size:10px;">PRICE</th>
                                    <th class="price" style="font-size:10px;">AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>';
                            
                            $query2 = "
                            SELECT * FROM inventory_order_product WHERE inventory_overview_id =$sqlBind
                            ";

                            $result2 = $Qobject->select($query2);
                            $count2 = $Qobject->table_row_count($query2);
                            

                            foreach ($result2 as $row => $item) {
                                $html .= '<tr>
                                    <td class="quantity" style="font-size:10px;">'.$item["inventory_quantity"].'</td>
                                    <td class="description" style="font-size:10px;">'. $item["inventory_product_name"].' '.$item["inventory_product_unit"].'</td>
                                    <td class="price" style="font-size:10px;">'. $item["inventory_price"].'</td>
                                    <td class="price" style="font-size:10px;">'.number_format($item["inventory_total_price"],2).'</td>
                                </tr>
                                ';
                            }
                            $html.='<tr>
                                    <td class="id"></td>
                                    <td colspan="2"><b>TOTAL</b></td>
                                    <td class="price"><b>'.number_format($invoice["inventory_order_total"],2).'</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="centered" style="font-size:8px;">[Payment Type] '.$invoice["payment_type"].'</p>
                        <p class="centered" style="font-size:8px;">[Cashier\'s Name] '.$invoice["user_name"].'</p>                        
                        <p class="centered" style="font-size:8px;">[Invoice generated at ] '.$Qobject->date_string(date('Y-m-d H:i:s')).'</p>    
                        <p class="centered" style="font-size:8px;">ITEMS BOUGHT IN GOOD CONDITION CAN NOT BE RETURNED</p>                    
                    </div>
                </body>
            </html>
                ';

            $dompdf->loadHtml($html);
            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream('invoice'.$invoice["inventory_number"].'thermal');
        }
    }


?>