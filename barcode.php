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

		$query = "SELECT * FROM products";
		$result = $Qobject->select($query);
		$count = $Qobject->table_row_count($query);
		$output = '';
		if($count > 0)
		{ 	
			foreach ($result as $row => $product)
			{
				$output .= '<p class="inline">';
				    $output .= '<span>Price: &#8358;'. number_format($product['retail_price'],2).'</span>';
					$output .= '<span style="font-size:16px; font-style:bold;">'.bar128(stripcslashes($product['product_barcode'])).'</span>';
					$output .= '<span style="font-size:16px; font-style:bold;width: 180px;display:inline-block;">'.$product['product_name'].'</span>';
				$output .= '</p>';
					
			}
		}
		echo $output;

		?>
	</div>
</body>

</html>