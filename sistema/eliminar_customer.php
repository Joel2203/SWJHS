<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM typecustomer WHERE COD_idCliente = $id");
    $query_delete2 = mysqli_query($conexion, "DELETE FROM customers WHERE idCliente = $id");
    mysqli_close($conexion);
    header("location: prueba2.php");
}
?>