<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    
    if (!empty($_POST['Rol']) && !empty($_POST['permisos'])) {
        $rol = $_POST['Rol'];
        $permisosSeleccionados = $_POST['permisos'];

        
        $query = mysqli_query($conexion, "SELECT * FROM `rol` WHERE rol = '$rol'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                        Ese rol ya existe
                    </div>';
        }else{

            // Llenar posiciones
            function llenarPosiciones($arreglo) {
                $result = [];
                for ($i = 1; $i <= 9; $i++) {
                    $result[] = in_array($i, $arreglo) ? [1, 2, 3, 4] : [];
                }
            
                return $result;
            }
            
            $resultado1 = llenarPosiciones($permisosSeleccionados);
            $resultado2 = json_encode($resultado1); 

            // Almacenar los IDs de permisos seleccionados en un array
            $permisosArray = array_map('intval', $permisosSeleccionados);
            $permisosJson = json_encode($permisosArray);

            // Insertar el rol en la base de datos
            $query_insert = mysqli_query($conexion, "INSERT INTO rol (rol) VALUES ('$rol')");
            $id_rol = mysqli_insert_id($conexion);

            // Insertar permisos del rol en la base de datos
            $query_insert_permiso = mysqli_query($conexion, "INSERT INTO rolxpermisos (idRol, idPermisoArreglo,crud) VALUES ('$id_rol', '$permisosJson', '$resultado2')");
            
            if (!$query_insert_permiso) {
                echo "Error al insertar el permiso: " . mysqli_error($conexion);
            }
        }    
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_roles.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Content Row -->
    <div class="row">
    <div class="col-lg-6 m-auto">
        <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <?php echo isset($alert) ? $alert : ''; ?>
            <div class="form-group">
                <label for="Rol">Rol</label>
                <input type="text" placeholder="Ingrese Rol" name="Rol" id="Rol" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="recurrente">Permisos</label>
                
                <?php
                $query_customer = mysqli_query($conexion, "SELECT * FROM permisos");
                $resultado_customer = mysqli_num_rows($query_customer);
                //mysqli_close($conexion);

                while ($permiso = mysqli_fetch_assoc($query_customer)) {
                    $permisoId = $permiso['id'];
                    $permisoNombre = $permiso['permiso'];
                  
                    echo '<div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permisos[]" id="permiso'.$permisoId.'" value="'.$permisoId.'">
                            <label class="form-check-label" for="permiso'.$permisoId.'">
                              '.$permisoNombre.'
                            </label>
                          </div>';
                  }
                ?>     
            </div>
             
            <input type="submit" value="Guardar Cliente" class="btn btn-primary">
        </form>
    </div>
</div>



</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>

 