<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    
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

          // Mostrar el nombre y la extensión del archivo
          //echo "Nombre del archivo: " . $nombreArchivo . "<br>";
          //echo "Extensión del archivo: " . $extensionArchivo;

          // Consulta 2
          $query2 = "INSERT INTO collections (NumeroFactura, fechaEmision, fechaVencimiento, monto, moneda, estado, documento, observaciones, recurrente, CODidcustomer)
          VALUES ('$numeroFactura', '$fechaEmision', '$fechaVencimiento', '$monto', '$moneda', '$estado', '$nombreArchivo.$extensionArchivo', '$observaciones', '$recurrente', '$idCustomer')";
          $result2 = mysqli_query($conexion, $query2);

          $querySelect = "SELECT idCollections FROM `collections` where NumeroFactura = '$numeroFactura' ORDER BY `idCollections` DESC LIMIT 1";
          $result1 = mysqli_query($conexion, $querySelect);
          $resultFinal = mysqli_fetch_array($result1);
          $id = $resultFinal['idCollections'];

          $query1 = "INSERT INTO files (url, type, COD_idCollections) VALUES ('$urlfile', '$extensionArchivo','$id')";
          $result2 = mysqli_query($conexion, $query1);

         }
        }else{
            $query2 = "INSERT INTO collections (NumeroFactura, fechaEmision, fechaVencimiento, monto, moneda, estado, documento, observaciones, recurrente, CODidcustomer)
          VALUES ('$numeroFactura', '$fechaEmision', '$fechaVencimiento', '$monto', '$moneda', '$estado', 'none', '$observaciones', '$recurrente', '$idCustomer')";
          $result2 = mysqli_query($conexion, $query2);

          $querySelect = "SELECT idCollections FROM `collections` where NumeroFactura = '$numeroFactura' ORDER BY `idCollections` DESC LIMIT 1";
          $result1 = mysqli_query($conexion, $querySelect);
          $resultFinal = mysqli_fetch_array($result1);
          $id = $resultFinal['idCollections'];

          $query1 = "INSERT INTO files (url, type, COD_idCollections) VALUES ('none', 'none','$id')";
          $result2 = mysqli_query($conexion, $query1);
        }

      if ($result2) {
          $alert = '<div class="alert alert-primary" role="alert">
                              Collection Registrado
                          </div>';
      } else {
          $alert = '<div class="alert alert-danger" role="alert">
                              Error al Guardar
                      </div>';
      }
    
    //mysqli_close($conexion);
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

.upload-message {
    background-color: #cce5ff;
    border: 1px solid #b8daff;
    padding: 8px;
    margin-top: 10px;
    display: none;
}

.upload-message span {
    font-weight: bold;
}

</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_collection.php" class="btn btn-primary">Regresar</a>
    </div>
  
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(event) {
        var numeroFactura = document.getElementById('NumeroFactura').value;
        var fechaEmision = document.getElementById('fechaEmision').value;
        var fechaVencimiento = document.getElementById('fechaVencimiento').value;
        var monto = document.getElementById('monto').value;
        var documento = document.getElementById('documento').value;
        var observaciones = document.getElementById('observaciones').value;
        var recurrente = document.getElementById('recurrente').value;

        var customerSelect = document.getElementById('Customer').value;

        if (numeroFactura === "") {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El Número de Factura es requerido',
            });
            return;
        }

        if (fechaEmision >= fechaVencimiento + 1 ) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La Fecha de Emisión debe ser anterior a la Fecha de Vencimiento',
            });
            return;
        }

        if (monto === "") {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El Monto es requerido',
            });
            return;
        }


        if (recurrente === "") {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El Recurrente es requerido',
            });
            return;
            }
        });

        if (customerSelect === "0") {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, selecciona un Customer',
            });
            return;
        }
    });

    </script>
    <!-- Content Row -->
    <div class="row">
    <div class="col-lg-6 m-auto">
        <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <?php echo isset($alert) ? $alert : ''; ?>
            <div class="form-group">
                <label for="NumeroFactura">Número de Factura</label>
                <input type="text" placeholder="Ingrese Número de Factura" name="NumeroFactura" id="NumeroFactura" class="form-control" required>
            </div>
            <div class="container">
                <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                    <label for="fechaEmision">Fecha de Emisión</label>
                    <input type="date" placeholder="Ingrese Fecha de Emisión" name="fechaEmision" id="fechaEmision" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                    <label for="fechaVencimiento">Fecha de Vencimiento</label>
                    <input type="date" placeholder="Ingrese Fecha de Vencimiento" name="fechaVencimiento" id="fechaVencimiento" class="form-control">
                    </div>
                </div>
                </div>
            </div>
            <script>
                function handleFileUpload(inputId) {
                    const fileInput = document.getElementById(inputId);
                    const fileName = fileInput.files[0].name;
                    const fileUploadMessage = document.getElementById(`${inputId}UploadMessage`);
                    const fileNameElement = document.getElementById(`${inputId}FileName`);

                    fileUploadMessage.style.display = 'block';
                    fileNameElement.textContent = fileName;
                }

                function filtrarCustomers() {
                    const input = document.getElementById('buscador');
                    const filter = input.value.toUpperCase();
                    const select = document.getElementById('Customer');
                    const options = select.getElementsByTagName('option');

                    for (let i = 0; i < options.length; i++) {
                        const txtValue = options[i].textContent || options[i].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            options[i].style.display = '';
                        } else {
                            options[i].style.display = 'none';
                        }
                    }
                }


            </script>
            <div class="container">
                <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                    <label for="monto">Monto</label>
                    <input type="number" placeholder="Ingrese Monto" name="monto" id="monto" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                    <label for="moneda">Moneda</label>
                    <select name="moneda" id="moneda" class="form-control">
                        <option value="Dolares">Dólares</option>
                        <option value="Euros">Euros</option>
                        <option value="Pesos Mexicanos">Pesos Mexicanos</option>
                        <option value="Rublos">Rublos</option>
                        <option selected value="Soles">Soles</option>
                        <option value="Yenes">Yenes</option>
                        <!-- Agrega más opciones aquí en orden alfabético -->
                    </select>
                    </div>
                </div>
                </div>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" class="form-control">
                    <option value="NC">NC</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <!-- Agrega más opciones aquí si es necesario -->
                </select>
            </div>
            <div class="form-group">
                <label for="documento">Documento</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="documento" name="documento" accept=".pdf" onchange="handleFileUpload('documento')">
                        <label class="custom-file-label" for="documento">Subir archivo PDF</label>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="far fa-file-pdf"></i></span>
                    </div>
                </div>
            </div>
            <div id="documentoUploadMessage" style="display: none;">
                Archivo subido:
                <span id="documentoFileName"></span>
            </div>

            <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea placeholder="Ingrese Observaciones" name="observaciones" id="observaciones" class="form-control"></textarea>
                </div>
            </div>
            </div>
            <div class="form-group">
                <label for="recurrente">Recurrente</label>
                <input type="text" placeholder="Ingrese Recurrente" name="recurrente" id="recurrente" class="form-control">
            </div>
            <div class="form-group">
                <label for="recurrente">Customer</label>
                <?php
                $query_customer = mysqli_query($conexion, "SELECT * FROM `customers` ORDER BY `customers`.`Company` ASC");
                $resultado_customer = mysqli_num_rows($query_customer);
                mysqli_close($conexion);
                ?>
                
                <select name="Customer" id="Customer" class="form-control">
                            <option value="0">--Escoger una opción--</option>
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
                <input type="text" class="form-control" id="buscador" onkeyup="filtrarCustomers()" placeholder="Escribe para buscar...">
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

 