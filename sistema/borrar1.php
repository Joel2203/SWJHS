<?php

//include_once "includes/header.php";

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

$seleccion = [[1,2,3,4],[],[1],[],[],[1],[1],[1],[1]];

echo "<script>
        function selectAllOptions(group) {
            var checkboxes = document.querySelectorAll('.opcion'+group);
            var selectAllCheckbox = document.getElementById('selectAll'+group);

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        }
    </script>";

for ($i = 0; $i < count($terminos); $i++) {
    $nombreOpcion = $terminos[$i][1];
    $grupo = $i;

    echo "<div class='container d-flex justify-content-between align-items-center'>
                <h2>{$nombreOpcion}</h2>
                <div class='form-check'>
                    <input class='form-check-input' type='checkbox' id='selectAll{$grupo}' onclick='selectAllOptions({$grupo})'>
                    <label class='form-check-label' for='selectAll{$grupo}'>Seleccionar todas las opciones</label>
                </div>
            </div>
            <div class='container'>
                <form method='post'>
                    <div class='form-check form-switch'>
                        <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[]' id='opcion1_{$grupo}' value='1' " . (in_array(1, $seleccion[$i]) ? 'checked' : '') . ">
                        <label class='form-check-label' for='opcion1_{$grupo}'>Crear {$nombreOpcion}</label>
                    </div>
                    <div class='form-check form-switch'>
                        <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[]' id='opcion2_{$grupo}' value='2' " . (in_array(2, $seleccion[$i]) ? 'checked' : '') . ">
                        <label class='form-check-label' for='opcion2_{$grupo}'>Ver {$nombreOpcion}</label>
                    </div>
                    <div class='form-check form-switch'>
                        <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[]' id='opcion3_{$grupo}' value='3' " . (in_array(3, $seleccion[$i]) ? 'checked' : '') . ">
                        <label class='form-check-label' for='opcion3_{$grupo}'>Editar {$nombreOpcion}</label>
                    </div>
                    <div class='form-check form-switch'>
                        <input class='form-check-input opcion{$grupo}' type='checkbox' name='opcion[]' id='opcion4_{$grupo}' value='4' " . (in_array(4, $seleccion[$i]) ? 'checked' : '') . ">
                        <label class='form-check-label' for='opcion4_{$grupo}'>Eliminar {$nombreOpcion}</label>
                    </div>
                    <button type='submit' class='btn btn-primary mt-3' name='guardar'>Guardar</button>
                </form>
            </div>";
}

?>


