<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-danger" role="alert">
                                    Todo los campos son obligatorio
                                </div>';
    } else {
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $usuario_id = $_SESSION['idUser'];

        $result = 0;
        if (is_numeric($dni) and $dni != 0) {
            $query = mysqli_query($conexion, "SELECT * FROM cliente where dni = '$dni'");
            $result = mysqli_fetch_array($query);
        }
        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                                    El dni ya existe
                                </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO cliente(dni,nombre,telefono,direccion, usuario_id) values ('$dni', '$nombre', '$telefono', '$direccion', '$usuario_id')");
            if ($query_insert) {
                $alert = '<div class="alert alert-primary" role="alert">
                                    Cliente Registrado
                                </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                                    Error al Guardar
                            </div>';
            }
        }
    }
    //mysqli_close($conexion);
}
?>
<script src="js/jquery.min.js"></script>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_activity.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <form action="" method="post" autocomplete="off">
                <?php echo isset($alert) ? $alert : ''; ?>
                <div class="form-group">
                    <label for="idActivities">ID de Actividades</label>
                    <input type="number" placeholder="Ingrese ID de Actividades" name="idActivities" id="idActivities" class="form-control">
                </div>
                <div class="form-group">
                    <label for="asunto">Asunto</label>
                    <input type="text" placeholder="Ingrese asunto" name="asunto" id="asunto" class="form-control">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea placeholder="Ingrese descripción" name="descripcion" id="descripcion" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="fechaInicio">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" name="fechaInicio" id="fechaInicio" class="form-control">
                </div>
                <div class="form-group">
                    <label for="fechaFin">Fecha y Hora de Fin</label>
                    <input type="datetime-local" name="fechaFin" id="fechaFin" class="form-control">
                </div>
                <div class="form-group">
                    <label for="COD_idContact">Código de Contacto</label>
                    <input type="number" placeholder="Ingrese Código de Contacto" name="COD_idContact" id="COD_idContact" class="form-control">
                </div>
 

                <div class="form-group">
                                <label for="recurrente">Account</label>
                                <?php
                                    $query_customer = mysqli_query($conexion, "SELECT * FROM account");
                                    $resultado_customer = mysqli_num_rows($query_customer);
                                    //mysqli_close($conexion);
                                    
                                    ?>
                                    <select name="COD_idAccount" id="COD_idAccount" class="form-control"> 
                                    <?php
                                    if ($resultado_customer > 0) {
                                        while ($customer = mysqli_fetch_array($query_customer)) {
                                    ?>
                                            <option value="<?php echo $customer["idAccount"]; ?>"><?php echo $customer["cuenta"] ?></option>
                                    <?php

                                        }
                                    }
                                    ?>
                                    </select>
                                    
                            </div>
                            <div id="info"></div>
               
                            <input type="button" value="Insertar"  id="pruebaruc2" class="btn btn-primary">
                <input type="submit" value="Guardar Cliente" class="btn btn-primary">
            </form>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
 
<div id="selectedAccount"></div>
<div id="selectedValue"></div>

<script>
 
 $(document).ready(function() {
    var valorSelect = $('#COD_idAccount').val();
      console.log(valorSelect);
        $("#COD_idAccount").change(function() {
         
            var selectedValue = $(this).val();
            $.ajax({
            url: 'registro_activity_combobox.php',
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
</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>