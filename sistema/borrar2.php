<?php

/*
include "../conexion.php";

$idrol = 1;
$alert = '';

function encontrarIndicesConElementos($crud) {
    $indices = [];
    $arrayCrud = json_decode($crud, true);

    foreach ($arrayCrud as $indice => $subarray) {
        if (!empty($subarray)) {
            $indices[] = $indice + 1;
        }
    }

    return '[' . implode(',', $indices) . ']';
}

$sql = mysqli_query($conexion, "SELECT * FROM `rolxpermisos`  WHERE idRol = $idrol");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: lista_roles.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id = $data['idRol'];
    $crud = $data['crud'];
    $idcrud = $data['idPermisoArreglo'];
}

mysqli_close($conexion);
*/

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

//$texto = "[[1,2,3,4],[],[1],[],[],[1],[1],[1],[1]]";
$texto = "[[1,2,3,4],[],[],[],[],[],[],[],[1]]";
echo "<br>Este es idCrud".$texto;
//$texto = $crud;
 

$seleccion = json_decode($texto);


echo "<script>
        function selectAllOptions(group) {
            var checkboxes = document.querySelectorAll('.opcion'+group);
            var selectAllCheckbox = document.getElementById('selectAll'+group);

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        }
    </script>";

echo "<form method='post'>";

for ($i = 0; $i < count($terminos); $i++) {
    $nombreOpcion = $terminos[$i][1];
    $grupo = $i;

    echo "<div class='container mb-4'>
            <h2>{$nombreOpcion}</h2>
            <div class='form-check'>
                <input class='form-check-input' type='checkbox' id='selectAll{$grupo}' onclick='selectAllOptions({$grupo})'>
                <label class='form-check-label' for='selectAll{$grupo}'>Seleccionar todas las opciones</label>
            </div>
        </div>
        <div class='container mb-4'>
            <div class='form-check form-switch'>
                <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[{$i}][]' id='opcion1_{$grupo}' value='1' " . (in_array(1, $seleccion[$i]) ? 'checked' : '') . ">
                <label class='form-check-label' for='opcion1_{$grupo}'>Crear {$nombreOpcion}</label>
            </div>
            <div class='form-check form-switch'>
                <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[{$i}][]' id='opcion2_{$grupo}' value='2' " . (in_array(2, $seleccion[$i]) ? 'checked' : '') . ">
                <label class='form-check-label' for='opcion2_{$grupo}'>Ver {$nombreOpcion}</label>
            </div>
            <div class='form-check form-switch'>
                <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[{$i}][]' id='opcion3_{$grupo}' value='3' " . (in_array(3, $seleccion[$i]) ? 'checked' : '') . ">
                <label class='form-check-label' for='opcion3_{$grupo}'>Editar {$nombreOpcion}</label>
            </div>
            <div class='form-check form-switch'>
                <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[{$i}][]' id='opcion4_{$grupo}' value='4' " . (in_array(4, $seleccion[$i]) ? 'checked' : '') . ">
                <label class='form-check-label' for='opcion4_{$grupo}'>Eliminar {$nombreOpcion}</label>
            </div>
        </div>";
}

echo "<button type='submit' class='btn btn-primary' name='guardar'>Guardar</button>";
echo "</form>";

if (isset($_POST['guardar'])) {
    $opcionesSeleccionadas = $_POST['opcion'];
    $arregloResultado = [];

    foreach ($opcionesSeleccionadas as $grupo => $opciones) {
        $arregloGrupo = [];

        if (empty($opciones)) {
            $arregloResultado[] = [];
        } else {
            for ($i = 1; $i <= 4; $i++) {
                if (in_array($i, $opciones)) {
                    $arregloGrupo[] = $i;
                }
            }
            $arregloResultado[] = $arregloGrupo;
        }
    }

    echo "<pre>";
    print_r($arregloResultado);
    echo "</pre>";

    $cadenaResultado = json_encode($arregloResultado, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo $cadenaResultado;
}


?>
