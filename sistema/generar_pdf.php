<?php
require('./orden/fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
 
$total = '';

$pdf->Image('./orden/img/orden-de-trabajo.PNG', 0, 0, 205, 150);

$pdf->SetFont('Arial', '', 9);
$pdf->Text(23, 25.8, utf8_decode($total));



$pdf->Output();
?>