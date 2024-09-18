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

	<?php if (verificarPermiso($permisos, 2) == 2) { ?>
		<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCollections" aria-expanded="true" aria-controls="collapseCollections">
	    	<i class="fas fa-money-bill"></i> 
			<span>Cobranza</span>
		</a>
		<div id="collapseCollections" class="collapse" aria-labelledby="headingcollapseCollections" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<?php if (verificarmostrarDatos(mostrarDatos(1), 1) != -1) { ?>
				<a class="collapse-item" href="./registro_collection.php">Nueva Cobranza</a>
				<?php } ?>
			<?php if (verificarmostrarDatos(mostrarDatos(1), 2) != -1) { ?>
				<a class="collapse-item" href="./lista_collection.php">Lista de Cobranzas</a>
				<a class="collapse-item" href="./generar_factura.php">Generar Factura</a>
				<a class="collapse-item" href="./generar_orden_de_compra.php">Generar Or.de Trabajo</a>
				<?php } ?>
			<?php if (verificarmostrarDatos(mostrarDatos(1), 2) != -1) { ?>
				<a class="collapse-item" href="./lista_orden_de_trabajo.php">Lista de Trabajo</a>
				<?php } ?>
			</div>
		 
		</div>
	</li>
	<?php } ?>

	<?php if (verificarPermiso($permisos, 7) == 7) { ?>
		<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSales" aria-expanded="true" aria-controls="collapseSales">
			<i class="fas fa-chart-line"></i>  
            <i class="fas fa-arrow-up"></i>  
				<span>Oportunidades</span>
			</a>
			<div id="collapseSales" class="collapse" aria-labelledby="headingCollapseSales" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php if (verificarmostrarDatos(mostrarDatos(6), 1) != -1) { ?>
					<a class="collapse-item" href="./registro_sales.php">Crear oportunidad</a>
					<?php } ?>
					<?php if (verificarmostrarDatos(mostrarDatos(6), 2) != -1) { ?>
					<a class="collapse-item" href="./lista_oportunidades_1.php">Mis Oportunidades</a>
					<?php } ?>
				</div>
			</div>
		</li>
		<?php } ?>

		<?php if (verificarPermiso($permisos, 6) == 6) { ?>
		<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseActivities" aria-expanded="true" aria-controls="collapseActivities">
			    <i class="fas fa-list"></i>  
				<i class="far fa-calendar-check"></i>
				<span>Actividades</span>
			</a>
			<div id="collapseActivities" class="collapse" aria-labelledby="headingcollapseActivities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php if (verificarmostrarDatos(mostrarDatos(5), 1) != -1) { ?>
					<a class="collapse-item" href="./lista_tarea.php">Lista de Actividades</a>
					<?php } ?>
					<?php if (verificarmostrarDatos(mostrarDatos(5), 2) != -2) { ?>
					<a class="collapse-item" href="./lista_tareas_view.php">Ver mis Actividades</a>
					<?php } ?>	
				</div>
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

	<?php if (verificarPermiso($permisos, 5) == 5) { ?>
		<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAccount" aria-expanded="true" aria-controls="collapseAccount">
			   <i class="fas fa-users"></i>  <i class="fas fa-money-bill"></i>  
				<span>Todas las Cuentas</span>
			</a>
			<div id="collapseAccount" class="collapse" aria-labelledby="headingcollapseAccount" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php if (verificarmostrarDatos(mostrarDatos(4), 1) != -1) { ?>
					<a class="collapse-item" href="./registro_allaccount.php">Nueva Cuenta</a>
					<?php } ?>
					<?php if (verificarmostrarDatos(mostrarDatos(4), 2) != -1) { ?>	
					<a class="collapse-item" href="./lista_allaccount.php">Accounts</a>
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