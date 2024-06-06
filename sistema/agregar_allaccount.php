<?php
 
include_once "includes/header.php";
include "../conexion.php";

// Agregar Productos a entrada
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['cuenta']) || empty($_POST['direccion']) || empty($_POST['ciudad']) || empty($_POST['contacto_principal']) || empty($_POST['secot']) || empty($_POST['propietario']) || empty($_POST['origen_cliente'])) {
    $alert = '<div class="alert alert-danger" role="alert">
    Todo los campos son obligatorios
  </div>';
  } else {
      $cuenta = $_POST['cuenta'];
      $direccion = $_POST['direccion'];
      $ciudad = $_POST['ciudad'];
      $contacto_principal = $_POST['contacto_principal'];
      $secot = $_POST['secot'];
      $propietario = $_POST['propietario'];
      $origen_cliente = $_POST['origen_cliente'];

      $sql = "INSERT INTO account (cuenta, direccion, ciudad, contacto_principal, secot, propietario, origen_cliente) 
      VALUES ('$cuenta', '$direccion', '$ciudad', '$contacto_principal', '$secot', '$propietario', '$origen_cliente')";

      $query_insert = mysqli_query($conexion, $sql);
      if ($query_insert) {
        $alert = '<div class="alert alert-primary" role="alert">
                Account Registrado
              </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar una Account
              </div>';
      }
  }
}

?>

<script src="js/jquery.min.js"></script>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <form action="" method="post">
                <?php echo isset($alert) ? $alert : ''; ?>

                <th>
                  <div class="form-group">
                    <label for="cuenta">Cuenta</label>
                    <input type="text" name="cuenta" id="cuenta" placeholder="Ingrese cuenta" class="form-control">
                  </div>
                </th>
                <th>
                  <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" placeholder="Ingrese dirección" class="form-control">
                  </div>
                </th>
                <th>
                  <div class="form-group">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad" placeholder="Ingrese ciudad" class="form-control">
                  </div>
                </th>
                <th>
                  <div class="form-group">
                    <label for="contacto_principal">Contacto Principal</label>
                    <input type="text" name="contacto_principal" id="contacto_principal" placeholder="Ingrese contacto principal" class="form-control">
                  </div>
                </th>
                <th>
                  <div class="form-group">
                    <label for="secot">Secot</label>
                    <input type="text" name="secot" id="secot" placeholder="Ingrese secot" class="form-control">
                  </div>
                </th>
                <th>
                  <div class="form-group">
                    <label for="propietario">Propietario</label>
                    <input type="text" name="propietario" id="propietario" placeholder="Ingrese propietario" class="form-control">
                  </div>
                </th>
                <th>
                  <div class="form-group">
                    <label for="origen_cliente">Origen del Cliente</label>
                    <input type="text" name="origen_cliente" id="origen_cliente" placeholder="Ingrese origen del cliente" class="form-control">
                  </div>
                </th>
                 
                <input type="submit" value="Registrar Account" class="btn btn-primary">
                <a href="lista_allaccount.php" class="btn btn-danger">Regresar</a>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>

<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>