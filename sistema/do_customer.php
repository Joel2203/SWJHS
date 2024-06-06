<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_update = mysqli_query($conexion, "UPDATE typecustomer SET type = 2 WHERE COD_idCliente = '$id'");
    mysqli_close($conexion);
    header("location: prueba1.php");
}
?>
