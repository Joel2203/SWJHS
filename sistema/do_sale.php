<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_update = mysqli_query($conexion, "UPDATE sale SET Typesale = 1 WHERE id = '$id'");
    mysqli_close($conexion);
    header("location: prueba2.php");
}
?>
