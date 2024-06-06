<?php
date_default_timezone_set("America/Bogota");
setlocale(LC_ALL,"es_ES");
//$hora = date("g:i:A");

include "../conexion.php";

$asignado = $_POST['asignado'];
$asunto = $_POST['asunto'];
$f_inicio = $_POST['fecha_inicio'];
$fecha_inicio      = date('Y-m-d', strtotime($f_inicio)); 
$f_fin = $_POST['fecha_fin'];
$fecha_fin         = date('Y-m-d', strtotime($f_fin)); 

$h_inicio = $_POST['hora_inicio'];
$h_fin = $_POST['hora_final'];

$ubicacion = $_POST['ubicacion'];
$mostrar_hora = $_POST['mostrar_hora'];
$descripcion = $_POST['descripcion'];
$COD_idrol = $_POST['COD_idrol'];
session_start();
$COD_id = $_SESSION['idUser'];
$COD_idoportunidad =  $_POST['COD_idoportunidad'];

$color_evento = "";

if ($mostrar_hora == "--Ninguno--") {
    $color_evento = "#1cc88a"; // Verde
} elseif ($mostrar_hora == "Ocupada") {
    $color_evento = "#e74a3b"; // Rojo
} elseif ($mostrar_hora == "Fuera de la oficina") {
    $color_evento = "#f6c23e"; // Amarillo
} elseif ($mostrar_hora == "Disponible") {
    $color_evento = "#4e73df"; // Azul
}

 
$sql = "INSERT INTO eventos1 (asignado, asunto, fecha_inicio, fecha_fin,h_inicio,h_fin, ubicacion, mostrar_hora, descripcion, COD_idrol, color_evento, COD_idusuario,COD_idSales)
        VALUES ('$asignado', '$asunto', '$fecha_inicio', '$fecha_fin', '$h_inicio', '$h_fin', '$ubicacion', '$mostrar_hora', '$descripcion', $COD_idrol, '$color_evento', '$COD_id' , '$COD_idoportunidad');";
 
 echo $sql;


$resultadoNuevoEvento = mysqli_query($conexion, $sql);

header("Location:lista_tarea.php?e=1");
 


?>