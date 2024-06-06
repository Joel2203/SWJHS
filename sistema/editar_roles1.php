<?php
include "includes/header.php";
include "../conexion.php";
 
// Mostrar Datos

echo "CRUD<br>";
echo  $_SESSION['generales']."<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['permisos'])) {
    $seleccionados = [];
    foreach ($_POST['permisos'] as $permiso) {
      $valor = intval($permiso);
      $seleccionados[] = $valor;
    }
    sort($seleccionados);
    echo json_encode($seleccionados);
  } else {
    echo "[]";
  }
}
?>


<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 m-auto">
      <form class="" action="" method="post">
        <?php echo isset($alert) ? $alert : ''; ?>
        <input type="hidden" name="id" value="<?php echo $idusuario; ?>">
        <div class="row">
 

 
  
        <div class="col-md-4">
    <!-- Campo 2: Account -->
    <div class="form-group">
      <label for="account"><strong><u>Account</u></strong></label>
      <div>
        <input type="checkbox" name="permisos[]" value="1">
        <span>Crear Account</span>
      </div>
      <div>
        <input type="checkbox" name="permisos[]" value="2">
        <span>Listar Account</span>
      </div>
      <div>
        <input type="checkbox" name="permisos[]" value="3">
        <span>Editar Account</span>
      </div>
      <div>
        <input type="checkbox" name="permisos[]" value="4">
        <span>Eliminar Account</span>
      </div>
    </div>
  </div>
  </div>
   
  </div>
</div>
<!-- /.row -->





<!-- Campos restantes -->
<!-- Completa los campos restantes de la misma manera -->



        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Usuario</button>
      </form>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>