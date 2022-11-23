<html>

<head>
	<style>
		p.inline {
			display: inline-block;
			width: 45%
		}

		span {
			font-size: 24px;
			font-weight: bold;
		}
	</style>
	<style type="text/css" media="print">
		@page {
			size: auto;
			/* auto is the initial value */
			margin: 0mm;
			/* this affects the margin in the printer settings */

		}
	</style>
</head>

<body onload="window.print();">
	<div style="margin-left: 5%">
		<?php
		include 'includes/DB.php';
		include 'includes/Query.php';
		include 'includes/barcode128.php';

		$Qobject = new Query;

		$query = "SELECT * FROM hamper_overview";
		$result = $Qobject->select($query);
		$count = $Qobject->table_row_count($query);
		$output = '';
		if($count > 0)
		{ 	
			foreach ($result as $row => $product)
			{
                $hamperId = $product['hamper_overview_id'];
               
				$output .= '<p class="inline">';
				    $output .= '<span>Price: &#8358;'. number_format($product['hamper_total_cost'],2).'</span>';
					$output .= '<span style="font-size:16px; font-style:bold;">'.bar128(stripcslashes($product['hamper_code'])).'</span>';
					$output .= '<span style="font-size:18px; font-style:bold;width: 180px;display:inline-block;">'.$product['hamper_name'].'</span>';
			

                $query2 = "SELECT * FROM hamper_items INNER JOIN products on products.product_id = hamper_items.hamper_item_product_id WHERE hamper_overview_key = $hamperId";
                $result2 = $Qobject->select($query2);
                $count2 = $Qobject->table_row_count($query2);

                if($count2 > 0){
                    foreach ($result2 as $row => $item) {
                        $output .= '<span style="font-size:16px;width: 180px;display:inline-block;">'.$item['product_name'].' X '.$item['hamper_item_quanity'].'.</span>';
                    }
                }
					
			}

          	$output .= '</p>';
		}
		echo $output;

		?>
	</div>
</body>

</html>