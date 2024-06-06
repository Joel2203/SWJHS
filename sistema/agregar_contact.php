<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    
    $nombre = $_POST['nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
 
    $nivelInteres = $_POST['nivel_interes'];
    $observaciones = $_POST['Observaciones'];
    $email = $_POST['email'];
    $telefono_fijo = $_POST['telefono_fijo'];
    $celular = $_POST['celular'];

    $nombre_completo = $nombre . ' ' . $segundo_nombre . ' ' . $apellido_paterno . ' ' . $apellido_materno;
    $nombre_completo = strtolower($nombre_completo);

    $COD_id = $_SESSION['idUser'];

    $sql = mysqli_query($conexion, "SELECT CONCAT(LOWER(nombre), ' ', LOWER(segundo_nombre), ' ', LOWER(apellido_paterno), ' ', LOWER(apellido_materno)) as nombre_completo FROM contacts WHERE nombre = '$nombre' AND segundo_nombre = '$segundo_nombre' AND apellido_paterno = '$apellido_paterno' AND apellido_materno = '$apellido_materno'");
    $data = mysqli_fetch_array($sql);

    $i = 0;
    
    if($data){

        if ($data['nombre_completo'] == $nombre_completo) {
            $alert = '<div class="alert alert-danger" role="alert">
            Ese contacto ya existe en la base de datos
            </div>';         
        }
        $i++;
    }
    
    $sql = mysqli_query($conexion, "SELECT email FROM contacts WHERE email = '$email'");
    $data = mysqli_fetch_array($sql);

    if ($data){
        if ($data['email'] == $email) {
            $alert = '<div class="alert alert-danger" role="alert">
            Ese email ya existe en la base de datos
            </div>';
        }
        $i++;
    }    

    $sql = mysqli_query($conexion, "SELECT celular FROM contacts WHERE celular = '$celular'");
    $data = mysqli_fetch_array($sql);

    if ($data){
        if ($data['celular'] == $celular) {
            $alert = '<div class="alert alert-danger" role="alert">
            Ese celular ya existe en la base de datos
            </div>';
        }
        $i++;
    }

    if($i == 0){
        if  (!empty($_POST['nombre']) || !empty($_POST['segundo_nombre']) || !empty($_POST['email']) || !empty($_POST['telefono_fijo']) || !empty($_POST['celular'])) {
            $sql = "iNSERT INTO contacts(nombre, segundo_nombre, apellido_paterno, apellido_materno, `Nivel de interés`, observaciones, email, telefono_fijo, celular,  COD_idusuario) VALUES ('$nombre', '$segundo_nombre', '$apellido_paterno', '$apellido_materno','$nivelInteres','$observaciones','$email',   '$telefono_fijo', '$celular', '$COD_id')";
            $query_insert = mysqli_query($conexion, $sql);
            if ($query_insert) {
                    $alert = '<div class="alert alert-primary" role="alert">
                            Contacts insertada con exito
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
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" required  maxlength="30">
                            </div>
                            <div class="form-group">
                                <label for="segundo_nombre">Segundo Nombre</label>
                                <input type="text" placeholder="Ingrese Segundo Nombre" name="segundo_nombre" id="segundo_nombre" class="form-control"  maxlength="30">
                            </div>
                            <div class="form-group">
                                <label for="apellido_paterno">Apellido Paterno</label>
                                <input type="text" placeholder="Ingrese Apellido Paterno" name="apellido_paterno" id="apellido_paterno" class="form-control" required  maxlength="30">
                            </div>
                            <div class="form-group">
                                <label for="apellido_materno">Apellido Materno</label>
                                <input type="text" placeholder="Ingrese Apellido Materno" name="apellido_materno" id="apellido_materno" class="form-control" required maxlength="30">
                            </div>
                            <div class="form-group">
                                <label for="nivel_interes">Nivel de interés</label>
                                <select name="nivel_interes" id="nivel_interes" class="form-control" required>
                                    <option value="Bajo">Bajo</option>
                                    <option value="Medio">Medio</option>
                                    <option value="Alto">Alto</option>
                                    <!-- Agrega más opciones aquí si es necesario -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Observaciones">Observaciones</label>
                                <input type="text" placeholder="Ingrese Observaciones" name="Observaciones" id="Observaciones" class="form-control" required maxlength="250">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" placeholder="Ingrese Email" name="email" id="email" class="form-control" required maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="telefono_fijo">Teléfono Fijo</label>
                                <input type="text" placeholder="Ingrese Teléfono Fijo" name="telefono_fijo" id="telefono_fijo" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="number" placeholder="Ingrese Celular" name="celular" id="celular" class="form-control" required>
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

 