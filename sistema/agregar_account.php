<?php
 
include_once "includes/header.php";
include "../conexion.php";


?>

<script src="js/jquery.min.js"></script>
<script>
  function newsearch(){
    var rucNumero = document.getElementById('rucNumero');
    var razonsocial = document.getElementById('razonsocial');
    var direccion = document.getElementById('direccion');
    var distrito = document.getElementById('distrito');
    var provincia = document.getElementById('provincia');
    var botonBuscar = document.getElementById('pruebaruc2');

    botonBuscar.disabled = false;
    rucNumero.disabled = false;
    rucNumero.value = '';  // Borra el valor del campo
    razonsocial.value = "";  // Establece el contenido
    direccion.value = "";  // Establece el contenido
    distrito.value = "";  // Establece el contenido
    provincia.value = ""; 
  }
</script>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <form action="" method="post">
                <?php echo isset($alert) ? $alert : ''; ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="ruc">RUC</label>
                            <input type="number" name="rucNumero" id="rucNumero" placeholder="Ingrese RUC" class="form-control" pattern="[0-9]{11}" title="Por favor, ingrese un número de RUC válido de 11 dígitos" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ruc">Consulta por SUNAT</label>
                            <br>
                            <button type="button" class="btn btn-primary"  id="pruebaruc2">Buscar</button>
                            <button type="button" class="btn btn-primary"  onclick="newsearch()">Nueva búsqueda</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="company">Razon social</label>
                            <input type="text" name="razonsocial" id="razonsocial" placeholder="Ingrese nombre de la empresa" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Direccion</label>
                            <input type="text" name="direccion" id="direccion" placeholder="Ingrese dirección" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="distrito">Distrito</label>
                            <input type="text" name="distrito" id="distrito" placeholder="Ingrese distrito" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="provincia">Provincia</label>
                            <input type="text" name="provincia" id="provincia" placeholder="Ingrese provincia" class="form-control" readonly>
                        </div>

                    </div>
                    <div class="col-md-4">
                          <div class="form-group">
                            <label for="pais">País</label>
                            <select name="pais" id="pais" class="form-control">
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
                            <input type="text" name="departamento" id="departamento" placeholder="Ingrese departamento" class="form-control" readonly>
                                </div>
                            </div>                   
                        </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" name="url" id="url" placeholder="Ingrese URL" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                          <label for="cantidad_empleados">Cantidad de Empleados</label>
                          <input type="number" name="cantidad_empleados" id="cantidad_empleados" placeholder="Ingrese cantidad de empleados" class="form-control">
                        </div>
                      </div>
                        
                      </div>

                   

                    <div class="row">
                      <div class="col-md-6">
                      <div class="form-group">
                        <label for="origen_cliente">Origen del Cliente</label>
                        <select name="origen_cliente" id="origen_cliente" class="form-control">
                          <option value="Campaña">Campaña</option>
                          <option value="Referido de marca">Referido de marca</option>
                          <option value="Generación Propia">Generación Propia</option>
                        </select>
                      </div>
                        
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="cargo">Cargo</label>
                          <input type="text" name="cargo" id="cargo" placeholder="Ingrese cargo" class="form-control" maxlength="20">
                        </div>
                      </div>
                    </div>

                    <div class="row">
 
                      <div class="col-md-12">
 
                       <div class="form-group">
                        <label for="recurrente">Contacto</label>
                        <?php
                        $id = $_SESSION['idUser'];
                     
                        if ($_SESSION['rol'] == 1) {
                            $query_customer = mysqli_query($conexion, "SELECT * FROM contacts");
                        } else {
                            $query_customer = mysqli_query($conexion, "SELECT * FROM contacts where COD_idusuario =$id");
                        }

                        $resultado_customer = mysqli_num_rows($query_customer);
                        //mysqli_close($conexion);

                        ?>
                         <div id="resultContact"></div>
                        <input type="text" id="searchContactInput" class="form-control" placeholder="Search contact...">                       

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                          $(document).ready(function() {
                              $('#searchContactInput').on('keyup', function() {
                                  var inputValue = $(this).val();
                                  var id = <?php echo $id; ?>;

                                  $.ajax({
                                      type: 'POST',
                                      url: 'searchContact.php', // Asegúrate de cambiar esto a la ruta correcta de tu script PHP
                                      data: { inputValue: inputValue, id:id },
                                      success: function(response) {
                                          // Maneja la respuesta del servidor aquí (si es necesario)
                                          console.log('Respuesta del servidor:', response);
                                          $('#resultContact').html(response);
                                      },
                                      error: function(xhr, status, error) {
                                          // Maneja los errores aquí
                                          console.error('Error al enviar AJAX:', error);
                                      }
                                  });
                              });
                          });

                        </script>

                        

        
                       
                    </div>
                     
                    
                              <div id="info"></div>
                    
                    </div>

                    </div>

                
                <input type="button" value="Insertar"  id="pruebaruc1" class="btn btn-primary">
                <a href="prueba1.php" class="btn btn-danger">Regresar</a>
            </form>
        </div>
    </div>
</div>

<div id="result"></div>

 

<script>



