<?php
date_default_timezone_set("America/Bogota");
setlocale(LC_ALL,"es_ES");

include "../conexion.php";

// Obtener los datos del formulario
$idEvento = $_POST['idEvento'];
$asunto = $_POST['asuntoUpdate'];
$fechaInicio = $_POST['fecha_inicio'];
$horaInicio = $_POST['hora_inicio'];
$fechaFin = $_POST['fecha_fin'];
$horaFin = $_POST['hora_final'];
$ubicacion = $_POST['ubicacionUpdate'];
$mostrarHora = $_POST['mostrar_horaUpdate'];
$descripcion = $_POST['descripcionUpdate'];
$COD_idrol = $_POST['COD_idrolUpdate'];

// Realizar la actualización
// Asignar color_evento
if ($mostrarHora == "--Ninguno--") {
    $color_evento = "#00FF00"; // Verde
} elseif ($mostrarHora == "Ocupada") {
    $color_evento = "#FF0000"; // Rojo
} elseif ($mostrarHora == "Fuera de la oficina") {
    $color_evento = "#FFFF00"; // Amarillo
} elseif ($mostrarHora == "Disponible") {
    $color_evento = "#0000FF"; // Azul
}

$UpdateEvento = "
    UPDATE eventos1 
    SET asunto='$asunto',
        fecha_inicio='$fechaInicio',
        h_inicio='$horaInicio',
        fecha_fin='$fechaFin',
        h_fin='$horaFin',
        ubicacion='$ubicacion',
        mostrar_hora='$mostrarHora',
        descripcion='$descripcion',
        COD_idrol='$COD_idrol',
        color_evento='$color_evento'
    WHERE id='$idEvento'
";

$result = mysqli_query($conexion, $UpdateEvento);

if ($result) {
    // La actualización fue exitosa
    header("Location: lista_tarea.php?ea=1");
} else {
    // Hubo un error en la actualización
    echo "Error al actualizar el evento: " . mysqli_error($conexion);
}
?>
