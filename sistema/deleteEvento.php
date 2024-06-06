<?php
include "../conexion.php";
$id    		= $_REQUEST['id']; 

$sqlDeleteEvento = ("DELETE FROM eventos1 WHERE  id='" .$id. "'");
$resultProd = mysqli_query($conexion, $sqlDeleteEvento);

?>
  