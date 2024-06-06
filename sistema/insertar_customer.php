<?php

include "../conexion.php";

$ruc = $_POST['ruc'];
$razonsocial = $_POST['razonsocial'];
$direccion = $_POST['direccion'];
$distrito = $_POST['distrito'];
$provincia = $_POST['provincia'];
$pais = $_POST['pais'];
$departamento = $_POST['departamento'];
$idContact = $_POST['idContact'];
$url = $_POST['url'];
$cargo = $_POST['cargo'];
$cantidad_empleados = $_POST['cantidad_empleados'];
$origen_cliente = $_POST['origen_cliente'];

session_start();
$COD_id =  $_SESSION['idUser'];
 
$query_verificar = mysqli_query($conexion, "SELECT * FROM customers WHERE Company = '$razonsocial'");
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

      $query = "INSERT INTO customers (RUC, Company, Direccion, Distrito, Provincia, Pais, Departamento, 	COD_idcontacto, URL, Cargo, Cantidad_Empleados, OrigenCliente, COD_idusuario) 
      VALUES ('$ruc', '$razonsocial', '$direccion', '$distrito', '$provincia', '$pais', '$departamento', '$idContact', '$url', '$cargo', '$cantidad_empleados', '$origen_cliente',$COD_id)";

 
      $query_insert = mysqli_query($conexion, $query);

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


 