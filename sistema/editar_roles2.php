<?php
include "includes/header.php";
include "../conexion.php";
 
$idrol = $_REQUEST['id'];
$alert = '';

function encontrarIndicesConElementos($crud) {
    $indices = [];
    $arrayCrud = json_decode($crud, true);

    foreach ($arrayCrud as $indice => $subarray) {
        if (!empty($subarray)) {
            $indices[] = $indice + 1;
        }
    }

    return '[' . implode(',', $indices) . ']';
}

if(isset($_POST['Opciones'])) {
    $opciones = $_POST['Opciones'];
    $idrolpermisosarreglo = encontrarIndicesConElementos($opciones);

    $query2 = "UPDATE rolxpermisos SET crud = '$opciones', idPermisoArreglo = '$idrolpermisosarreglo' WHERE idRol =  $idrol";

    $result2 = mysqli_query($conexion, $query2);
    $alert = '<div class="alert alert-success" role="alert">Permisos actualizados correctamente</div>';
} 

$sql = mysqli_query($conexion, "SELECT * FROM `rolxpermisos`  WHERE idRol = $idrol");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: lista_roles.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id = $data['idRol'];
    $crud = $data['crud'];
    $idcrud = $data['idPermisoArreglo'];
}

mysqli_close($conexion); // Si no lo has hecho en otra parte del código.
 
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
  <a href="lista_roles.php" class="btn btn-primary">Regresar</a>
</div>

<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 m-auto">
      <form class="" action="" method="post">
        <?php echo isset($alert) ? $alert : ''; ?>
         
        <div class="row">
          <div class="form-group">
            <label for="marca">Opciones</label>
            <input type="text" placeholder="Ingrese la marca" name="Opciones" id="Opciones" class="form-control" value="<?php echo $crud; ?>">
            <span><strong>Opciones:</strong> [Opción 1: Insertar, Opción 2: Ver, Opción 3: Actualizar, Opción 4: Eliminar]</span>
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Usuario</button>
      </form>
    </div>
  </div>
</div>

</div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>