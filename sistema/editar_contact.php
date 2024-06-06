<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    $idcontact = $_REQUEST['id'];
    $nombre = $_POST['nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $email = $_POST['email'];
    $telefono_fijo = $_POST['telefono_fijo'];
    $celular = $_POST['celular'];


    $observaciones =  $_POST['Observaciones'];
    $nivel = $_POST['nivel_interes'];

    if (!empty($_POST['nombre']) || !empty($_POST['segundo_nombre']) || !empty($_POST['apellido_paterno']) || !empty($_POST['apellido_materno']) || !empty($_POST['email']) || !empty($_POST['telefono_fijo']) || !empty($_POST['celular'])) {

        $query_update = mysqli_query($conexion, "UPDATE contacts SET observaciones='$observaciones', `Nivel de interés`='$nivel', nombre='$nombre', segundo_nombre = '$segundo_nombre', apellido_paterno = '$apellido_paterno', apellido_materno = '$apellido_materno', email = '$email', telefono_fijo = '$telefono_fijo', celular = '$celular' WHERE idContacts = '$idcontact'");
        if($query_update){
                $alert = '<div class="alert alert-primary" role="alert">
                        Contacts actualizada con exito
                    </div>';
            
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                              Error al insertar
                      </div>';
        }
        //mysqli_close($conexion);
    } else {
        echo "error";
    }
    //mysqli_close($conexion);
}

  $idcontact = $_REQUEST['id'];
  $sql = mysqli_query($conexion, "SELECT * FROM `contacts` c  WHERE c.idContacts = $idcontact");
  //mysqli_close($conexion);
  $result_sql = mysqli_num_rows($sql);
  
    while ($data = mysqli_fetch_array($sql)) {
        $idCod = $data['idContacts'];
        $nombre = $data['nombre'];
        $segundo_nombre = $data['segundo_nombre'];
        $apellido_paterno = $data['apellido_paterno'];
        $apellido_materno = $data['apellido_materno'];
        $email = $data['email'];
        $observaciones =  $data['observaciones'];
        $nivel1 = $data['Nivel de interés'];
        $telefono_fijo = $data['telefono_fijo'];
        $celular = $data['celular'];   
  }
  ?>

 

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_contact.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Content Row -->
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <form action="" method="post" autocomplete="off"  enctype="multipart/form-data">
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" value="<?php echo $nombre ?>"  class="form-control"  >
                            </div>
                            <div class="form-group">
                                <label for="segundo_nombre">Segundo Nombre</label>
                                <input type="text" placeholder="Ingrese Segundo Nombre" name="segundo_nombre" id="segundo_nombre" class="form-control" value="<?php echo $segundo_nombre ?>"  >
                            </div>
                            <div class="form-group">
                                <label for="apellido_paterno">Apellido Paterno</label>
                                <input type="text" placeholder="Ingrese Apellido Paterno" name="apellido_paterno" id="apellido_paterno" class="form-control" value="<?php echo $apellido_paterno ?>" >
                            </div>
                            <div class="form-group">
                                <label for="apellido_materno">Apellido Materno</label>
                                <input type="text" placeholder="Ingrese Apellido Materno" name="apellido_materno" id="apellido_materno" class="form-control" value="<?php echo $apellido_materno ?>" >
                            </div>

                            <div class="form-group">
                                <label for="nivel_interes">Nivel de interés</label>
                                <select name="nivel_interes" id="nivel_interes" class="form-control">
                                    <option selected value="<?php  echo $nivel1 ?>"><?php  echo $nivel1 ?></option>
                                    <option value="Bajo">Bajo</option>
                                    <option value="Medio">Medio</option>
                                    <option value="Alto">Alto</option>
                                    <!-- Agrega más opciones aquí si es necesario -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Observaciones">Observaciones</label>
                                <input type="Observaciones"  value="<?php  echo $observaciones ?>" placeholder="Ingrese Observaciones" name="Observaciones" id="Observaciones" class="form-control"  >
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" placeholder="Ingrese Email" name="email" id="email" class="form-control" value="<?php echo $email ?>" >
                            </div>
                            <div class="form-group">
                                <label for="telefono_fijo">Teléfono Fijo</label>
                                <input type="text" placeholder="Ingrese Teléfono Fijo" name="telefono_fijo" id="telefono_fijo" class="form-control" value="<?php echo $telefono_fijo ?>" >
                            </div>
                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="number" placeholder="Ingrese Celular" name="celular" id="celular" class="form-control" value="<?php echo $celular ?>" >
                            </div>
                            
                <input type="submit" value="Guardar Contact" class="btn btn-primary">
            </form>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>

 