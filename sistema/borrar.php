<?php

//leer
$terminos = array(
    array(1, 'Cuentas'),
    array(2, 'Cobranza'),
    array(3, 'Contactos'),
    array(4, 'Todos los productos'),
    array(5, 'Todas las cuentas'),
    array(6, 'Actividad'),
    array(7, 'Oportunidades'),
    array(8, 'Familia y productos'),
    array(9, 'Usuarios')
);

$arreglo = "[1,2,4]"; 
$arreglo_decodificado = json_decode($arreglo, true);





//crear

$opciones = []; // Inicializamos el arreglo de opciones

if(isset($_POST['guardar'])) {
    // Verificamos si se ha enviado el formulario

    // Comprobamos si se ha marcado la opción "Crear Cuentas"
    if(isset($_POST['crearCuentas'])) {
        $opciones[] = 1;
    }

    // Comprobamos si se ha marcado la opción "Ver Cuentas"
    if(isset($_POST['verCuentas'])) {
        $opciones[] = 2;
    }

    // Comprobamos si se ha marcado la opción "Editar Cuentas"
    if(isset($_POST['editarCuentas'])) {
        $opciones[] = 3;
    }

    // Comprobamos si se ha marcado la opción "Eliminar Cuentas"
    if(isset($_POST['eliminarCuentas'])) {
        $opciones[] = 4;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Switches con Bootstrap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 
</head>
<body>

<div class="container d-flex justify-content-between align-items-center">
    <h2>--posicion--</h2>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="selectAll">
        <label class="form-check-label" for="selectAll">Seleccionar todas las opciones</label>
    </div>
</div>

<div class="container">
    <form method="post">
        <?php
        for ($i = 1; $i <= 4; $i++) {
            $checked = in_array($i, $arreglo_decodificado) ? 'checked' : '';
            echo "<div class='form-check form-switch'>
                      <input class='form-check-input' type='checkbox' name='opcion[]' id='opcion{$i}' value='{$i}' {$checked}>
                      <label class='form-check-label' for='opcion{$i}'>Opción {$i}</label>
                  </div>";
        }
        ?>
        <button type="submit" class="btn btn-primary mt-3" name="guardar">Guardar</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var allChecked = true;
                checkboxes.forEach(function(cb) {
                    if (cb !== document.getElementById('selectAll') && !cb.checked) {
                        allChecked = false;
                    }
                });
                document.getElementById('selectAll').checked = allChecked;
            });
        });

        document.getElementById('selectAll').addEventListener('change', function() {
            checkboxes.forEach(function(cb) {
                if (cb !== this) {
                    cb.checked = this.checked;
                }
            });
        });
    });
</script>

    <div class="container">
        <form method="post">

            <h2>Cuentas</h2>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">Seleccionar todas las opciones</label>
            </div>


            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="crearCuentas" id="flexSwitchCrearCuentas">
                <label class="form-check-label" for="flexSwitchCrearCuentas">Crear Cuentas</label>
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="verCuentas" id="flexSwitchVerCuentas">
                <label class="form-check-label" for="flexSwitchVerCuentas">Ver Cuentas</label>
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="editarCuentas" id="flexSwitchEditarCuentas">
                <label class="form-check-label" for="flexSwitchEditarCuentas">Editar Cuentas</label>
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="eliminarCuentas" id="flexSwitchEliminarCuentas">
                <label class="form-check-label" for="flexSwitchEliminarCuentas">Eliminar Cuentas</label>
            </div>

            <button type="submit" class="btn btn-primary mt-3" name="guardar">Guardar</button>
        </form>
    </div>


    <?php
    if(isset($_POST['guardar'])) {
        echo "<h4>Opciones seleccionadas:</h4>";
        if(empty($opciones)) {
            echo "Ninguna opción seleccionada.";
        } else {
            echo "[" . implode(",", $opciones) . "]";
        }
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = event.target.checked;
            });
        });
    </script>
</body>
</html>
