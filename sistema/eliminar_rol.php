<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];

    $query_validator = mysqli_query($conexion, "SELECT count(*) as usuarios_con_este_rol FROM usuario as u inner join rol as r on r.idrol = u.rol where r.idrol = $id");
    $data1 = mysqli_fetch_array($query_validator);
    $users = $data1['usuarios_con_este_rol'];

    if($users == 0 ){
        $query_delete1 = mysqli_query($conexion, "DELETE FROM rolxpermisos WHERE idRol = $id");
        $query_delete = mysqli_query($conexion, "DELETE FROM rol WHERE idrol = $id");
        mysqli_close($conexion);
        header("location: lista_roles.php");
    }else{
        header("location: lista_roles.php?id=$id");
    }

 
}
?>