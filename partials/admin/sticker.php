<?php
require_once('../tcpdf/examples/lang/eng.php');
require_once('../tcpdf/tcpdf.php');
//require_once('tcpdf_include.php');
// create new PDF document

$prodNum = $_GET['prodNum'];

$pdf = new TCPDF("P", "mm", array(80, 30), true, 'UTF-8', false);

//set margins
$pdf->SetMargins(5, 5, 0);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(false, 0);

//set image scale factor
$pdf->setImageScale(1);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// add a page
$pdf->AddPage('L', '', false, false);

$pdf->SetAutoPageBreak(false, 0);



$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);


//$pdf->Cell(0, 0, 'CODE 128 AUTO', 0, 1);
$pdf->write1DBarcode($prodNum, 'C128', '', '', 60, 18, 0.4, $style, 'N');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('test.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
