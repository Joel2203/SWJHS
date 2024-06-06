<?php
include_once "includes/header.php";
include "../conexion.php";

if (!empty($_POST)) {
    $alert = "";

    $fabricante = $_POST['fabricante'];
    $producto = $_POST['producto'];
    $nombre = $_POST['nombre'];
    $precioListado = $_POST['precioListado'];
    $idproducto = $_POST['idProducto'];
    $Segmento = $_POST['Segmento'];
    $listaPreciosPredeterminada = $_POST['listaPreciosPredeterminada'];
    $fechaModificacion = date("Y-m-d H:i:s");
    /*
    echo "Producto: " . $producto . "<br>";
    echo "Nombre: " . $nombre . "<br>";
    echo "Fabricante: " . $fabricante . "<br>";
    echo "Id producto: " . $idproducto . "<br>";
    echo "Precio Listado: " . $precioListado . "<br>";
    echo "Segmento: " . $Segmento . "<br>";
    echo "Lista de precio determinada: " . $listaPreciosPredeterminada . "<br>";
    */

    if (empty($fabricante) || empty($producto) || empty($nombre) || $precioListado < 0) {
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
}
?>


 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
     <a href="lista_product.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <form action="" method="post" autocomplete="off">
         <?php echo isset($alert) ? $alert : ''; ?>

         <div class="form-group">
            <label for="producto">Producto</label>
            <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
          </div>

          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control">
          </div>

          <div class="form-group">
            <label for="fabricante">Fabricante</label>
            <?php
              $query_fabricante = mysqli_query($conexion, "SELECT marca FROM maker ORDER BY marca ASC");
              $resultado_fabricante = mysqli_num_rows($query_fabricante);
              //mysqli_close($conexion);
            ?>
            <select id="fabricante" name="fabricante" class="form-control">
              <?php
                if ($resultado_fabricante > 0) {
                  while ($fabricante = mysqli_fetch_array($query_fabricante)) {
              ?>
                    <option value="<?php echo $fabricante['marca']; ?>"><?php echo $fabricante['marca']; ?></option>
              <?php
                  }
                }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="idProducto">ID de Producto</label>
            <input type="text" placeholder="Ingrese ID de producto" name="idProducto" id="idProducto" class="form-control">
          </div>

          <div class="form-group">
            <label for="precioListado">Precio Listado</label>
            <input type="text" placeholder="Ingrese precio listado" name="precioListado" id="precioListado" class="form-control" step="0.01" >
          </div>

          <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <input type="text" placeholder="Ingrese observaciones" name="observaciones" id="observaciones" class="form-control">
        </div>






          <div class="form-group">
            <label for="fabricante">Segmento</label>
            <?php
              $query_fabricante = mysqli_query($conexion, "SELECT marca FROM segment ORDER BY marca ASC");
              $resultado_fabricante = mysqli_num_rows($query_fabricante);
              //mysqli_close($conexion);
            ?>
            <select id="Segmento" name="Segmento" class="form-control">
              <?php
                if ($resultado_fabricante > 0) {
                  while ($fabricante = mysqli_fetch_array($query_fabricante)) {
              ?>
                    <option value="<?php echo $fabricante['marca']; ?>"><?php echo $fabricante['marca']; ?></option>
              <?php
                  }
                }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="listaPreciosPredeterminada">Lista de Precios Predeterminada</label>
            <input type="text" placeholder="Ingrese lista de precios predeterminada" name="listaPreciosPredeterminada" id="listaPreciosPredeterminada" class="form-control">
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