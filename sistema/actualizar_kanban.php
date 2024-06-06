<?php
include "../conexion.php";

$id = $_POST['id'];
$estado = $_POST['estado'];

if($estado=='qualified'){
    $sql = "UPDATE sales SET Status = '$estado', current_step = '1', progress_width = '25' WHERE id = $id";
} else if ($estado == 'lead') {
    $sql = "UPDATE sales SET Status = '$estado', current_step = '0', progress_width = '0' WHERE id = $id";
} else if ($estado == 'proposal') {
    $sql = "UPDATE sales SET Status = '$estado', current_step = '2', progress_width = '50' WHERE id = $id";
}else if ($estado == 'negotiation') {
    $sql = "UPDATE sales SET Status = '$estado', current_step = '3', progress_width = '75' WHERE id = $id";
}else if ($estado == 'ganada') {
    $sql = "UPDATE sales SET Status = '$estado', current_step = '4', progress_width = '100' WHERE id = $id";
}else {
    $sql = "UPDATE sales SET Status = '$estado', current_step = '5', progress_width = '125' WHERE id = $id";
}



if ($conexion->query($sql) === TRUE) {
    echo "Estado actualizado exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>
