<?php include_once "includes/header.php"; ?>

 
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" type="text/css" href="css/fullcalendar.min.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/home.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>
<body>

<style>
  @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Montserrat', sans-serif;
}

</style>


<?php
//include('config.php');

include "../conexion.php";

  if ($_SESSION['rol'] == 1) {
    $SqlEventos = "SELECT e.*,s.Oportunidad as opo FROM `eventos1` as e INNER JOIN sales as s on e.COD_idsales = s.id";
  } else {
    $SqlEventos = "SELECT e.*,s.Oportunidad as opo FROM `eventos1` as e INNER JOIN sales as s on e.COD_idsales = s.id WHERE e.COD_idusuario = " . $_SESSION['idUser'];
  }

  $resulEventos = mysqli_query($conexion, $SqlEventos);

?>
<div class="mt-5"></div>

<div class="container">
  <div class="row">
    <div class="col msjs">
      <?php
        include('msjs.php');
      ?>
    </div>
  </div>

<div class="row">
  <div class="col-md-12 mb-3">
  <h3 class="text-center" id="title">Nueva actividad</h3>
  <a href="lista_tareas_view.php" class="btn btn-primary">Ver detalle</a>
  </div>
</div>
</div>



<div id="calendar"></div>


<?php  
 
include('modalNuevoEvento.php');
include('modalUpdateEvento.php');
 
?>

 

<script src ="js/jquery-3.0.0.min.js"> </script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript" src="js/moment.min.js"></script>	
<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<script src='locales/es.js'></script>

<script type="text/javascript">
$(document).ready(function() {
  $("#calendar").fullCalendar({
    header: {
      left: "prev,next today",
      center: "title",
      right: "month,agendaWeek,agendaDay"
    },

    locale: 'es',

    defaultView: "month",
    navLinks: true, 
    editable: true,
    eventLimit: true, 
    selectable: true,
    selectHelper: false,

//Nuevo Evento
  select: function(start, end){
    $("#exampleModal").modal();
      $("input[name=fecha_inicio]").val(start.format('DD-MM-YYYY'));
       
      var valorFechaFin = end.format("DD-MM-YYYY");
      var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(0, 'days').format('DD-MM-YYYY'); //Le resto 1 dia
      $('input[name=fecha_fin').val(F_final);  


      var horaActual = moment().format('HH:mm'); // Obtiene la hora actual en formato HH:mm

      $("input[name=hora_inicio]").val(horaActual);
      var horaFinal = moment(horaActual, 'HH:mm').add(1, 'hour').format('HH:mm');

      $("input[name=hora_inicio]").val(horaActual);
      $("input[name=hora_final]").val(horaFinal);

    },
      
    events: [
  <?php
   while($dataEvento = mysqli_fetch_array($resulEventos)){ ?>
      {
      _id: '<?php echo $dataEvento['id']; ?>',
      asignado: '<?php echo $dataEvento['asignado']; ?>',
      title: '<?php echo $dataEvento['asunto']; ?>',
      start: '<?php echo $dataEvento['fecha_inicio']; ?>',
      time_start: '<?php echo $dataEvento['h_inicio']; ?>',
      end: '<?php echo $dataEvento['fecha_fin']; ?>',
      time_end: '<?php echo $dataEvento['h_fin']; ?>',
      ubicacion: '<?php echo $dataEvento['ubicacion']; ?>',
      mostrar_hora: '<?php echo $dataEvento['mostrar_hora']; ?>',
      descripcion: '<?php echo $dataEvento['descripcion']; ?>',
      COD_idrol: '<?php echo $dataEvento['COD_idrol']; ?>',
      color: '<?php echo $dataEvento['color_evento']; ?>',
      COD_idusuario: '<?php echo $dataEvento['COD_idusuario']; ?>',
      COD_idsales: '<?php echo $dataEvento['COD_idsales']; ?>'
      
      },
    <?php } ?>
],



//Eliminar Evento
eventRender: function(event, element) {
    element
      .find(".fc-content")
      .prepend("<span id='btnCerrar'; class='closeon material-icons'>&#xe5cd;</span>");
    
    //Eliminar evento
    element.find(".closeon").on("click", function() {

  var pregunta = confirm("Deseas Borrar este Evento?");   
  if (pregunta) {

    $("#calendar").fullCalendar("removeEvents", event._id);

     $.ajax({
            type: "POST",
            url: 'deleteEvento.php',
            data: {id:event._id},
            success: function(datos)
            {
              $(".alert-danger").show();

              setTimeout(function () {
                $(".alert-danger").slideUp(500);
              }, 3000); 

            }
        });
      }
    });
  },

 
//Moviendo Evento Drag - Drop
eventDrop: function (event, delta) {
  var idEvento = event._id;
  var start = (event.start.format('DD-MM-YYYY'));
  var end = (event.end.format("DD-MM-YYYY"));

  console.log(start)

    $.ajax({
        url: 'drag_drop_evento.php',
        data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
        type: "POST",
        success: function (response) {
          $("#respuesta").html(response);
        }
    });
},
 
//Modificar Evento del Calendario 
eventClick:function(event){
    var idEvento = event._id;
	$('input[name=idEvento').val(idEvento);
  $('input[name=asignado]').val(event.asignado);
  $('input[name=asuntoUpdate]').val(event.title);
  $('input[name=fecha_inicio]').val(event.start.format('YYYY-MM-DD'));
    $('input[name=hora_inicio]').val(event.time_start);
    $('input[name=fecha_fin]').val(event.end.format('YYYY-MM-DD'));
    $('input[name=hora_final]').val(event.time_end);
    $('input[name=ubicacionUpdate]').val(event.ubicacion);
    $('#mostrar_horaUpdate').val(event.mostrar_hora);
    $('textarea[name=descripcionUpdate]').val(event.descripcion);
   // $('input[name=oportunidad]').val(event.oportunidad);
    $("#modalUpdateEvento").modal();
  },


  });


//Oculta mensajes de Notificacion
  setTimeout(function () {
    $(".alert").slideUp(300);
  }, 3000); 


});

</script>


</body>
</html>


</div>


 