 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {

    $oportunidad = $_POST['nombre'];
  $Status1 = $_POST['estado'];
  $Priorit = $_POST['prioridad'];
  $MRC = $_POST['mrc'];
  $Detalle = $_POST['Detalle'];
  $Expected_Close = $_POST['Close'];
  $FCV = $_POST['fcv'];
  $One_Shot = $_POST['one-shot'];
  $Producto = $_POST['producto'];

  $fechaActual = date('YmdHis');

  $documento = $_FILES['Propuesta']['name'];
  $urlfile = 'files_sales/'.$fechaActual.'-'.$documento;
 
  $idcontact = $_REQUEST['id']; // Suponiendo que tengas un campo oculto en el formulario con el ID del contacto
  
  if(isset($documento) && $documento != ""){
    $tipo = $_FILES['Propuesta']['type'];
    $temp  = $_FILES['Propuesta']['tmp_name'];

     if( !((strpos($tipo,'pdf') || strpos($tipo,'word') ))){
      $alert = '<p class="msg_error">solo se permite archivos pdf, word</p>';
   }else{
          move_uploaded_file($temp,$urlfile); 
                // Obtener el nombre del archivo sin la extensión
                $nombreArchivo = pathinfo($documento, PATHINFO_FILENAME);

                // Obtener la extensión del archivo en minúsculas
                $extensionArchivo = strtolower(pathinfo($documento, PATHINFO_EXTENSION));
                

        $query = "UPDATE sales SET Oportunidad = '$oportunidad', Status = '$Status1', Priorit = '$Priorit', MRC = '$MRC', Detalle = '$Detalle', `Expected Close` = '$Expected_Close', FCV = '$FCV', `One Shot` = '$One_Shot', Producto = '$Producto', Propuesta = '$nombreArchivo.$extensionArchivo' WHERE id = $idcontact";

        $id_insertado = mysqli_insert_id($conexion);

        $query1 = "UPDATE file_sales SET url = '$urlfile', type = '$extensionArchivo' WHERE COD_idSales = '$idcontact'";
        $result2 = mysqli_query($conexion, $query1);

    }
  }else{
    $query = "UPDATE sales SET Oportunidad = '$oportunidad', Status = '$Status1', Priorit = '$Priorit', MRC = '$MRC', Detalle = '$Detalle', `Expected Close` = '$Expected_Close', FCV = '$FCV', `One Shot` = '$One_Shot', Producto = '$Producto' WHERE id = $idcontact";
  }
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
 
$sql = mysqli_query($conexion, "SELECT  fs.url, s.id,s.Oportunidad,cu.Company,s.Status,s.Priorit,s.MRC,u.usuario,s.Detalle,co.celular,s.`Expected Close`, co.nombre, s.FCV, s.`One Shot`, s.Producto, s.Propuesta FROM sales as s inner join file_sales as fs on s.id = fs.COD_idSales INNER JOIN customers as cu on cu.idCliente = s.idCustomer INNER JOIN contacts as co on co.idContacts = s.idContact INNER JOIN usuario as u on s.idUsuario = u.idusuario WHERE s.id = $idcontact");
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
     <a href="lista_oportunidades_1.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- STEPS-->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <style>
    .steps-container {
      display: flex;
      align-items: center;
    }

    .step-item {
      flex: 1;
      position: relative;
      text-align: center;
      padding: 10px;
      background-color: #d2edf4;
      color: #007bff;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .step-item:hover {
      background-color: #007bff;
      color: #fff;
    }

    .step-item.checked::before {
      content: '\2713';
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #007bff;
      color: #fff;
      width: 30px;
      height: 30px;
      line-height: 30px;
      border-radius: 50%;
      font-size: 18px;
    }

    .step-item.lost {
      background-color: #ff0000;
      color: #ffffff;
    }

    .step-item.lost::before {
      content: 'x';
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #ff0000;
      color: #ffffff;
      width: 30px;
      height: 30px;
      line-height: 30px;
      border-radius: 50%;
      font-size: 18px;
    }

    .progress-bar-container {
      width: 100%;
      height: 20px;
      background-color: #d2edf4;
      margin-top: 10px;
      position: relative;
    }

    .progress-bar {
      height: 100%;
      background-color: #007bff;
      width: 0;
      transition: width 0.3s ease;
    }

    .progress-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #007bff;
    }

    .disabled {
      pointer-events: none;
      opacity: 0.5;
    }
  </style>
    <?php
    include "../conexion.php";
    // Obtén el estado actual y el progreso almacenados en la tabla
    $query = "SELECT current_step, progress_width FROM sales where id = $idcontact";
    $result = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($result);
    $currentStep = $row['current_step'];
    $progressWidth = $row['progress_width'];

    ?>
    <script>
      $(document).ready(function() {
        // Restaura el estado y el progreso almacenados en la página
        var currentStep = <?php echo $currentStep; ?>;
        var progressBarWidth = <?php echo $progressWidth; ?>;

        // Actualiza el progreso y los pasos según lo almacenado
        $('.step-item:lt(' + (currentStep + 1) + ')').addClass('checked');
        $('.step-item:lt(' + currentStep + ')').addClass('disabled');
        $('.progress-bar').css('width', progressBarWidth + '%');
        $('.progress-text').text(progressBarWidth + '%');
        if(progressBarWidth==125){
          $('.step-item').removeClass('checked').css('background-color', '#8b2525');
                  $('.progress-text').css('color', 'red');
                  $('.progress-bar-container').css('background-color', '#ffdfdf');


                  $('.step-item').addClass('lost');
                  $('.step-item').removeClass('checked');
                  $('.progress-bar').addClass('disabled');
                  $('.progress-text').text('Venta perdida');
        }
      });
    </script>

          <div>
            <div class="steps-container">
              <div class="step-item checked" data-step="0">Lead</div>
              <div class="step-item" data-step="1">Qualified</div>
              <div class="step-item" data-step="2">Proposal</div>
              <div class="step-item" data-step="3">Negotiation</div>
              <div class="step-item" data-step="4">Ganada</div>
              <div class="step-item" data-step="5">Lost</div>
            </div>

            <div class="progress-bar-container">
              <div class="progress-bar"></div>
              <div class="progress-text">0%</div>
            </div>
            <button class="btn btn-primary mt-2" id="cambiarEstado" disabled>Cambiar de estado</button>
          </div>

            <script>
    $(document).ready(function() {
      var totalSteps = $('.step-item').length;
      var currentStep = 0;
      var progressBarWidth = 0;

      // Actualizar el progreso
      function updateProgress() {
        progressBarWidth = (currentStep / (totalSteps - 2)) * 100;
        console.log(progressBarWidth);
        if (progressBarWidth == 125) {
          $('.progress-bar').css('width', 0 + '%');
          $('.progress-text').text(0 + '%');
        } else {
          $('.progress-bar').css('width', progressBarWidth + '%');
          $('.progress-text').text(progressBarWidth + '%');
        }
      }

      // Manejar el evento de cambio de estado
      $('.step-item').click(function() {
        currentStep = parseInt($(this).data('step'));
        updateProgress();
        $('.step-item').removeClass('checked');
        $('.step-item:lt(' + (currentStep + 1) + ')').addClass('checked');
        $('#cambiarEstado').prop('disabled', false);
      });

      // Manejar el evento de clic en el botón de cambiar de estado
      $('#cambiarEstado').click(function() {
        var idContact = <?php echo $idcontact; ?>;
        // Realizar la solicitud AJAX
        $.ajax({
          url: 'cambiar_estado.php',
          method: 'POST',
          data: { estado: currentStep,
            idContact:idContact },
          success: function(response) {
            console.log(response);
                        // Deshabilitar los pasos anteriores al estado actual
            $('.step-item:lt(' + currentStep + ')').addClass('disabled');
            $('#cambiarEstado').prop('disabled', true);

            // Mostrar mensaje de éxito o error
            if (response === '4') {
              $('.progress-bar').addClass('disabled');
              $('.progress-text').text('Venta cerrada');
            } else if (response === '5') {
 
              $('.step-item').removeClass('checked').css('background-color', '#8b2525');
              $('.progress-text').css('color', 'red');
              $('.progress-bar-container').css('background-color', '#ffdfdf');


              $('.step-item').addClass('lost');
              $('.step-item').removeClass('checked');
              $('.progress-bar').addClass('disabled');
              $('.progress-text').text('Venta perdida');
            }
             setTimeout(function() {
        location.reload();
      }, 100);
          }
        });
      });

      // Inicializar el progreso
      updateProgress();
    });
  </script>
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
        <input type="text"  readonly placeholder="Ingrese MRC" value="<?php echo $Status?>" readonly class="form-control" name="estado" id="estado">
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

    <div class="form-group">
      <label for="Propuesta">Propuesta</label>
      <div class="input-group">
        <div class="custom-file">
          <input type="file" class="custom-file-input" id="Propuesta" name="Propuesta" accept=".pdf">
          <label class="custom-file-label" for="Propuesta">Subir archivo PDF</label>
        </div>
        <div class="input-group-append">
          <span class="input-group-text"><i class="far fa-file-pdf"></i></span>
        </div>
      </div>
    </div>

    <?php
 if($Status != 'Lost'){?>
         <input type="submit" value="Editar Oportunidad" class="btn btn-primary">
 <?php } ?>         
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