<?php include_once "../../../includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Clientes</h1>
		<a href="registro_cliente.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>Company</th>
							<th>RUC</th>
							<th>URL</th>
							<th>Tipo_Contacto</th>
							<th>Contact_Name</th>
							<th>Apellido_Paterno</th>
							<th>Apellido_Materno</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Direccion</th>
							<th>Distrito</th>
							<th>Provincia</th>
							<th>Departamento</th>
							<th>Pais</th>
							<th>Cargo</th>
							<th>Cantidad_Empleados</th>
							<th>OrigenCliente</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../../../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM customers");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['idCliente']; ?></td>
									<td><?php echo $data['Company']; ?></td>
									<td><?php echo $data['RUC']; ?></td>
									<td><?php echo $data['URL']; ?></td>
									<td><?php echo $data['Tipo_Contacto']; ?></td>
                                    <td><?php echo $data['Contact_Name']; ?></td>
                                    <td><?php echo $data['Apellido_Paterno']; ?></td>
                                    <td><?php echo $data['Apellido_Materno']; ?></td>
                                    <td><?php echo $data['Email']; ?></td>
                                    <td><?php echo $data['Phone']; ?></td>
                                    <td><?php echo $data['Direccion']; ?></td>
                                    <td><?php echo $data['Distrito']; ?></td>
                                    <td><?php echo $data['Provincia']; ?></td>
                                    <td><?php echo $data['Departamento']; ?></td>
                                    <td><?php echo $data['Pais']; ?></td>
                                    <td><?php echo $data['Cargo']; ?></td>
                                    <td><?php echo $data['Cantidad_Empleados']; ?></td>
                                    <td><?php echo $data['OrigenCliente']; ?></td>

									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<a href="editar_cliente.php?id=<?php echo $data['idCliente']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
										<form action="eliminar_cliente.php?id=<?php echo $data['idCliente']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
									</td>
									<?php } ?>
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


<?php include_once "../../../includes/footer.php"; ?>