const rucNumeroInput = document.getElementById('rucNumero');
const botonInsertar = document.getElementById('pruebaruc1');
const idContact = document.getElementById('COD_idContact') || 0;
 
 
$("#pruebaruc2").click(function(){

  var ruc=$("#rucNumero").val();
  
  //console.log(ruc);
  
  $.ajax({
  type: "POST",
  url: "verificar_ruc.php",
  data: {
    ruc: ruc
  },
  dataType: "text",
  success: function(response) {
     
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
                    });
                    document.getElementById('pruebaruc1').disabled = true;
                    document.getElementById('razonsocial').value = '';
                }
                if(data==2)
                {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El RUC tiene que ser 10 o 20!'
                    });
                    document.getElementById('pruebaruc1').disabled = true;
                    document.getElementById('razonsocial').value = '';
                }
                if (data.error === "RUC invalido") 
                {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El RUC no se encontró'
                    });
                    document.getElementById('pruebaruc1').disabled = true;
                    document.getElementById('razonsocial').value = '';
                }
                else{
                    // Validar que el RUC contenga solo números
                    const rucPattern = /^[0-9]+$/;

                    if (!ruc.match(rucPattern)) {
                      Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "El RUC debe contener solo números",
                      });
                      return;
                    }
                    document.getElementById('pruebaruc1').disabled = false;

                  
                    $("#razonsocial").val(data.nombre);
                    $("#direccion").val(data.direccion);
                    $("#distrito").val(data.distrito);
                    $("#provincia").val(data.provincia);
                    //$("#pais").val(data.pais);
                    $("#departamento").val(data.departamento);

                    var rucNumero = document.getElementById('rucNumero');
                    var botonBuscar = document.getElementById('pruebaruc2');
                    rucNumero.disabled = true;
                    botonBuscar.disabled = true;

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
      document.getElementById('pruebaruc1').disabled = true;
     
    }
  },
  error: function(xhr, status, error) {
    // Manejar el error de la primera llamada AJAX
    console.log(error);
  }
});


});


$("#pruebaruc1").click(function(){

  var COD_idusuario = "<?php echo $_SESSION['idUser']; ?>";
  //console.log("CODIGO: "+COD_idusuario)

    var ruc=$("#rucNumero").val();
    var razonsocial = $("#razonsocial").val();
    var direccion = $("#direccion").val();
    var distrito = $("#distrito").val();
    var provincia = $("#provincia").val();
    var pais = $("#pais").val();
    var departamento = $("#departamento").val();

    var idContact = $("#COD_idContact").val() || 0;
    var searchContactInput = $("#searchContactInput").val();

    var url = $("#url").val();
   
    var cargo = $("#cargo").val();
    var cantidad_empleados = $("#cantidad_empleados").val();
    var origen_cliente = $("#origen_cliente").val();


    console.log("Razón social:", razonsocial);
    console.log("Dirección:", direccion);
    console.log("Distrito:", distrito);
    console.log("Provincia:", provincia);
    console.log("País:", pais);
    console.log("Departamento:", departamento);
    console.log("URL:", url);

 
    console.log("Cargo:", cargo);
    console.log("Cantidad de empleados:", cantidad_empleados);
    console.log("Cod_idContact:", idContact);
    console.log("origen_cliente:", origen_cliente);

    if (ruc === "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El RUC es requerido",
      });
      return;
    }

    // Validar que el RUC contenga solo números
    const rucPattern = /^[0-9]+$/;

    if (!ruc.match(rucPattern)) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El RUC debe contener solo números",
      });
      return;
    }
  
    if (razonsocial === "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "La razón social es requerido",
      });
      return;
    }

    // Validar campo Cargo
    if (cargo === "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Por favor, ingrese el cargo.",
      });
      return;
    }

    // Validar campo Cantidad de Empleados
    if (cantidad_empleados === "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Por favor, ingrese la cantidad de empleados.",
      });
      return;
    }

    // Validar campo Origen del Cliente
    if (origen_cliente === "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Por favor, ingrese el origen del cliente.",
      });
      return;
    }

    // Validar tamaño máximo de los campos
    if (url.length > 200) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El campo URL excede el tamaño máximo permitido (100 caracteres).",
      });
      return;
    }


    if (cargo.length > 50) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El campo Cargo excede el tamaño máximo permitido (50 caracteres).",
      });
      return;
    }

    if (cantidad_empleados.length > 10) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El campo Cantidad de Empleados excede el tamaño máximo permitido (10 caracteres).",
      });
      return;
    }

    if (origen_cliente.length > 50) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El campo Origen del Cliente excede el tamaño máximo permitido (50 caracteres).",
      });
      return;
    }

    
    if (idContact === 0) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El contacto es requerido",
      });
      return;
    }

    if (isNaN(cantidad_empleados) || parseInt(cantidad_empleados) > 10000) {
      Swal.fire({
          icon: "error",
          title: "Error",
          text: "La cantidad de empleados debe ser un número válido y no puede exceder 10000",
      });
      return;
    }

      if (parseInt(cantidad_empleados) === 0) {
          Swal.fire({
              icon: "error",
              title: "Error",
              text: "El contacto es requerido",
          });
          return;
    }
        
    
 
    var data = {
      ruc: ruc,
      razonsocial: razonsocial,
      direccion: direccion,
      distrito: distrito,
      provincia: provincia,
      pais: pais,
      departamento: departamento,
      idContact: idContact,
      url: url,
      cargo: cargo,
      cantidad_empleados: cantidad_empleados,
      origen_cliente: origen_cliente
    };

     
    $.ajax({
      type: "POST",
      url: "insertar_customer.php",
      data: data,
      success: function(response) {
        $('#result').html(response);
         
        /*
        let mensaje = new SpeechSynthesisUtterance();
            mensaje.text = response;
            speechSynthesis.speak(mensaje);
          */  
        setTimeout(function() {
        window.location.href = "prueba1.php";
        }, 3000);
         
        
      },
   
    });
    
 
});
</script>

<!-- /.container-fluid -->

</div>

<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>