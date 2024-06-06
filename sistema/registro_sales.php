    <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    $prioridad = $_POST['prioridad'];
    $mrc = $_POST['mrc'];
    $COD_idAccount = $_POST['COD_idAccount'];
    $Detalle = $_POST['Detalle'];
    $Close = $_POST['Close'];
    $fcv = isset($_POST['fcv']) ? $_POST['fcv'] : 0;
    $one_shot = isset($_POST['one-shot']) ? $_POST['one-shot'] : 0;    
    $tipo1 = $_POST['tipo'];
    $producto = $_POST['producto'];

    $idcompany = $_POST['COD_idAccount'];
    $idcontact = isset($_POST['COD_idContact']) ? $_POST['COD_idContact'] : 0;
    $id = $_SESSION['idUser'];
    $fechaActual = date('YmdHis');

    $documento = $_FILES['Propuesta']['name'];
    $urlfile = 'files_sales/'.$fechaActual.'-'.$documento;
 
 
   $result = 0;

    if($idcontact == 0){
      $alert = '<div class="alert alert-danger" role="alert">El contacto es requerido</div>';
    }else{


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

          // Mostrar el nombre y la extensión del archivo
          //echo "Nombre del archivo: " . $nombreArchivo . "<br>";
          //echo "Extensión del archivo: " . $extensionArchivo;

          // Consulta 2
          $query2 = "INSERT INTO sales (Oportunidad, Status, Priorit, MRC, Detalle, `Expected Close`, FCV, `One Shot`,tipo, Producto, Propuesta, Typesale, idUsuario, idCustomer, idContact)
          VALUES ('$nombre','$estado', '$prioridad', '$mrc', '$Detalle' , '$Close' , $fcv, '$one_shot' ,'$tipo1', '$producto','$nombreArchivo.$extensionArchivo', '0', $id, $idcompany, $idcontact);";

          $result2 = mysqli_query($conexion, $query2);

          $id_insertado = mysqli_insert_id($conexion);

          $query1 = "INSERT INTO file_sales (url, type, COD_idSales) VALUES ('$urlfile', '$extensionArchivo','$id_insertado')";
          $result2 = mysqli_query($conexion, $query1);

         }
        }else{
          $query2 = "INSERT INTO sales (Oportunidad, Status, Priorit, MRC, Detalle, `Expected Close`, FCV, `One Shot`,tipo, Producto, Propuesta, Typesale, idUsuario, idCustomer, idContact)
          VALUES ('$nombre','$estado', '$prioridad', '$mrc', '$Detalle' , '$Close' , $fcv,'$one_shot' ,'$tipo1' , '$producto','none', '0', $id, $idcompany, $idcontact);";


        $result2 = mysqli_query($conexion, $query2);


          $id_insertado = mysqli_insert_id($conexion);

          $query1 = "INSERT INTO file_sales (url, type, COD_idSales) VALUES ('none', 'none','$id_insertado')";
          $result2 = mysqli_query($conexion, $query1);
        } 

        if ($result2) {
          $alert = '<div class="alert alert-primary" role="alert">
                              Sales Registrada correctamente
                          </div>';
      } else {
          $alert = '<div class="alert alert-danger" role="alert">
                              Error al Guardar
                      </div>';
      }
    }
     

}

  ?>
<style>
  #fileUploadMessage {
  background-color: #cce5ff;
  border: 1px solid #b8daff;
  padding: 8px;
  margin-top: 10px;
  display: none;
}

