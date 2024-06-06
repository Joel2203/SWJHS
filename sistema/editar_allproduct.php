<?php
include_once "includes/header.php";
include "../conexion.php";

$idAllproduct = $_REQUEST['id'];

if (!empty($_POST)) {
  $alert = "";

  $fabricante1 = $_POST['fabricante'];
  $producto1 = $_POST['producto'];
  $nombre1 = $_POST['nombre'];
  $precioListado1 = $_POST['precioListado'];
  $idproducto1 = $_POST['idProducto'];
  $Segmento1 = $_POST['Segmento'];
  $listaPreciosPredeterminada1 = $_POST['listaPreciosPredeterminada'];
  $fechaModificacion1 = date("Y-m-d H:i:s");

  if (empty($fabricante1) || empty($producto1) || empty($nombre1) || $precioListado1 < 0) {
      $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';
  } else {
      $sql = "UPDATE allproduct SET 
                  Fabricante = '$fabricante1',
                  Producto = '$producto1',
                  Nombre = '$nombre1',
                  `Precio listado` = '$precioListado1 US$',
                  Segmento = '$Segmento1',
                  `Id. de producto` = '$idproducto1',
                  `Lista de precios predeterminada` = '$listaPreciosPredeterminada1',
                  `Fecha de modificación` = '$fechaModificacion1'
              WHERE `idAllproduct` = '$idAllproduct'";
        
      $query_update = mysqli_query($conexion, $sql);
      if ($query_update) {
          $alert = '<div class="alert alert-primary" role="alert">
              Producto actualizado
            </div>';
      } else {
          $alert = '<div class="alert alert-danger" role="alert">
              Error al actualizar el producto
            </div>';
      }
  }
}


$sql = mysqli_query($conexion, "SELECT * FROM `allproduct`  where idAllproduct = $idAllproduct");
//mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_allaccount.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idAllproduct = $data['idAllproduct'];
    $producto = $data['Producto'];
    $fechaModificacion = $data['Fecha de modificación'];
    $nombre = $data['Nombre'];
    $fabricante = $data['Fabricante'];
    $idProducto = $data['Id. de producto'];
    $precioListado = $data['Precio listado'];
    $segmento = $data['Segmento'];
    $listaPreciosPredeterminada = $data['Lista de precios predeterminada'];
}

}

?>


 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">product</h1>
     <a href="lista_product.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <form action="" method="post" autocomplete="off">
         <?php echo isset($alert) ? $alert : ''; ?>

         <div class="form-group">
            <label for="producto">Producto</label>
            <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control" value=<?php echo $producto?>>
          </div>

          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control" value=<?php echo $nombre?>>
          </div>

          <div class="form-group">
            <label for="fabricante">Fabricante</label>
            <?php
              $query_fabricante = mysqli_query($conexion, "SELECT marca FROM maker ORDER BY marca ASC");
              $resultado_fabricante = mysqli_num_rows($query_fabricante);
              //mysqli_close($conexion);
            ?>
            <select id="fabricante" name="fabricante" class="form-control">
            <option selected value="<?php echo $fabricante?>"><?php echo $fabricante?></option>
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
            <input type="text" placeholder="Ingrese ID de producto" name="idProducto" id="idProducto" class="form-control" value=<?php echo $idProducto?>>
          </div>

          <div class="form-group">
            <label for="precioListado">Precio Listado</label>
            <input type="text" placeholder="Ingrese precio listado" name="precioListado" id="precioListado" class="form-control" step="0.01" value=<?php echo $precioListado?>>
          </div>



          <div class="form-group">
            <label for="fabricante">Segmento</label>
            <?php
              $query_fabricante = mysqli_query($conexion, "SELECT marca FROM segment ORDER BY marca ASC");
              $resultado_fabricante = mysqli_num_rows($query_fabricante);
              //mysqli_close($conexion);
            ?>
            <select id="Segmento" name="Segmento" class="form-control">
            <option selected value="<?php echo $segmento?>"><?php echo $segmento?></option>
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
            <input type="text" placeholder="Ingrese lista de precios predeterminada" name="listaPreciosPredeterminada" id="listaPreciosPredeterminada" class="form-control" value=<?php echo $listaPreciosPredeterminada?>>
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