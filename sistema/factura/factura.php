<?php
require('./fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$fecha = $_POST['fecha'];
$senores = $_POST['senores'];
$atencion = $_POST['atencion'];
$dni_ruc = $_POST['dni_ruc'];
$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$cantidad = $_POST['cantidad'];
$unidad = $_POST['unidad'];
$total = $_POST['total'];


$pdf->Image('img/FORMATO COTIZACION_page-0001.jpg', 0, 0, 210, 297);

 // ACA TRBAAJS TU
 $pdf->SetFont('Arial', 'B', 16);
 $pdf->Text(10, 10, utf8_decode('Cotización'));

 // Añadir los datos generales
 $pdf->SetFont('Arial', '', 12);
 $pdf->Text(82, 51, utf8_decode($fecha));
 $pdf->Text(82, 56, utf8_decode($senores));
 $pdf->Text(82, 62, utf8_decode($atencion));
 $pdf->Text(150, 62, utf8_decode($dni_ruc));

 $pdf->Text(180, 39, utf8_decode(rand(10,100)));

// Añadir los productos
$y = 95; // Posición inicial en el eje Y
$incremento = 7; // Incremento de 5 píxeles
$totalGeneral = 0;

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFont('Arial', '', 12);

for ($i = 0; $i < count($codigo); $i++) {
    $pdf->Text(26, $y, utf8_decode($i+1));
    $pdf->Text(38, $y, utf8_decode($codigo[$i]));
    $pdf->Text(60, $y, utf8_decode($descripcion[$i]));
    $pdf->Text(138, $y, utf8_decode($cantidad[$i]));
    $pdf->Text(150, $y, utf8_decode($unidad[$i]));
    $pdf->Text(170, $y, utf8_decode($total[$i]));
    $totalGeneral += $total[$i];
    $y += $incremento; // Incrementar la posición Y en 5 píxeles para la próxima iteración
}

$pdf->Text(172, 136.5, utf8_decode($totalGeneral));


$pdf->AddPage("L");
$pdf->Image('img/orden-de-trabajo.jpg', 0, 0, 297, 210);

 // Añadir los datos generales
 $pdf->SetFont('Arial', '', 12);
 $pdf->Text(35, 44, utf8_decode($fecha));
 $pdf->Text(35, 34, utf8_decode($senores));
 $pdf->Text(35, 39, utf8_decode($atencion));
 $pdf->Text(230, 70, utf8_decode($dni_ruc));

 $pdf->Text(258, 34, utf8_decode(rand(10,100)));

// Añadir los productos
$y = 100; // Posición inicial en el eje Y
$incremento = 5; // Incremento de 5 píxeles
$totalGeneral = 0;

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFont('Arial', '', 10);

for ($i = 0; $i < count($codigo); $i++) {
    $pdf->Text(210, $y, utf8_decode($codigo[$i]));
    $pdf->Text(40, $y, utf8_decode($descripcion[$i]));
    $pdf->Text(20, $y, utf8_decode($cantidad[$i]));
    $pdf->Text(235, $y, utf8_decode($unidad[$i]));
    $pdf->Text(260, $y, utf8_decode($total[$i]));
    $totalGeneral += $total[$i];
    $y += $incremento; // Incrementar la posición Y en 5 píxeles para la próxima iteración
}

$pdf->Text(260, 171, utf8_decode($totalGeneral));

$pdf->Output();
?>