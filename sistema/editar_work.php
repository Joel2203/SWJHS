<?php 
include_once "includes/header.php";
include "../conexion.php";

if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['nombre']) || empty($_POST['direccion']) || empty($_POST['telefono']) || empty($_POST['web']) || empty($_POST['num_orden']) || empty($_POST['fecha_orden']) || empty($_POST['cliente']) || empty($_POST['descripcion_trabajo']) || empty($_POST['datos_facturacion']) || empty($_POST['datos_empresa']) || empty($_POST['cantidad']) || empty($_POST['descripcion']) || empty($_POST['impuestos']) || empty($_POST['precio_unitario']) || empty($_POST['total']) || empty($_POST['observaciones']) || empty($_POST['notas_pago']) || empty($_POST['total_sin_iva']) || empty($_POST['iva']) || empty($_POST['total_con_iva'])) {
    $alert = '<p class="error">Todos los campos son requeridos</p>';
  } else {
    $idwork = $_POST['id'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $web = $_POST['web'];
    $num_orden = $_POST['num_orden'];
    $fecha_orden = $_POST['fecha_orden'];
    $cliente = $_POST['cliente'];
    $descripcion_trabajo = $_POST['descripcion_trabajo'];
    $datos_facturacion = $_POST['datos_facturacion'];
    $datos_empresa = $_POST['datos_empresa'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];
    $impuestos = $_POST['impuestos'];
    $precio_unitario = $_POST['precio_unitario'];
    $total = $_POST['total'];
    $observaciones = $_POST['observaciones'];
    $notas_pago = $_POST['notas_pago'];
    $total_sin_iva = $_POST['total_sin_iva'];
    $iva = $_POST['iva'];
    $total_con_iva = $_POST['total_con_iva'];

    $sql_update = mysqli_query($conexion, "UPDATE work SET name = '$nombre', address = '$direccion', phone = '$telefono', website = '$web', order_number = '$num_orden', order_date = '$fecha_orden', client_name = '$cliente', job_description = '$descripcion_trabajo', billing_data = '$datos_facturacion', company_data = '$datos_empresa', quantity = '$cantidad', description = '$descripcion', taxes = '$impuestos', unit_price = '$precio_unitario', total = '$total', observations = '$observaciones', payment_notes = '$notas_pago', total_amount = '$total_sin_iva', total_tax = '$iva', total_with_tax = '$total_con_iva' WHERE id = $idwork");

    if ($sql_update) {
      $alert = '<p class="exito">Orden de trabajo actualizada correctamente</p>';
    } else {
      $alert = '<p class="error">Error al actualizar la orden de trabajo</p>';
    }
  }
}

if (empty($_REQUEST['id'])) {
  header("Location: lista_co_work.php");
}

