<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

	<!-- Sidebar - Brand -->
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
		<div class="sidebar-brand-icon rotate-n-0" style="width: 70px;">
			<img src="img/JHS.jpg" class="img-thumbnail">
		</div>
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider my-0">

	<!-- Divider -->
	<hr class="sidebar-divider">

	<!-- Heading -->
	<div class="sidebar-heading">
		Interface
	</div>

	<?php
	$permisos = $_SESSION['Permisos'];
	$generales = $_SESSION['generales'];

	
	function mostrarDatos($posicion) {
		$opciones = $_SESSION['generales'];
		$array1 = json_decode($opciones, true);
	
		if (isset($array1[$posicion])) {
			return "[" . implode(",", $array1[$posicion]) . "]";
		} else {
			return "[]";
		}
	}

	function verificarmostrarDatos($posicion,$num) {
		$array = json_decode($posicion, true);
	
		if (is_array($array)) {
			if (in_array($num, $array)) {
				// El número está en el array, retornar 1
				return $num;
			} else {
				// El número no está en el array, retornar 0
				return -1;
			}
		} else {
			// El resultado no es un array, retornar 0
			return -1;
		}
	}

	function verificarPermiso($permisos, $num) {
		// Convertir la cadena a un array
		$array = json_decode($permisos);
	
		if (is_array($array)) {
			if (in_array($num, $array)) {
				// El número está en el array, retornar 1
				return $num;
			} else {
				// El número no está en el array, retornar 0
				return 0;
			}
		} else {
			// El resultado no es un array, retornar 0
			return 0;
		}
	}
    
	//$resultado = verificarPermiso($permisos, $num);
	?>

	<?php if (verificarPermiso($permisos, 3) == 3) { ?>
		<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContacts" aria-expanded="true" aria-controls="collapseContacts">
				<i class="fas fa-phone"></i>
				<span>Cliente</span>
			</a>
			<div id="collapseContacts" class="collapse" aria-labelledby="headingcollapseContacts" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php if (verificarmostrarDatos(mostrarDatos(2), 1) != -1) { ?>
					<a class="collapse-item" href="./agregar_contact.php">Nuevo Contactos</a>
					<?php } ?>
				<?php if (verificarmostrarDatos(mostrarDatos(2), 2) != -1) { ?>
					<a class="collapse-item" href="./lista_contact.php">Lista de contactos</a>
					<?php } ?>
				</div>
			</div>
		</li>	
		<?php } ?>

	<?php if (verificarPermiso($permisos, 1) == 1) { ?>
	<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCustomer" aria-expanded="true" aria-controls="collapseCustomer">
		    <i class="fas fa-user"></i> 
			<span>proovedores</span>
		</a>
		<div id="collapseCustomer" class="collapse" aria-labelledby="headingcollapseCustomer" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<?php if (verificarmostrarDatos(mostrarDatos(0), 1) != -1) { ?>
				<a class="collapse-item" href="./agregar_account.php">Nuevo Cuenta</a>
			<?php } ?>
			<?php if (verificarmostrarDatos(mostrarDatos(0), 2) != -1) { ?>
				<a class="collapse-item" href="./prueba1.php">Lista de Cuentas</a>
			</div>
			<?php } ?>
		</div>
	</li>
	<?php } ?>

	
	<?php if (verificarPermiso($permisos, 4) == 4) { ?>
	<!-- Nav Item - Pages Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts" aria-expanded="true" aria-controls="collapseProducts">
		    <i class="fas fa-tag"></i>  
			<span>Todos los Productos</span>
		</a>
		<div id="collapseProducts" class="collapse" aria-labelledby="headingcollapseProducts" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<?php if (verificarmostrarDatos(mostrarDatos(3), 2) != -1) { ?>	
			<a class="collapse-item" href="./lista_segment.php">Segmento</a>
			<?php } ?>
			<?php if (verificarmostrarDatos(mostrarDatos(3), 2) != -1) { ?>
			<a class="collapse-item" href="./lista_maker.php">Fabricantes</a>
			<?php } ?>
			<?php if (verificarmostrarDatos(mostrarDatos(3), 1) != -1) { ?>
				<a class="collapse-item" href="./registro_allproducto.php">Nuevos Productos</a>
				<?php } ?>
				<?php if (verificarmostrarDatos(mostrarDatos(2), 1) != -1) { ?>
				<a class="collapse-item" href="./lista_product.php">Lista de Productos</a>
				<?php } ?>
			</div>
		</div>
	</li>
	<?php } ?>
	

		<?php if (verificarPermiso($permisos, 9) == 9) { ?>
	<?php if ($_SESSION['rol'] == 1) { ?>
		<!-- Nav Item - Usuarios Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios" aria-expanded="true" aria-controls="collapseUtilities">
				<i class="fas fa-user"></i>
				<span>Trabajadores</span>
			</a>
			<div id="collapseUsuarios" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php if (verificarmostrarDatos(mostrarDatos(8), 1) != -1) { ?>
					<a class="collapse-item" href="registro_usuario.php">Nuevo Usuario</a>
					<?php } ?>
					<?php if (verificarmostrarDatos(mostrarDatos(8), 2) != -1) { ?>
					<a class="collapse-item" href="lista_usuarios.php">Usuarios</a>
					<?php } ?>
					<?php if (verificarmostrarDatos(mostrarDatos(8), 2) != -1) { ?>
					<a class="collapse-item" href="lista_roles.php">Roles</a>
					<?php } ?>
				</div>
			</div>
		</li>
	<?php } ?>
	<?php } ?>

</ul>