<?php include_once "includes/header.php";
include "../conexion.php";


if (!empty($_POST)) {
    $alert = "";
      $idcollection = $_REQUEST['id'];
      $numeroFactura = $_POST['NumeroFactura'];
      $fechaEmision = $_POST['fechaEmision'];
      $fechaVencimiento = $_POST['fechaVencimiento'];
      $monto = $_POST['monto'];
      $moneda = $_POST['moneda'];
      $estado = $_POST['estado'];
      $observaciones = $_POST['observaciones'];
      $recurrente = $_POST['recurrente'];
      //$CODidcustomer = $_POST['CODidcustomer'];
      $fechaActual = date('YmdHis');
      $idCustomer = $_POST['Customer']; 

      $ultimoId = mysqli_insert_id($conexion);

      $documento = $_FILES['documento']['name'];
      $urlfile = 'files/'.$fechaActual.'-'.$documento;
        $result = 0;
        if(isset($documento) && $documento != ""){
          $tipo = $_FILES['documento']['type'];
          $temp  = $_FILES['documento']['tmp_name'];
        
         if( !((strpos($tipo,'pdf') || strpos($tipo,'word') ))){
            $alert = '<p class="msg_error">solo se permite archivos pdf, word</p>';
         }else{
          move_uploaded_file($temp,$urlfile); 
          // Obtener el nombre del archivo sin la extensión
          $nombreArchivo = pathinfo($documento, PATHINFO_FILENAME);
          // Obtener la extensión del archivo en minúsculas
          $extensionArchivo = strtolower(pathinfo($documento, PATHINFO_EXTENSION));

          $queryUpdate = "UPDATE collections SET documento='$nombreArchivo.$extensionArchivo', fechaEmision = '$fechaEmision', fechaVencimiento = '$fechaVencimiento', monto = '$monto', moneda = '$moneda', estado = '$estado', observaciones = '$observaciones', recurrente = '$recurrente' WHERE NumeroFactura = '$numeroFactura' AND idCollections = '$idcollection'";  
          $result2 = mysqli_query($conexion, $queryUpdate);
          $queryUpdate2 = "UPDATE files SET url = '$urlfile', type = '$extensionArchivo' WHERE COD_idCollections = '$idcollection'";
          $result2 = mysqli_query($conexion, $queryUpdate2 );

         }
        }else{
            $queryUpdate = "UPDATE collections SET fechaEmision = '$fechaEmision', fechaVencimiento = '$fechaVencimiento', monto = '$monto', moneda = '$moneda', estado = '$estado', observaciones = '$observaciones', recurrente = '$recurrente' WHERE NumeroFactura = '$numeroFactura' AND idCollections = '$idcollection'";          
            $result2 = mysqli_query($conexion, $queryUpdate);
        }

      if ($result2) {
          $alert = '<div class="alert alert-primary" role="alert">
                              Collection actualizada
                          </div>';
      } else {
          $alert = '<div class="alert alert-danger" role="alert">
                              Error al Actualizar
                      </div>';
      }
    
    //mysqli_close($conexion);
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: lista_proveedor.php");
    mysqli_close($conexion);
  }
  $idproveedor = $_REQUEST['id'];
  $sql = mysqli_query($conexion, "SELECT * FROM `collections` as c inner join customers as cu on c.CODidcustomer = cu.idCliente inner join files as f on c.idCollections = f.COD_idCollections where idCollections = $idproveedor");
  //mysqli_close($conexion);
  $result_sql = mysqli_num_rows($sql);
  if ($result_sql == 0) {
    header("Location: lista_collection.php");
  } else {
    while ($data = mysqli_fetch_array($sql)) {
        $idCod = $data['idCollections'];
        $numeroFactura = $data['NumeroFactura'];
        $fechaEmision = $data['fechaEmision'];
        $fechaVencimiento = $data['fechaVencimiento'];
        $monto = $data['monto'];
        $moneda = $data['moneda'];
        $estado = $data['estado'];
        $documento = $data['documento'];
        $observaciones = $data['observaciones'];
        $recurrente = $data['recurrente'];
        $company = $data['Company'];
    }
  }
  ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_collection.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <form action="" method="post" autocomplete="off"  enctype="multipart/form-data">
                <?php echo isset($alert) ? $alert : ''; ?>
                <div class="form-group">
                    <label for="NumeroFactura">Número de Factura</label>
                    <input type="text" placeholder="Ingrese Número de Factura" name="NumeroFactura" readonly id="NumeroFactura" value="<?php echo $numeroFactura?>"class="form-control" >
                </div>
                <div class="form-group">
                    <label for="fechaEmision">Fecha de Emisión</label>
                    <input type="date" placeholder="Ingrese Fecha de Emisión" name="fechaEmision" id="fechaEmision" value="<?php echo $fechaEmision?>" class="form-control"  >
                </div>
                <div class="form-group">
                    <label for="fechaVencimiento">Fecha de Vencimiento</label>
                    <input type="date" placeholder="Ingrese Fecha de Vencimiento" name="fechaVencimiento" id="fechaVencimiento" value="<?php echo $fechaVencimiento?>" class="form-control"  >
                </div>
                <div class="form-group">
                    <label for="monto">Monto</label>
                    <input type="number" placeholder="Ingrese Monto" name="monto" id="monto" class="form-control" value="<?php echo $monto?>">
                </div>
                <div class="form-group">
                    <label for="moneda">Moneda</label>
                    <select name="moneda" id="moneda" class="form-control">
                        <option value="Dolares">Dólares</option>
                        <option value="Euros">Euros</option>
                        <option value="Pesos Mexicanos">Pesos Mexicanos</option>
                        <option value="Rublos">Rublos</option>
                        <option value="Soles">Soles</option>
                        <option selected value="<?php echo $moneda?>"><?php echo $moneda?></option>
                        <option value="Yenes">Yenes</option>
                        <!-- Agrega más opciones aquí en orden alfabético -->
                    </select>
                </div>
                <div class="form-group">
                  <label for="estado">Estado</label>
                  <select name="estado" id="estado" class="form-control">
                      <option value="NC">NC</option>
                      <option value="Pendiente">Pendiente</option>
                      <option value="Pagado">Pagado</option>
                      <option selected value="<?php echo $estado?>"><?php echo $estado?></option>
                      <!-- Agrega más opciones aquí si es necesario -->
                  </select>
                </div>
                <div class="form-group">
                    <label for="documento">Documento</label>
                    <input type="file" placeholder="Ingrese Documento" name="documento" id="documento" class="form-control" value="<?php echo $documento?>">
                </div>
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <input type="text" placeholder="Ingrese Observaciones" name="observaciones" id="observaciones" class="form-control" value="<?php echo $observaciones?>">
                </div>
                <div class="form-group">
                    <label for="recurrente">Recurrente</label>
                    <input type="text" placeholder="Ingrese Recurrente" name="recurrente" id="recurrente" class="form-control" value="<?php echo $recurrente?>">
                </div>
                <div class="form-group">
                    <label for="recurrente">Customer</label>
                    <?php
                        $query_customer = mysqli_query($conexion, "SELECT * FROM customers");
                        $resultado_customer = mysqli_num_rows($query_customer);
                        mysqli_close($conexion);
                        
                         ?>
                        <select name="Customer" id="Customer" class="form-control"> 
                        <option selected value="<?php echo $company ?>"><?php echo $company ?></option>
                         <?php
                         if ($resultado_customer > 0) {
                            while ($customer = mysqli_fetch_array($query_customer)) {
                        ?>
                                <option value="<?php echo $customer["idCliente"]; ?>"><?php echo $customer["Company"] ?></option>
                        <?php

                            }
                        }
                        ?>
                        </select>
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

 