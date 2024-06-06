<?php include_once "includes/header.php"; 
include "../conexion.php";
ob_end_flush(); 
ob_start();
$idrol = 28;
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

function obtenerDatosRol($conexion, $idRol) {
    $sql = mysqli_query($conexion, "SELECT * FROM `rolxpermisos`  WHERE idRol = $idRol");
    $result_sql = mysqli_num_rows($sql);

    if ($result_sql == 0) {
        return false; // No se encontraron datos para el rol
    } else {
        $data = mysqli_fetch_array($sql);
        return [
            'id' => $data['idRol'],
            'crud' => $data['crud'],
            'idCrud' => $data['idPermisoArreglo']
        ];
    }
}

$datosRol = obtenerDatosRol($conexion, $idrol);

if ($datosRol === false) {
    header("Location: lista_roles.php");
} else {
    $id = $datosRol['id'];
    $crud = $datosRol['crud'];
    $idcrud = $datosRol['idCrud'];
}

 
$texto = $crud;
 

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

    echo '<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_collection.php" class="btn btn-primary">Regresar</a>
    </div>';
 
    echo "<div class='container d-flex justify-content-center'>";



    echo "<form method='post'>";
        
    for ($i = 0; $i < count($terminos); $i++) {
        $nombreOpcion = $terminos[$i][1];
        $grupo = $i;
    
        echo "<div class='mb-4'>
                <h2>{$nombreOpcion}</h2>
                <div class='form-check'>
                    <input class='form-check-input' type='checkbox' id='selectAll{$grupo}' onclick='selectAllOptions({$grupo})'>
                    <label class='form-check-label' for='selectAll{$grupo}'>Seleccionar todas las opciones</label>
                </div>
            </div>
            <div class='mb-4'>
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
    
    echo "<button type='submit' class='btn btn-primary' name='guardar'  id='botonGuardar'>Guardar</button>";
    echo "</form>";
    echo "</div>";



    

      if (isset($_POST['guardar'])) {
        $opcionesSeleccionadas = isset($_POST['opcion']) ? $_POST['opcion'] : [];
        $arregloResultado = [];
    
        foreach ($terminos as $grupo => $opcion) {
            $arregloGrupo = [];
    
            if (isset($opcionesSeleccionadas[$grupo])) {
                foreach ($opcionesSeleccionadas[$grupo] as $valor) {
                    $arregloGrupo[] = (int)$valor;
                }
            }
    
            $arregloResultado[] = $arregloGrupo;
        }
    
        $cadenaResultado = json_encode($arregloResultado, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    
        $idrolpermisosarreglo = encontrarIndicesConElementos($cadenaResultado);
    
        $query2 = "UPDATE rolxpermisos SET crud = '$cadenaResultado', idPermisoArreglo = '$idrolpermisosarreglo' WHERE idRol =  $idrol";
        $alert = '<div class="alert alert-success" role="alert">Permisos actualizados correctamente</div>';
        $result2 = mysqli_query($conexion, $query2);
    
        // Obtener datos actualizados después de la actualización
        $datosNuevos = obtenerDatosRol($conexion, $id);
    
        echo "<pre> crud";
        echo $idrolpermisosarreglo;
        echo $cadenaResultado;
    
        if ($datosNuevos !== false) {
            // Mostrar los datos actualizados
            echo "Datos actualizados del rol:<br>";
            echo "ID: " . $datosNuevos['id'] . "<br>";
            echo "CRUD: " . $datosNuevos['crud'] . "<br>";
            echo "ID CRUD: " . $datosNuevos['idCrud'] . "<br>";
        }
    }

?>
 <?php include_once "includes/footer.php"; ?>