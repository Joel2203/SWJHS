<?php
include "includes/header.php";
include "../conexion.php";


if (!empty($_POST)) {
$idproveedor = $_REQUEST['id'];
$ruc = $_POST['rucNumero'];
$razonsocial = $_POST['razonsocial'];
$direccion = $_POST['direccion'];
$distrito = $_POST['distrito'];
$provincia = $_POST['provincia'];
$pais = $_POST['pais'];
$departamento = $_POST['departamento'];

$url = $_POST['url'];
$cantidad_empleados = $_POST['cantidad_empleados'];
$origen_cliente = $_POST['origen_cliente'];
$cargo = $_POST['cargo'];
$COD_idContact = $_POST['COD_idContact'];

$sql_verificar = "SELECT * FROM customers WHERE RUC = '$ruc' AND idCliente <> $idproveedor";

$query_verificar = mysqli_query($conexion, $sql_verificar);

 if (mysqli_num_rows($query_verificar) > 0) {
    $alert = '<div class="alert alert-danger" role="alert">
                Ruc ya existe en la base de datos
              </div>';
}else{

      $query = "UPDATE customers
      SET RUC = '$ruc',
          Company = '$razonsocial',
          Direccion = '$direccion',
          Distrito = '$distrito',
          Provincia = '$provincia',
          Pais = '$pais',
          Departamento = '$departamento',
          URL = '$url',
          Cantidad_Empleados = '$cantidad_empleados',
          OrigenCliente = '$origen_cliente',
          Cargo = '$cargo',
          COD_idcontacto = '$COD_idContact'
      WHERE idCliente = '$idproveedor'";
      $query_insert = mysqli_query($conexion, $query);
      if ($query_insert) {

        $alert ='
        <div class="alert alert-success" role="alert">
        Editado correctamente
        </div>
        ';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
      }
      if ($query_insert) {
      
      } else {
        echo "Error al registrar el producto";
      }
      
}     
} 
// Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_proveedor.php");
  mysqli_close($conexion);
}
$idproveedor = $_REQUEST['id'];
$cod_idUsuario = $_SESSION['idUser'];

$sql = mysqli_query($conexion, "SELECT * FROM customers INNER JOIN contacts AS C ON C.idContacts = customers.COD_idcontacto WHERE idCliente = $idproveedor");
//mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: prueba1.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idCod = $data['idCliente'];
    $company = $data['Company'];
    $ruc = $data['RUC'];
    $url = $data['URL'];
    $direccion = $data['Direccion'];
    $distrito = $data['Distrito'];
    $provincia = $data['Provincia'];
    $departamento = $data['Departamento'];
    $pais = $data['Pais'];
    $cargo = $data['Cargo'];
    $cantidadEmpleados = $data['Cantidad_Empleados'];
    $origenCliente = $data['OrigenCliente'];
    $idContact = $data['idContacts'];
    $nombre = $data['nombre'];
    $segundo_nombre = $data['segundo_nombre'];
    $apellido_paterno = $data['apellido_paterno'];
    $apellido_materno = $data['apellido_materno'];
  }
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/jquery.min.js"></script>
<!-- Begin Page Content -->