$idwork = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM work WHERE id = $idwork");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_work.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idwork = $data['id'];
    $nombre = $data['name'];
    $direccion = $data['address'];
    $telefono = $data['phone'];
    $web = $data['website'];
    $num_orden = $data['order_number'];
    $fecha_orden = $data['order_date'];
    $cliente = $data['client_name'];
    $descripcion_trabajo = $data['job_description'];
    $datos_facturacion = $data['billing_data'];
    $datos_empresa = $data['company_data'];
    $cantidad = $data['quantity'];
    $descripcion = $data['description'];
    $impuestos = $data['taxes'];
    $precio_unitario = $data['unit_price'];
    $total = $data['total'];
    $observaciones = $data['observations'];
    $notas_pago = $data['payment_notes'];
    $total_sin_iva = $data['total_amount'];
    $iva = $data['total_tax'];
    $total_con_iva = $data['total_with_tax'];
  }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 m-auto">

      <form action="" method="post">
        <?php echo isset($alert) ? $alert : ''; ?>
        <input type="hidden" name="id" value="<?php echo $idwork; ?>">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
        </div>
        <div class="form-group">
          <label for="direccion">Dirección</label>
          <input type="text" placeholder="Ingrese Dirección" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">
        </div>
        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">
        </div>
        <div class="form-group">
          <label for="web">Web</label>
          <input type="text" placeholder="Ingrese Web" name="web" class="form-control" id="web" value="<?php echo $web; ?>">
        </div>
        <div class="form-group">
          <label for="num_orden">Nº Orden</label>
          <input type="text" placeholder="Ingrese Nº Orden" name="num_orden" class="form-control" id="num_orden" value="<?php echo $num_orden; ?>">
        </div>
        <div class="form-group">
          <label for="fecha_orden">Fecha Orden</label>
          <input type="date" placeholder="Ingrese Fecha Orden" name="fecha_orden" class="form-control" id="fecha_orden" value="<?php echo $fecha_orden; ?>">
        </div>
        <div class="form-group">
          <label for="cliente">Cliente</label>
          <input type="text" placeholder="Ingrese Cliente" name="cliente" class="form-control" id="cliente" value="<?php echo $cliente; ?>">
        </div>
        <div class="form-group">
          <label for="descripcion_trabajo">Descripción del Trabajo</label>
          <textarea placeholder="Ingrese Descripción del Trabajo" name="descripcion_trabajo" class="form-control" id="descripcion_trabajo"><?php echo $descripcion_trabajo; ?></textarea>
        </div>
        <div class="form-group">
          <label for="datos_facturacion">Datos de Facturación</label>
          <textarea placeholder="Ingrese Datos de Facturación" name="datos_facturacion" class="form-control" id="datos_facturacion"><?php echo $datos_facturacion; ?></textarea>
        </div>
        <div class="form-group">
          <label for="datos_empresa">Datos Empresa</label>
          <textarea placeholder="Ingrese Datos Empresa" name="datos_empresa" class="form-control" id="datos_empresa"><?php echo $datos_empresa; ?></textarea>
        </div>
        <div class="form-group">
          <label for="cantidad">Cantidad</label>
          <input type="number" placeholder="Ingrese Cantidad" name="cantidad" class="form-control" id="cantidad" value="<?php echo $cantidad; ?>">
        </div>
        <div class="form-group">
          <label for="descripcion">Descripción</label>
          <textarea placeholder="Ingrese Descripción" name="descripcion" class="form-control" id="descripcion"><?php echo $descripcion; ?></textarea>
        </div>
        <div class="form-group">
          <label for="impuestos">Impuestos</label>
          <input type="number" placeholder="Ingrese Impuestos" name="impuestos" class="form-control" id="impuestos" value="<?php echo $impuestos; ?>">
        </div>
        <div class="form-group">
          <label for="precio_unitario">Precio Unitario</label>
          <input type="number" placeholder="Ingrese Precio Unitario" name="precio_unitario" class="form-control" id="precio_unitario" value="<?php echo $precio_unitario; ?>">
        </div>
        <div class="form-group">
          <label for="total">Total</label>
          <input type="number" placeholder="Ingrese Total" name="total" class="form-control" id="total" value="<?php echo $total; ?>">
        </div>
        <div class="form-group">
          <label for="observaciones">Observaciones</label>
          <textarea placeholder="Ingrese Observaciones" name="observaciones" class="form-control" id="observaciones"><?php echo $observaciones; ?></textarea>
        </div>
        <div class="form-group">
          <label for="notas_pago">Notas de Pago</label>
          <textarea placeholder="Ingrese Notas de Pago" name="notas_pago" class="form-control" id="notas_pago"><?php echo $notas_pago; ?></textarea>
        </div>
        <div class="form-group">
          <label for="total_sin_iva">Total Sin IVA</label>
          <input type="number" placeholder="Ingrese Total Sin IVA" name="total_sin_iva" class="form-control" id="total_sin_iva" value="<?php echo $total_sin_iva; ?>">
        </div>
        <div class="form-group">
          <label for="iva">IVA</label>
          <input type="number" placeholder="Ingrese IVA" name="iva" class="form-control" id="iva" value="<?php echo $iva; ?>">
        </div>
        <div class="form-group">
          <label for="total_con_iva">Total con IVA</label>
          <input type="number" placeholder="Ingrese Total con IVA" name="total_con_iva" class="form-control" id="total_con_iva" value="<?php echo $total_con_iva; ?>">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Orden de Trabajo</button>
      </form>
    </div>
  </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>
