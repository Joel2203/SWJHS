 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {

    $oportunidad = $_POST['nombre'];
  $Status = $_POST['estado'];
  $Priorit = $_POST['prioridad'];
  $MRC = $_POST['mrc'];
  $Detalle = $_POST['Detalle'];
  $Expected_Close = $_POST['Close'];
  $FCV = $_POST['fcv'];
  $One_Shot = $_POST['one-shot'];
  $Producto = $_POST['producto'];
 
  $idcontact = $_REQUEST['id']; // Suponiendo que tengas un campo oculto en el formulario con el ID del contacto
  $query = "UPDATE sales SET Oportunidad = '$oportunidad', Status = '$Status', Priorit = '$Priorit', MRC = '$MRC', Detalle = '$Detalle', `Expected Close` = '$Expected_Close', FCV = '$FCV', `One Shot` = '$One_Shot', Producto = '$Producto' WHERE id = $idcontact";
 
  $query_customer = mysqli_query($conexion,$query);

      if ($query_customer) {
          $alert = '<div class="alert alert-primary" role="alert">
                              Oportunidad actualizada correctamente
                          </div>';
      } else {
          $alert = '<div class="alert alert-danger" role="alert">
                              Error al Guardar
                      </div>';
      }
}
$idcontact = $_REQUEST['id'];
 
$sql = mysqli_query($conexion, "SELECT fs.url, s.id,s.Oportunidad,cu.Company,s.Status,s.Priorit,s.MRC,u.usuario,s.Detalle,co.celular,s.`Expected Close`, co.nombre, s.FCV, s.`One Shot`, s.Producto, s.Propuesta FROM `sales` as s INNER JOIN usuario as u on s.idUsuario = u.idusuario INNER JOIN customers as cu on cu.idCliente = s.idCustomer INNER JOIN contacts as co on co.idContacts = s.idContact INNER JOIN file_sales as fs on fs.id = s.id WHERE s.id = $idcontact");
//mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);

  while ($data = mysqli_fetch_array($sql)) {
    $idCod = $data['id'];
    $oportunidad = $data['Oportunidad'];
    $company = $data['Company'];
    $Status = $data['Status'];
    $Priorit = $data['Priorit'];
    $MRC = $data['MRC'];
    $usuario =  $data['usuario'];
    $Detalle = $data['Detalle'];
    $celular = $data['celular'];
    $Expected_Close = $data['Expected Close'];
    $nombre = $data['nombre'];
    $FCV = $data['FCV'];
    $One_Shot = $data['One Shot'];
    $Producto = $data['Producto'];
   
}

  ?>
<script src="js/jquery.min.js"></script>
 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Editar Oportunidad</h1>
     <a href="prueba2.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
         <?php echo isset($alert) ? $alert : ''; ?>
         <div class="form-group">
         <div class="row">
         <div class="col-md-6">
      <div class="form-group">
        <label for="nombre">Oportunidad</label>
        <input type="text" value="<?php echo $oportunidad?>" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="estado">Estado</label>
        <select class="form-control" name="estado" id="estado">
          <option selected value="<?php echo $Status?>"><?php echo $Status?></option>
          <option value="Lead">Lead</option>
          <option value="Qualified">Qualified</option>
          <option value="Proposal">Proposal</option>
          <option value="Negotiation">Negotiation</option>
          <option value="Ganada">Ganada</option>
          <option value="Lost">Lost</option>
        </select>
      </div>
    </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="prioridad">Prioridad</label>
          <select class="form-control" name="prioridad" id="prioridad">
            <option selected value="<?php echo $Priorit?>"><?php echo $Priorit?></option>
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="mrc">MRC(Renta Mensual)</label>
          <input type="number" value="<?php echo $MRC?>" placeholder="Ingrese MRC" class="form-control" name="mrc" id="mrc">
        </div>
      </div>
    </div>


    <div class="form-group">
 
    <div class="col-sm-12">
      <div class="form-group">
        <label for="Detalle">Detalle</label>
        <input type="text" placeholder="Ingrese detalle" value="<?php echo $Detalle?>" class="form-control" name="Detalle" id="Detalle">
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="Close">Expected Close</label>
          <input type="date" placeholder="Ingrese detalle" value="<?php echo $Expected_Close?>" class="form-control" name="Close" id="Close">
        </div>
      </div>
 
    </div>

    <div class="row">
      
      <div class="col-md-12">
        <div class="form-group">
          <label for="fcv">FCV</label>
          <input type="text" placeholder="Ingrese FCV" value="<?php echo $FCV?>" class="form-control" name="fcv" id="fcv">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="one-shot">One Shot</label>
          <input type="text" placeholder="Ingrese One Shot" value="<?php echo $One_Shot?>" class="form-control" name="one-shot" id="one-shot" pattern="[0-9]+([.,][0-9]+)?">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="producto">Producto</label>
          <input type="text" placeholder="Ingrese nombre del producto" value="<?php echo $Producto?>" name="producto" id="producto" class="form-control">
        </div>
      </div>
    </div>

 

         <input type="submit" value="Editar Oportunidad" class="btn btn-primary">
       </form>
     </div>
   </div>


 </div>
 <!-- /.container-fluid -->

 </div>

 <div id="selectedAccount"></div>
<div id="selectedValue"></div>

 <script>
 
 $(document).ready(function() {
    var valorSelect = $('#COD_idAccount').val();
      console.log(valorSelect);
        $("#COD_idAccount").change(function() {
         
            var selectedValue = $(this).val();
            $.ajax({
            url: 'registro_contact_combobox.php',
            method: 'POST',
            data: {
                selectedValue: selectedValue
            },
            success: function(data1){
                $('#info').html(data1);
            }
        });
        });
    });
</script>

 <!-- End of Main Content -->
 <?php include_once "includes/footer.php"; ?>