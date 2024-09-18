<?php include_once "includes/header.php";
include "../conexion.php";

if (!empty($_POST)) {
    $alert = "";


    $fechaEmision = $_POST['fechaEmision'];
    $fechaVencimiento = $_POST['fechaVencimiento'];
    $monto = $_POST['monto'];
    $moneda = $_POST['moneda'];
    $estado = $_POST['estado'];
    $observaciones = $_POST['observaciones'];
    $ruc = isset($_POST['ruc']) ? $_POST['ruc'] : '';
    $fechaActual = date('YmdHis');

 

    if ($result2) {
        $alert = '<div class="alert alert-primary" role="alert">
                            Work Registrado
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

    .toggle-buttons {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .toggle-button {
        margin: 0 10px;
        padding: 10px 20px;
        border: 1px solid #ccc;
        cursor: pointer;
        background-color: #f8f9fc;
    }

    .toggle-button.active {
        background-color: #4e73df;
        color: white;
    }
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_work.php" class="btn btn-primary">Regresar</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').addEventListener('submit', function(event) {
                var numeroDocumento = document.getElementById('numeroDocumento').value;
                var fechaEmision = document.getElementById('fechaEmision').value;
                var fechaVencimiento = document.getElementById('fechaVencimiento').value;
                var monto = document.getElementById('monto').value;
                var documento = document.getElementById('documento').value;
                var observaciones = document.getElementById('observaciones').value;
                var recurrente = document.getElementById('recurrente').value;
                var customerSelect = document.getElementById('Customer').value;

                if (numeroDocumento === "") {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El Número de Documento es requerido',
                    });
                    return;
                }

                if (fechaEmision >= fechaVencimiento + 1) {
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

            document.getElementById('tipoDocumentoToggle').addEventListener('click', function(event) {
                const isFactura = event.target.dataset.tipo === 'factura';
                document.getElementById('numeroDocumentoLabel').innerText = isFactura ? 'Número de Factura' : 'Número de Boleta';
                document.getElementById('rucField').style.display = isFactura ? 'block' : 'none';
                document.getElementById('tipoDocumento').value = isFactura ? 'Factura' : 'Boleta';

                document.querySelectorAll('.toggle-button').forEach(button => {
                    button.classList.remove('active');
                });
                event.target.classList.add('active');
            });
        });

        function handleFileUpload(inputId) {
            const fileInput = document.getElementById(inputId);
            const fileName = fileInput.files[0].name;
            const fileUploadMessage = document.getElementById(inputId + 'UploadMessage');
            const fileNameElement = document.getElementById(inputId + 'FileName');

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
    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                <?php echo isset($alert) ? $alert : ''; ?>
                <div class="form-group">
                    <label id="numeroDocumentoLabel" for="numeroDocumento">Número de Documento</label>
                    <input type="text" placeholder="Ingrese número de documento" name="numeroDocumento" id="numeroDocumento" class="form-control">
                </div>
                <div class="form-group" id="rucField">
                    <label for="ruc">RUC</label>
                    <input type="text" placeholder="Ingrese RUC" name="ruc" id="ruc" class="form-control">
                </div>
                <div class="form-group">
                    <label for="fechaEmision">Fecha de Emisión</label>
                    <input type="date" name="fechaEmision" id="fechaEmision" class="form-control">
                </div>
                <div class="form-group">
                    <label for="fechaVencimiento">Fecha de Vencimiento</label>
                    <input type="date" name="fechaVencimiento" id="fechaVencimiento" class="form-control">
                </div>
                <div class="form-group">
                    <label for="monto">Monto</label>
                    <input type="number" placeholder="Ingrese monto" name="monto" id="monto" class="form-control">
                </div>
                <div class="form-group">
                    <label for="moneda">Moneda</label>
                    <select name="moneda" id="moneda" class="form-control">
                        <option value="PEN">PEN</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="anulado">Anulado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="documento">Documento</label>
                    <input type="file" name="documento" id="documento" class="form-control-file" onchange="handleFileUpload('documento')">
                    <div id="documentoUploadMessage" class="upload-message">
                        Archivo seleccionado: <span id="documentoFileName"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea placeholder="Ingrese observaciones" name="observaciones" id="observaciones" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Guardar</button>
            </form>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>