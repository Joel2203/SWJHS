<?php

include "../conexion.php";
$idproveedor = $_REQUEST['id'];
$ruc = $_POST['ruc'];
 
$sql =  "SELECT count(*) as count FROM customers WHERE RUC = '$ruc' AND idCliente <> $idproveedor";
 
$query_verificar = mysqli_query($conexion,$sql);
 
$row = mysqli_fetch_assoc($query_verificar);
$count = $row['count'];
if ($count > 0) {
    echo "0";
}else{
    echo "1";
}    

?>


 