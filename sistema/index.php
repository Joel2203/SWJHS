<?php include_once "includes/header.php"; ?>

<?php
	include "../conexion.php";


	if ($_SESSION['rol'] == 1) {
		$account = mysqli_query($conexion, "SELECT COUNT(*) as resultado FROM `customers` INNER JOIN typecustomer ON customers.idCliente = typecustomer.COD_idCliente WHERE typecustomer.type = 1");
		$account1 = mysqli_fetch_assoc($account);
	
		$customer = mysqli_query($conexion, "SELECT COUNT(*) as resultado FROM `customers` INNER JOIN typecustomer ON customers.idCliente = typecustomer.COD_idCliente WHERE typecustomer.type = 2");
		$customer1 = mysqli_fetch_assoc($customer);

		$contact = mysqli_query($conexion, "SELECT COUNT(*) as resultado FROM `contacts`");
		$contact1 = mysqli_fetch_assoc($contact);

		//
		$queryA = mysqli_query($conexion, 'SELECT SUM(s.`One Shot`) as resultado FROM sales as s WHERE s.tipo = "PEN";');
		$queryB = mysqli_query($conexion, 'SELECT SUM(s.`One Shot`) as resultado FROM sales as s WHERE s.tipo = "USD";');
		$dataA = mysqli_fetch_assoc($queryA);
		$dataB = mysqli_fetch_assoc($queryB);



	} else {
		$account = mysqli_query($conexion, "SELECT COUNT(*) as resultado FROM `customers` INNER JOIN typecustomer ON customers.idCliente = typecustomer.COD_idCliente WHERE COD_idusuario = " . $_SESSION['idUser'] . " AND typecustomer.type = 1");
		$account1 = mysqli_fetch_assoc($account);
	
		$customer = mysqli_query($conexion, "SELECT COUNT(*) as resultado FROM `customers` INNER JOIN typecustomer ON customers.idCliente = typecustomer.COD_idCliente WHERE COD_idusuario = " . $_SESSION['idUser'] . " AND typecustomer.type = 2");
		$customer1 = mysqli_fetch_assoc($customer);

		$contact = mysqli_query($conexion, "SELECT COUNT(*) as resultado FROM `contacts` as c INNER join usuario as u on c.COD_idusuario = u.idusuario  where u.idusuario =  " . $_SESSION['idUser']);
		$contact1 = mysqli_fetch_assoc($contact);

		$queryA = mysqli_query($conexion, 'SELECT SUM(s.`One Shot`) as resultado FROM sales as s inner join usuario as u on s.idUsuario=u.idusuario WHERE s.tipo = "PEN" and u.idusuario = ' . $_SESSION['idUser']);
		$queryB = mysqli_query($conexion, 'SELECT SUM(s.`One Shot`) as resultado FROM sales as s inner join usuario as u on s.idUsuario=u.idusuario WHERE s.tipo = "USD" and u.idusuario = ' . $_SESSION['idUser']);
		$dataA = mysqli_fetch_assoc($queryA);
		$dataB = mysqli_fetch_assoc($queryB);
	}						
?>



