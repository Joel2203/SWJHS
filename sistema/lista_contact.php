<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Contacts</h1>
		<form action="./includes/excel_contact.php" method="POST">
		<input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
		<br>
		<button type="sumbit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
		</form>
		<?php if (verificarmostrarDatos(mostrarDatos(2), 1) != -1) { ?>
		<a href="agregar_contact.php" class="btn btn-primary">Nuevo</a>
		<?php } ?>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
					    	<th>ACCIONES</th>
							<th>ID</th>
							<th>nombre</th>
							<th>segundo_nombre</th>
							<th>apellido_paterno</th>
							<th>apellido_materno</th>
							
							<th>Nivel de interés</th>
							<th>Observaciones</th>
                            <th>Added</th>
							<th>email</th>
							<th>telefono_fijo</th>
							<th>celular</th>
							 
							
							 
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";
						$quey_message = mysqli_query($conexion, "SELECT * FROM configuracion limit 1");
						$dataM = mysqli_fetch_assoc($quey_message);
						if ($_SESSION['rol'] == 1) { 
							$query = mysqli_query($conexion, "SELECT * FROM `contacts`");
						}else{
							$query = mysqli_query($conexion, "SELECT * FROM `contacts` WHERE COD_idusuario = " . $_SESSION['idUser']);
						}
						
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
								    <td>
									 <a href="https://wa.me/<?php echo $data['celular']; ?>?text=Hola%20que%20tal%20te%20saluda%20<?php echo $_SESSION['nombre']; ?>%20mucho%20gusto%20<?php echo $data['nombre']; ?>%20<?php echo $data['segundo_nombre']; ?>%20<?php echo $data['apellido_paterno']; ?>%20<?php echo $data['apellido_materno']; ?>" class="btn btn-success" target="_blank"><i class='fab fa-whatsapp'></i></a>
											 <?php if (verificarmostrarDatos(mostrarDatos(2), 3) != -1) { ?>
											 <a href="editar_contact.php?id=<?php echo $data['idContacts']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
											 <?php } ?>
											 <?php if (verificarmostrarDatos(mostrarDatos(2), 4) != -1) { ?>
											 <form action="eliminar_contact.php?id=<?php echo $data['idContacts']; ?>" method="post" class="confirmar d-inline">
												 <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
											 </form>
											 <?php } ?>
								    </td>
									<td><?php echo $data['idContacts']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									<td><?php echo $data['segundo_nombre']; ?></td>
									<td><?php echo $data['apellido_paterno']; ?></td>
									<td><?php echo $data['apellido_materno']; ?></td>									 							 

									<?php
									$nivelInteres = $data['Nivel de interés'];
									$colorClass = '';

									if ($nivelInteres === 'Bajo') {
									$colorClass = 'bg-warning';
									} elseif ($nivelInteres === 'Medio') {
									$colorClass = 'bg-info';
									} elseif ($nivelInteres === 'Alto') {
									$colorClass = 'bg-success';
									}

									?>

									<?php if (!empty($colorClass)): ?>
									<td><span class="badge <?php echo $colorClass; ?>"><?php echo $nivelInteres; ?></span></td>
									<?php else: ?>
									<td><?php echo $nivelInteres; ?></td>
									<?php endif; ?>

									<td><?php echo $data['observaciones']; ?></td>
									<td><?php echo $data['AddedContacts']; ?></td>
                                    <td><?php echo $data['email']; ?></td>
                                    <td><?php echo $data['telefono_fijo']; ?></td>
                                    <td><?php echo $data['celular']; ?></td>									 
									
									 
								</tr>
						<?php }
						} ?>
					</tbody>

				</table>
			</div>

		</div>
	</div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>