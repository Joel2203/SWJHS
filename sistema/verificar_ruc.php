<?php

include "../conexion.php";

$ruc = $_POST['ruc'];
 
$sql =  "SELECT count(*) as count FROM customers WHERE RUC = '$ruc'";
$query_verificar = mysqli_query($conexion,$sql);
 
$row = mysqli_fetch_assoc($query_verificar);
$count = $row['count'];
if ($count > 0) {
    echo "0";
}else{
    echo "1";
}    

?>


 