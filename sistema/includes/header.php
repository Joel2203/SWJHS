<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}
include "includes/functions.php";
include "../conexion.php";
// datos Empresa
$dni = '';
$nombre_empresa = '';
$razonSocial = '';
$emailEmpresa = '';
$telEmpresa = '';
$dirEmpresa = '';
$igv = '';

$query_empresa = mysqli_query($conexion, "SELECT * FROM configuracion");
$row_empresa = mysqli_num_rows($query_empresa);
if ($row_empresa > 0) {
	if ($infoEmpresa = mysqli_fetch_assoc($query_empresa)) {
		$dni = $infoEmpresa['dni'];
		$nombre_empresa = $infoEmpresa['nombre'];
		$razonSocial = $infoEmpresa['razon_social'];
		$telEmpresa = $infoEmpresa['telefono'];
		$emailEmpresa = $infoEmpresa['email'];
		$dirEmpresa = $infoEmpresa['direccion'];
		$igv = $infoEmpresa['igv'];
	}
}
$query_data = mysqli_query($conexion, "CALL data();");
$result_data = mysqli_num_rows($query_data);
if ($result_data > 0) {
	$data = mysqli_fetch_assoc($query_data);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="IndustriasHernandez">

	<title>CRM</title>

	<!-- Custom styles for this template-->
	<link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
	<link rel="icon" href="../img/a.ico" type="image/x-icon">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&display=swap">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

  <!-- Estilos personalizados -->
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
    /* Agrega estilos adicionales según tus necesidades */
	.form-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }
        
        .form-card:hover {
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group select {
            border: none;
            border-radius: 10px;
            padding: 10px;
            transition: box-shadow 0.3s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="tel"]:focus,
        .form-group input[type="date"]:focus,
        .form-group input[type="number"]:focus,
        .form-group select:focus {
            outline: none;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

		/* Estilo base para la tabla */
		.mi-tabla {
		border-collapse: collapse;
		width: 100%;
		max-width: 800px; /* Ajusta el ancho máximo según tu diseño */
		margin: 0 auto;
		background-color: #ffffff; /* Cambia el color de fondo según tu preferencia */
		box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Agrega una sombra */
		}

		/* Estilo para las celdas de encabezado */
		.mi-tabla th {
		background-color: #e3e6ed; /* Cambia el color de fondo del encabezado */
		color: #a3abb3; /* Cambia el color del texto del encabezado a negro */
		font-weight: bold;
		height: 50px; /* Ajusta la altura del encabezado según tu preferencia */
		text-align: center; /* Centra el texto del encabezado */
		}

		/* Estilo para las celdas de datos */
		.mi-tabla td {
		padding: 8px;
		text-align: center; /* Alinea el texto al centro según tu preferencia */
		}

		/* Estilo de las filas impares (opcional) */
		.mi-tabla tr:nth-child(odd) {
		background-color: #f2f2f2; /* Cambia el color de fondo de las filas impares */
		}
  </style>
  
  

</head>

<body id="page-top">
	<?php
	include "../conexion.php";
	$query_data = mysqli_query($conexion, "CALL data();");
	$result_data = mysqli_num_rows($query_data);
	if ($result_data > 0) {
		$data = mysqli_fetch_assoc($query_data);
	}

	?>
	<!-- Page Wrapper -->
	<div id="wrapper">

		<?php include_once "includes/menu.php"; ?>
		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">
				<!-- Topbar -->
				<nav class="navbar navbar-expand navbar-light bg-primary text-white topbar mb-4 static-top shadow">

					<!-- Sidebar Toggle (Topbar) -->
					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>
					<div class="input-group">
						<h6>CRM</h6>
						<p class="ml-auto" style="margin-right: 20px;"><strong>Peru, </strong><?php echo fechaPeru();?></p>

					</div>
					

					<script>
					function Celestial() {
						var div = document.querySelector('.bg-gradient-primary');
						var div2 = document.querySelector('.list-group-item:first-child');
						var elements = document.querySelectorAll('.bg-primary');
						div.style.backgroundImage = "linear-gradient(180deg, #819ceb 10%, #ff2323 100%)";
						div2.style.backgroundImage = "linear-gradient(90deg, #819ceb 10%, #ff2323 100%)";
						for (var i = 0; i < elements.length; i++) {
						elements[i].style.backgroundImage = "linear-gradient(90deg, #819ceb 10%, #ff2323 100%)";
						elements[i].style.backgroundColor = "red";
						}
					}
					</script>

 

					
<!-- Icono de la tuerquita -->
<a href="#" id="dropdownIcon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="  padding: 5px; border-radius: 10%; color: white;">
	<svg role="img" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cog" class="svg-inline--fa fa-cog fa-w-16 fa-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"></path></svg>
</a>

<!-- Contenido del menú desplegable -->
<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="dropdownIcon">
    <h6 class="dropdown-header">
        Personaliza
    </h6>
 
    <a class="dropdown-item d-flex align-items-center" href="#">
         
        <div>
		<div class="swatch" id="swatch2">
            <button class="btn btn-customizer btn-primary" id="swatch2" style="color: #fff; background-color: #1da1f5; background-image: linear-gradient(45deg,#1da1f5,#8039da); width: 250px;">Flat</button>
        </div>
        </div>
    </a>
    <a class="dropdown-item d-flex align-items-center" href="#">
	<div>
		<div class="swatch" id="swatch2">
            <button  onclick="Celestial()" class="btn btn-customizer btn-primary" id="swatch2" style="color: #fff; background-color: #1da1f5; background-image: linear-gradient(90deg, #819ceb 10%, #ff2323 100%); width: 250px;">Celestial Red Gradient</button>
        </div>
        </div>
    </a>
    <a class="dropdown-item d-flex align-items-center" href="#">
        <div class="dropdown-list-image mr-3">
            <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="...">
            <div class="status-indicator bg-success"></div>
        </div>
        <div>
             
        </div>
    </a>
    <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
</div>



					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">

						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - User Information -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline small text-white"><?php echo $_SESSION['nombre']; ?></span>
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="#">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
									<?php echo $_SESSION['email']; ?>
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="salir.php">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
									Salir
								</a>
							</div>
						</li>

					</ul>

				</nav>
