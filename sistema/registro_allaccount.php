<?php
include_once "includes/header.php";
include "../conexion.php";

if (!empty($_POST)) {
    $alert = "";

    $cuenta = $_POST['cuenta'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $contacto = $_POST['contacto'];
    $secot = $_POST['secot'];
    $propietario = $_POST['propietario'];
    $origen = $_POST['origen'];

    // Mostrar los valores capturados
    echo "Cuenta: " . $cuenta . "<br>";
    echo "Dirección: " . $direccion . "<br>";
    echo "Ciudad: " . $ciudad . "<br>";
    echo "Contacto principal: " . $contacto . "<br>";
    echo "Secot: " . $secot . "<br>";
    echo "Propietario: " . $propietario . "<br>";
    echo "Origen cliente: " . $origen . "<br>";
 

    /*
    if (empty($cuenta) || empty($direccion) || empty($ciudad)) {
        $alert = '<div class="alert alert-danger" role="alert">
                  Todos los campos son obligatorios
                </div>';
    } else {
        $sql = "INSERT INTO allproduct (Fabricante, Producto, Nombre, `Precio listado`, `Id. de producto`, Segmento, `Lista de precios predeterminada`,`Fecha de modificación`) 
                VALUES ('$fabricante', '$producto', '$nombre', '$precioListado US$', '$idproducto', '$Segmento', '$listaPreciosPredeterminada','$fechaModificacion')";

        $query_insert = mysqli_query($conexion, $sql);
        if ($query_insert) {
            $alert = '<div class="alert alert-primary" role="alert">
                Producto Registrado
              </div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
        }
    }
    */
}
?>


 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
     <a href="lista_allaccount.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <form action="" method="post" autocomplete="off">
         <?php echo isset($alert) ? $alert : ''; ?>

         <div class="form-group">
            <label for="cuenta">Cuenta</label>
            <input type="text" placeholder="Ingrese el nombre de la cuenta" name="cuenta" id="cuenta" class="form-control">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" placeholder="Ingrese la dirección" name="direccion" id="direccion" class="form-control">
        </div>

        <div class="form-group">
            <label for="ciudad">Ciudad</label>
            <input type="text" placeholder="Ingrese la ciudad" name="ciudad" id="ciudad" class="form-control">
        </div>

        <div class="form-group">
            <label for="contacto">Contacto principal</label>
            <input type="text" placeholder="Ingrese el contacto principal" name="contacto" id="contacto" class="form-control">
        </div>

        <div class="form-group">
            <label for="secot">Secot</label>
            <input type="text" placeholder="Ingrese Secot" name="secot" id="secot" class="form-control">
        </div>

        <div class="form-group">
            <label for="propietario">Propietario</label>
            <input type="text" placeholder="Ingrese el propietario" name="propietario" id="propietario" class="form-control">
        </div>

        <div class="form-group">
            <label for="origen">Origen cliente</label>
            <input type="text" placeholder="Ingrese el origen del cliente" name="origen" id="origen" class="form-control">
        </div>

         <input type="submit" value="Guardar Producto" class="btn btn-primary">
       </form>
     </div>
   </div>


 </div>
 <!-- /.container-fluid -->

 </div>
 <!-- End of Main Content -->
 <?php include_once "includes/footer.php"; ?>