<!-- Begin Page Content -->
<div class="container-fluid">
 
	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
	</div>
 
	<!-- Content Row -->
	<div class="row">

		<!-- Earnings (Monthly) Card Example -->
		<?php if (verificarPermiso($permisos, 9) == 9) { ?>
		<?php if ($_SESSION['rol'] == 1) {  ?>
			
		<a class="col-xl-3 col-md-6 mb-4" href="lista_usuarios.php">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Usuarios</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['usuarios']; ?></div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>
		<?php } ?>
		<?php } ?>

	<!-- ejm -->
	<div class="row">

	<?php if (verificarPermiso($permisos, 2) == 2) { ?>
		<!-- Earnings (Monthly) Card Example -->
		<a class="col-xl-3 col-md-6 mb-4" href="lista_contact.php">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Contactos</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $contact1['resultado']; ?></div>
						</div>
						<div class="col-auto">
							<i class="fas fa-envelope fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>
		<?php } ?>

		
		

		

		

	</div>
	
	<?php if ($_SESSION['rol'] == 1) {  ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
							<th>RUC</th>
							<th>Company</th>
							<th>Oportunidad</th>
							<th>Días Restantes</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query_select = mysqli_query($conexion, "SELECT c.RUC, c.Company, s.Oportunidad, s.`Expected Close`, DATEDIFF(s.`Expected Close`, CURDATE()) AS DiasRestantes FROM sales AS s INNER JOIN customers AS c ON c.idCliente = s.idCustomer ORDER BY s.`Expected Close` DESC;");
						while ($expect = mysqli_fetch_assoc($query_select)) {
							$ruc1 = $expect['RUC'];
							$company1 = $expect['Company'];
							$Oportunidad1 = $expect['Oportunidad'];
							$Expected_Close1 = $expect['Expected Close'];
							$dias_restantes = $expect['DiasRestantes'];
						?>
							<tr>
								<td><?php echo $ruc1; ?></td>
								<td><?php echo $company1; ?></td>
								<td><?php echo $Oportunidad1; ?></td>
								<td><?php
								if ($dias_restantes > 0) {
									echo '<span style="color: green;">' . $dias_restantes . ' días restantes</span>';
								} else {
									
									echo '<span style="color: red;">' . $dias_restantes . ' días transcurridos</span>';
								}
								?></td>

							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Configuración</h1>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header bg-primary text-white">
					Información Personal
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Nombre: <strong><?php echo $_SESSION['nombre']; ?></strong></label>
					</div>
					<div class="form-group">
						<label>Correo: <strong><?php echo $_SESSION['email']; ?></strong></label>
					</div>
					<div class="form-group">
						<label>Rol: <strong><?php echo $_SESSION['rol_name']; ?></strong></label>
					</div>
					<div class="form-group">
						<label>Usuario: <strong><?php echo $_SESSION['user']; ?></strong></label>
					</div>
					<ul class="list-group">
						<li class="list-group-item active">Cambiar Contraseña</li>
						<form action="" method=" post" name="frmChangePass" id="frmChangePass" class="p-3">
							<div class="form-group">
								<label>Contraseña Actual</label>
								<input type="password" name="actual" id="actual" placeholder="Clave Actual" required class="form-control">
							</div>
							<div class="form-group">
								<label>Nueva Contraseña</label>
								<input type="password" name="nueva" id="nueva" placeholder="Nueva Clave" required class="form-control">
							</div>
							<div class="form-group">
								<label>Confirmar Contraseña</label>
								<input type="password" name="confirmar" id="confirmar" placeholder="Confirmar clave" required class="form-control">
							</div>
							<div class="alertChangePass" style="display:none;">
							</div>
							<div>
								<button type="submit" class="btn btn-primary btnChangePass">Cambiar Contraseña</button>
							</div>
						</form>
					</ul>
					<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT api FROM api");
						$data = mysqli_fetch_assoc($query);

						if(isset($_POST['actual'])) {
							$nuevoToken = $_POST['actual'];
					
							$query = "UPDATE api SET api = '$nuevoToken', fecha_actualizada = CURRENT_TIMESTAMP WHERE idapi = 1";
							mysqli_query($conexion, $query);
						}

				    ?>
					 
					<ul class="list-group">
						<li class="list-group-item active">API SUNAT</li>
						<form action="" method="post" name="frmChangePass" id="frmChangePass" class="p-3">
							<div class="form-group">
								<label>TOKEN</label>
								<input type="text" value="<?php echo $data['api']; ?>" name="actual" id="actual" placeholder="Token Actual" required class="form-control">
							</div>
							<div class="form-group">
								<label><a href="https://www.apisperu.com/servicios/dniruc/#register">Enlace a API Perú</a></label>
							</div>
							<div class="alertChangePass" style="display:none;">
							</div>
							<div>
								<button type="submit" class="btn btn-primary btnChangePass">Cambiar Token</button>
							</div>
						</form>
					</ul>
				</div>
			</div>
		</div>
		<?php if ($_SESSION['rol'] == 1) { ?>
			<div class="col-lg-6">
				<div class="card">
					<div class="card-header bg-primary text-white">
						Datos de la Empresa
					</div>
					<div class="card-body">
						<form action="empresa.php" method="post" id="frmEmpresa" class="p-3">
							<div class="form-group">
								<label>Ruc:</label>
								<input type="number" name="txtDni" value="<?php echo $dni; ?>" id="txtDni" placeholder="Dni de la Empresa" required class="form-control">
							</div>
							<div class="form-group">
								<label>Nombre:</label>
								<input type="text" name="txtNombre" class="form-control" value="<?php echo $nombre_empresa; ?>" id="txtNombre" placeholder="Nombre de la Empresa" required class="form-control">
							</div>
							<div class="form-group">
								<label>Razon Social:</label>
								<input type="text" name="txtRSocial" class="form-control" value="<?php echo $razonSocial; ?>" id="txtRSocial" placeholder="Razon Social de la Empresa">
							</div>
							<div class="form-group">
								<label>Teléfono:</label>
								<input type="number" name="txtTelEmpresa" class="form-control" value="<?php echo $telEmpresa; ?>" id="txtTelEmpresa" placeholder="teléfono de la Empresa" required>
							</div>
							<div class="form-group">
								<label>Correo Electrónico:</label>
								<input type="email" name="txtEmailEmpresa" class="form-control" value="<?php echo $emailEmpresa; ?>" id="txtEmailEmpresa" placeholder="Correo de la Empresa" required>
							</div>
							<div class="form-group">
								<label>Dirección:</label>
								<input type="text" name="txtDirEmpresa" class="form-control" value="<?php echo $dirEmpresa; ?>" id="txtDirEmpresa" placeholder="Dirreción de la Empresa" required>
							</div>
							<div class="form-group">
								<label>IGV (%):</label>
								<input type="text" name="txtIgv" class="form-control" value="<?php echo $igv; ?>" id="txtIgv" placeholder="IGV de la Empresa" required>
							</div>
							<?php echo isset($alert) ? $alert : ''; ?>
							<div>
								<button type="submit" class="btn btn-primary btnChangePass"><i class="fas fa-save"></i> Guardar Datos</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="col-lg-6">
				<div class="card">
					<div class="card-header bg-primary text-white">
						Datos de la Empresa
					</div>
					<div class="card-body">
						<div class="p-3">
							<div class="form-group">
								<strong>Ruc:</strong>
								<h6><?php echo $dni; ?></h6>
							</div>
							<div class="form-group">
								<strong>Nombre:</strong>
								<h6><?php echo $nombre_empresa; ?></h6>
							</div>
							<div class="form-group">
								<strong>Razon Social:</strong>
								<h6><?php echo $razonSocial; ?></h6>
							</div>
							<div class="form-group">
								<strong>Teléfono:</strong>
								<?php echo $telEmpresa; ?>
							</div>
							<div class="form-group">
								<strong>Correo Electrónico:</strong>
								<h6><?php echo $emailEmpresa; ?></h6>
							</div>
							<div class="form-group">
								<strong>Dirección:</strong>
								<h6><?php echo $dirEmpresa; ?></h6>
							</div>
							<div class="form-group">
								<strong>IGV (%):</strong>
								<h6><?php echo $igv; ?></h6>
							</div>

						</div>
					</div>
				</div>
			</div>

		<?php } ?>
	</div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>