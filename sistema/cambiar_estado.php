<?php
 include "../conexion.php";
// Obtener el estado seleccionado
$estado = $_POST['estado'];
$idContact = $_POST['idContact'];
 

// Obtén el estado actual y el progreso del POST
$currentStep = $_POST['estado'];

$estadoUpdate = '';

if($currentStep==0){
  $estadoUpdate = "Lead";
}elseif($currentStep==1){
  $estadoUpdate = "Qualified";
}elseif($currentStep==2){
  $estadoUpdate = "Proposal";
}elseif($currentStep==3){
  $estadoUpdate = "Negotiation";
}elseif($currentStep==4){
  $estadoUpdate = "Ganada";
}else{
  $estadoUpdate = "Lost";
}
$progressWidth = ($currentStep / 4) * 100;  // Calcula el progreso en base al número de pasos

// Inserta o actualiza los datos en la tabla
$query = "UPDATE sales SET  Status = '$estadoUpdate',current_step = '$currentStep', progress_width = '$progressWidth' WHERE id = $idContact ";
mysqli_query($conexion, $query);

// Cierra la conexión
//mysqli_close($connection);



 
 


// Guardar el estado seleccionado en la base de datos u otro sistema de almacenamiento

// Retornar el índice del estado seleccionado
switch ($estado) {
  case '1':
    echo '1';
    break;
  case '2':
    echo '2';
    break;
  case '3':
    echo '3';
    break;
  case '4':
    echo '4';
    break;
  case '5':
    echo '5';
    break;
  default:
    echo '0';
    break;
}
?>
