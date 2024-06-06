<?php include_once "includes/header.php";
  include "../conexion.php";



  if (!empty($_POST)) {
    $alert = "";
    $idproveedor = $_REQUEST['id'];
      // Obtener los valores ingresados por el usuario
    $familia = $_POST['familia'];
    $marca = $_POST['marca'];
    $producto = $_POST['producto'];
    $descripcion = $_POST['descripcion'];
    $proveedor = $_POST['proveedor'];
    $contacto = $_POST['contacto'];
 

    if (empty($familia) || empty($marca) || empty($producto) ) {
      $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
    } else {

      $query_insert = mysqli_query($conexion, "UPDATE `family&products` SET Marca = '$marca', `Producto/Servicio` = '$producto', Descripción = '$descripcion', Proveedor = '$proveedor', Contacto = '$contacto',Familia = '$familia' WHERE id=$idproveedor");
      if ($query_insert) {
        $alert = '<div class="alert alert-primary" role="alert">
                Family&Product Registrado
              </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
      }
    }
  }

  $idfp = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM `family&products` WHERE id = $idfp");
//mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_familyproduct.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $id = $data['id'];
    $familia = $data['Familia'];
    $marca = $data['Marca'];
    $producto = $data['Producto/Servicio'];
    $descripcion = $data['Descripción'];
    $proveedor = $data['Proveedor'];
    $contacto = $data['Contacto'];
  }
}

  ?>

 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Actualizar Family&Products</h1>
     <a href="lista_familyproduct.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <form action="" method="post" autocomplete="off">
         <?php echo isset($alert) ? $alert : ''; ?>
            <div class="form-group">
            <label for="familia">Familia</label>
            <input type="text" placeholder="Ingrese la familia" name="familia" id="familia" class="form-control" value="<?php echo $familia; ?>">
            </div>

            <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" placeholder="Ingrese la marca" name="marca" id="marca" class="form-control" value="<?php echo $marca; ?>">
            </div>

            <div class="form-group">
            <label for="producto">Producto/Servicio</label>
            <input type="text" placeholder="Ingrese nombre del producto/servicio" name="producto" id="producto" class="form-control" value="<?php echo $producto; ?>">
            </div>

            <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" placeholder="Ingrese la descripción" name="descripcion" id="descripcion" class="form-control" value="<?php echo $descripcion; ?>">
            </div>

            <div class="form-group">
            <label for="proveedor">Proveedor</label>
            <input type="text" placeholder="Ingrese el proveedor" name="proveedor" id="proveedor" class="form-control" value="<?php echo $proveedor; ?>">
            </div>

            <div class="form-group">
            <label for="contacto">Contacto</label>
            <input type="text" placeholder="Ingrese el contacto" name="contacto" id="contacto" class="form-control" value="<?php echo $contacto; ?>">
            </div>


         <input type="submit" value="Guardar Family&Product" class="btn btn-primary">
       </form>
     </div>
   </div>


 </div>
 <!-- /.container-fluid -->

 </div>
 <!-- End of Main Content -->
 <?php include_once "includes/footer.php"; ?>