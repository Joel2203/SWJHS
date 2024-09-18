<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];

    $query_select = mysqli_query($conexion, "SELECT * FROM `work` as w inner join files as f on w.id = f.COD_idWork where w.id = $id");
    $dataUrl = mysqli_fetch_assoc($query_select);
    $file = $dataUrl['url'];

    $query_deleteA = mysqli_query($conexion, "DELETE FROM files WHERE COD_idWork = $id");
    $query_delete = mysqli_query($conexion, "DELETE FROM work WHERE id = $id");
    
    mysqli_close($conexion);

    header("location: lista_co_work.php");
}
?>
