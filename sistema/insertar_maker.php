<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    
      $Marca = $_POST['Marca'];
      $fechaModificacion = date("Y-m-d H:i:s");
 
        if(isset($Marca) && $Marca != ""){
          $query2 = "INSERT INTO maker (marca, fecha_De_registro)
          VALUES ('$Marca', '$fechaModificacion')";
          $result2 = mysqli_query($conexion, $query2);
          if ($result2) {
            $alert = '<div class="alert alert-primary" role="alert">
                                Maker Registrado
                            </div>';
          } else {
            $alert = '<div class="alert alert-danger" role="alert">
                                Error al Guardar
                        </div>';
           }
         }else{
          $alert = '<div class="alert alert-danger" role="alert">
           Marca debe estar lleno
           </div>';
         }

    //mysqli_close($conexion);
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_maker.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Content Row -->
    <div class="row">
    <div class="col-lg-6 m-auto">
        <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <?php echo isset($alert) ? $alert : ''; ?>
            <div class="form-group">
                <label for="Marca">Marca</label>
                <input type="text" placeholder="Ingrese Marca" name="Marca" id="Marca" class="form-control">
            </div>
             
 
            <input type="submit" value="Guardar marca" class="btn btn-primary">
        </form>
    </div>
</div>



</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>

 