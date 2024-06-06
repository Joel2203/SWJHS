<?php

include "../conexion.php";

$ruc = $_POST['ruc'];
$razonsocial = $_POST['razonsocial'];
$direccion = $_POST['direccion'];
$distrito = $_POST['distrito'];
$provincia = $_POST['provincia'];
$pais = $_POST['pais'];
$departamento = $_POST['departamento'];

$url = $_POST['url'];
$tipo_contacto = $_POST['tipo_contacto'];
$contact_name = $_POST['contact_name'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'];
$cargo = $_POST['cargo'];
$cantidad_empleados = $_POST['cantidad_empleados'];
$origen_cliente = $_POST['origen_cliente'];
session_start();
$COD_id = $_SESSION['idUser'];
$sql = "SELECT * FROM account WHERE cuenta = '$razonsocial' and RUC = '$ruc' and COD_idusuario = " . $COD_id;
$query_verificar = mysqli_query($conexion,$sql );
$data = mysqli_fetch_array($query_verificar);

if (mysqli_num_rows($query_verificar) > 0) {
    echo "
    <script>
    Swal.fire(
    'RUC duplicado',
    'El RUC ya existe en la base de datos',
    'question'
    );
    </script>
    ";
}else{
      
      $query = "INSERT INTO account (RUC, Company, direccion, distrito, provincia, pais, departamento, url, tipo_contacto, contact_name, apellido_paterno, apellido_materno, cargo, cantidad_empleados, OrigenCliente, COD_idusuario) 
      VALUES ($ruc,'$razonsocial', '$direccion', '$distrito', '$provincia', '$pais', '$departamento', '$url', '$tipo_contacto', '$contact_name', '$apellido_paterno', '$apellido_materno', '$cargo', '$cantidad_empleados', '$origen_cliente','$COD_id')";
      $query_insert = mysqli_query($conexion, $query);

      echo $query;

      $last_inserted_id = mysqli_insert_id($conexion);
      $queryType ="INSERT INTO typecustomer (type, COD_idCliente) VALUES (1,'$last_inserted_id')";
      mysqli_query($conexion, $queryType);

      if ($query_insert) {
        $alert ="
        <script>
        Swal.fire(
          'Customer registrado correctamente',
          '',
          'success'
        );
        </script>
        ";;
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
      }
      if ($query_insert) {
        echo $alert ;
      } else {
        echo "Error al registrar el producto";
      }
}      
?>


 