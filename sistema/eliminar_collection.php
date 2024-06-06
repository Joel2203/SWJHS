<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];

    $query_select = mysqli_query($conexion, "SELECT * FROM `collections` as c inner join files as f on c.idCollections = f.COD_idCollections where c.idCollections = $id");
    $dataUrl = mysqli_fetch_assoc($query_select);
    $file = $dataUrl['url'];

    $query_deleteA = mysqli_query($conexion, "DELETE FROM files WHERE COD_idCollections = $id");
    $query_delete = mysqli_query($conexion, "DELETE FROM collections WHERE idCollections = $id");
    
    mysqli_close($conexion);

    header("location: lista_collection.php");
}
?>