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
		
		include 'includes/barcode128.php';

        $output = '';

        if($_POST['btn_action'] == "Add")
        {
            for($count = 0; $count < count($_POST['product_name']); $count++)
            {
                $product_name = $_POST["product_name"][$count];
                $barcode = $_POST["product_barcode"][$count];
                $price = $_POST["product_price"][$count];

                $output .= '<p class="inline">';
                $output .= '<span>Price: &#8358;'. number_format($price,2).'</span>';
                $output .= '<span style="font-size:16px; font-style:bold;">'.bar128(stripcslashes($barcode)).'</span>';
                $output .= '<span style="font-size:16px; font-style:bold;width: 180px;display:inline-block;">'.$product_name.'</span>';
            $output .= '</p>';
            }
        }
	   
		
		echo $output;

		?>
	</div>
</body>

</html>
   