</style>
<script src="js/jquery.min.js"></script>
 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Registar Oportunidad</h1>
     <a href="lista_oportunidades_1.php" class="btn btn-primary">Regresar</a>
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
        <input type="text" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control" required>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="estado">Estado</label>
        <input type="text" value="Lead" readonly placeholder="Ingrese MRC" class="form-control" name="estado" id="estado">
      </div>
    </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="prioridad">Prioridad</label>
          <select class="form-control" name="prioridad" id="prioridad">
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="mrc">MRC(Renta Mensual)</label>
          <input type="number" value="0" placeholder="Ingrese MRC" class="form-control" name="mrc" id="mrc">
        </div>
      </div>
    </div>


    <div class="form-group">

    <div class="row">
      <div class="col-md-12">

      <div class="form-group">
                                <div class="form-group">
    <label for="recurrente">Cuenta</label>
    <?php
    $iduser = $_SESSION['idUser'];
    if ($_SESSION['rol'] == 1) {
        $query_customer = mysqli_query($conexion, "SELECT * FROM customers");
    } else {
        $query_customer = mysqli_query($conexion, "SELECT * FROM customers where COD_idusuario = $iduser");
    }

    $resultado_customer = mysqli_num_rows($query_customer);
    //mysqli_close($conexion);
    
    ?>
    <div class="d-flex">
        <input type="text" id="searchAccountInput" class="form-control" placeholder="Search account...">
        <select name="COD_idAccount" id="COD_idAccount" class="form-control">
            <option value="">Escoja una opción</option>
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
        <a href="agregar_account.php" id="btnSubmit" class="btn btn-primary ml-2">Crear nuevo</a>
    </div>
</div>
<div id="info"></div>

<script>
    document.getElementById("searchAccountInput").addEventListener("input", function() {
        var input, filter, select, option, i, txtValue;
        input = document.getElementById("searchAccountInput");
        filter = input.value.toUpperCase();
        select = document.getElementById("COD_idAccount");
        option = select.getElementsByTagName("option");
        for (i = 0; i < option.length; i++) {
            txtValue = option[i].textContent || option[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                option[i].style.display = "";
            } else {
                option[i].style.display = "none";
            }
        }
    });

    function handleFileUpload(event) {
        const fileInput = event.target;
        const fileName = fileInput.files[0].name;
        const fileUploadMessage = document.getElementById('fileUploadMessage');
        const fileNameElement = document.getElementById('fileName');

        fileUploadMessage.style.display = 'block';
        fileNameElement.textContent = fileName;
    }

</script>
 </div>
    </div>
    </div>
    <div class="col-sm-12">
       <div class="form-group">
          <label for="tipo_contacto">Tipo de Contacto</label>
          <select name="tipo_contacto" id="tipo_contacto" class="form-control">
            <option value="Externo">Externo</option>
            <option value="Influenciador">Influenciador</option>
            <option value="Decisor">Decisor</option>
          </select>
        </div>
      <div class="form-group">
        <label for="Detalle">Detalle</label>
        <textarea placeholder="Ingrese Detalle" name="Detalle" id="Detalle" class="form-control"></textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="Close">Expected Close</label>
          <input type="date" placeholder="Ingrese detalle" class="form-control" name="Close" id="Close" min="<?php echo date('Y-m-d'); ?>">
        </div>
      </div>
 
    </div>

    <div class="row">
      
      <div class="col-md-12">
        <div class="form-group">
          <label for="fcv">FCV</label>
          <input type="number" value="0" placeholder="Ingrese FCV" class="form-control" name="fcv" id="fcv">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="one-shot">One Shot</label>
          <input type="number" value="0" placeholder="Ingrese One Shot" class="form-control" name="one-shot" id="one-shot" pattern="[0-9]+([.,][0-9]+)?">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="tipo">Tipo</label>
          <select class="form-control" name="tipo" id="tipo">
            <option value="PEN">PEN</option>
            <option value="USD">USD</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="producto">Producto</label>
          <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control" required>
        </div>
      </div>
    </div>


    <div class="form-group">
        <label for="Propuesta">Propuesta</label>
        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="Propuesta" name="Propuesta" accept=".pdf" onchange="handleFileUpload(event)">
                <label class="custom-file-label" for="Propuesta">Subir archivo PDF</label>
            </div>
            <div class="input-group-append">
                <span class="input-group-text"><i class="far fa-file-pdf"></i></span>
            </div>
        </div>
    </div>
    <div id="fileUploadMessage" style="display: none;">
        Archivo subido:
        <span id="fileName"></span>
    </div>


         <input type="submit" value="Guardar Oportunidad" class="btn btn-primary">
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