<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 m-auto">
      <?php echo isset($alert) ? $alert : ''; ?>
      <form class="" action="" method="post">
        <input type="hidden" name="id" value="<?php echo $idproveedor; ?>">
        
        <div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="ruc">RUC</label>
            <input type="text" name="rucNumero" id="rucNumero" placeholder="Ingrese RUC" class="form-control" pattern="[0-9]{11}" title="Por favor, ingrese un número de RUC válido de 11 dígitos" value="<?php echo $ruc; ?>" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="ruc">Consulta por SUNAT</label>
            <br>
            <button type="button" class="btn btn-primary" id="pruebaruc2">Buscar</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="company">Razón social</label>
            <input type="text" name="razonsocial" id="razonsocial" placeholder="Ingrese nombre de la empresa" class="form-control" value="<?php echo $company; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" placeholder="Ingrese dirección" class="form-control" value="<?php echo $direccion; ?>" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="distrito">Distrito</label>
            <input type="text" name="distrito" id="distrito" placeholder="Ingrese distrito" class="form-control" value="<?php echo $distrito; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="provincia">Provincia</label>
            <input type="text" name="provincia" id="provincia" placeholder="Ingrese provincia" class="form-control" value="<?php echo $provincia; ?>" readonly>
        </div>
    </div>
    <div class="col-md-4">
            <div class="form-group">
              <label for="pais">País</label>
              <select name="pais" id="pais" class="form-control">
                <option selected value="<?php echo $pais?>"><?php echo $pais?></option>
                <option value="Perú">Perú</option>
                <option value="Argentina">Argentina</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Brasil">Brasil</option>
                <option value="Chile">Chile</option>
                <option value="Colombia">Colombia</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Ecuador">Ecuador</option>
                <option value="El Salvador">El Salvador</option>
                <option value="España">España</option>
                <option value="Estados Unidos">Estados Unidos</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Honduras">Honduras</option>
                <option value="México">México</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Panamá">Panamá</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="República Dominicana">República Dominicana</option>
                <option value="Uruguay">Uruguay</option>
                <option value="Venezuela">Venezuela</option>
              </select>
            </div>
            <div class="form-group">
              <label for="departamento">Departamento</label>
              <input type="text" name="departamento" value="<?php echo $departamento?>" id="departamento" placeholder="Ingrese departamento" class="form-control" readonly>
                  </div>
              </div>                   
          </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="url">URL</label>
              <input type="text" name="url" id="url" placeholder="Ingrese URL"  value="<?php echo $url?>" class="form-control">
            </div>
          </div>
          
          <div class="col-md-6">
          <div class="form-group">
            <label for="cantidad_empleados">Cantidad de Empleados</label>
            <input type="number" name="cantidad_empleados" id="cantidad_empleados" value="<?php echo $cantidadEmpleados?>" placeholder="Ingrese cantidad de empleados" class="form-control">
          </div>
        </div>
          
        </div>

    
        
      <div class="row">
        <div class="col-md-6">
        <div class="form-group">
          <label for="origen_cliente">Origen del Cliente</label>
          
          <select name="origen_cliente" id="origen_cliente" class="form-control">
            <option selected value="<?php echo $origenCliente?>"><?php echo $origenCliente?></option>
            <option value="Campaña">Campaña</option>
            <option value="Referido de marca">Referido de marca</option>
            <option value="Generación Propia">Generación Propia</option>
          </select>
        </div>
          
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="cargo">Cargo</label>
            <input type="text" name="cargo" id="cargo" value="<?php echo $cargo?>" placeholder="Ingrese cargo" class="form-control">
          </div>
        </div>
      </div>

        

    
      <div class="row">

        <div class="col-md-12">
        <div class="form-group">
          <label for="recurrente">Contacto</label>
          <?php
              $id = $_SESSION['idUser'];
              $query_customer = mysqli_query($conexion, "SELECT * FROM contacts where COD_idusuario =$id");
              $resultado_customer = mysqli_num_rows($query_customer);
              //mysqli_close($conexion);
              
              ?>
              
              <select name="COD_idContact" id="COD_idContact" class="form-control"> 
              <option selected value="<?php echo $idContact; ?>"><?php echo $nombre ?> <?php echo $segundo_nombre ?> <?php echo $apellido_paterno ?> <?php echo $apellido_materno ?></option>
              <?php
              if ($resultado_customer > 0) {
                  while ($customer = mysqli_fetch_array($query_customer)) {
              ?>
                      <option value="<?php echo $customer["idContacts"]; ?>"><?php echo $customer["nombre"] ?> <?php echo $customer["segundo_nombre"] ?> <?php echo $customer["apellido_paterno"] ?> <?php echo $customer["apellido_materno"] ?></option>
              <?php

                  }
              }
              ?>
              
              </select>
                        
        </div>
        <div id="info"></div>
      </div>

      </div>

        <input type="submit" value="Editar Cuenta" class="btn btn-primary">
        <a href="prueba1.php" class="btn btn-danger">Regresar</a>
      </form>
    </div>
  </div>
  </div>

</div>
</div>
<!-- /.container-fluid -->

<div id="result"></div>

<script>

$("#pruebaruc2").click(function(){

  var ruc=$("#rucNumero").val();
  console.log(ruc);
  
  $.ajax({
  type: "POST",
  url: "verificar_ruc_update.php",
  data: {
    ruc: ruc,
    id: <?php echo $_REQUEST['id']; ?>
  },
  dataType: "text",
  success: function(response) {
    console.log(response);
    if (response.charAt(0) == 1) {
      // Primera condición cumplida, invocar segunda llamada AJAX
        $.ajax({           
            type:"POST",
            url: "./component/consultar-ruc-ajax.php",
            data: 'ruc='+ruc,
            dataType: 'json',
            success: function(data) {
                if(data==1)
                {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El RUC tiene que tener 11 digitos!'
                    })
                }
                if(data==2)
                {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El RUC tiene que ser 10 o 20!'
                    })
                }
                else{
                    console.log(data);
                  
                    $("#razonsocial").val(data.nombre);
                    $("#direccion").val(data.direccion);
                    $("#distrito").val(data.distrito);
                    $("#provincia").val(data.provincia);
                    //$("#pais").val(data.pais);
                    $("#departamento").val(data.departamento);

                }
            }
        });
    } else {
      // La primera condición no se cumple, hacer algo más
      Swal.fire(
          'RUC duplicado',
    'El RUC ya existe en la base de datos',
    'question'
    );
     
    }
  },
  error: function(xhr, status, error) {
    // Manejar el error de la primera llamada AJAX
    console.log(error);
  }
});

});

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="js/jquery.min.js"></script>



</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>