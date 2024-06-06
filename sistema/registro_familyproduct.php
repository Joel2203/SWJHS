 <?php include_once "includes/header.php";
  include "../conexion.php";



  if (!empty($_POST)) {
    $alert = "";

      // Obtener los valores ingresados por el usuario
  $familia = $_POST['familia'];
  $marca = $_POST['marca'];
  $producto = $_POST['producto'];
  $descripcion = $_POST['descripcion'];
  $proveedor = $_POST['proveedor'];
  $contacto = $_POST['contacto'];
 

    if (empty($familia) || empty($marca) || empty($producto) || empty($descripcion)) {
      $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
    } else {

      $query_insert = mysqli_query($conexion, "INSERT INTO `family&products` (Familia, Marca, `Producto/Servicio`, DescripciÓn, Proveedor, Contacto) VALUES ('$familia', '$marca', '$producto', '$descripcion', '$proveedor', '$contacto')");
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
  ?>

 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Registro Family&Products</h1>
     <a href="lista_familyproduct.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <form action="" method="post" autocomplete="off">
         <?php echo isset($alert) ? $alert : ''; ?>
         <div class="form-group">
            <label for="familia">Familia</label>
            <input type="text" placeholder="Ingrese la familia" name="familia" id="familia" class="form-control">
          </div>

          <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" placeholder="Ingrese la marca" name="marca" id="marca" class="form-control">
          </div>

          <div class="form-group">
            <label for="producto">Producto/Servicio</label>
            <input type="text" placeholder="Ingrese nombre del producto/servicio" name="producto" id="producto" class="form-control">
          </div>

          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" placeholder="Ingrese la descripción" name="descripcion" id="descripcion" class="form-control">
          </div>

          <div class="form-group">
            <label for="proveedor">Proveedor</label>
            <input type="text" placeholder="Ingrese el proveedor" name="proveedor" id="proveedor" class="form-control">
          </div>

          <div class="form-group">
            <label for="contacto">Contacto</label>
            <input type="text" placeholder="Ingrese el contacto" name="contacto" id="contacto" class="form-control">
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