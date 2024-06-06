<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">All Family&Product</h1>
		<?php if (verificarmostrarDatos(mostrarDatos(7), 1) != -1) { ?>
		<a href="registro_familyproduct.php" class="btn btn-primary">Nuevo</a>
		<?php } ?>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
						    <?php if (verificarmostrarDatos(mostrarDatos(7), 3) != -1) { ?>
						    <th>ACCIONES</th>
							<?php } ?>
							<th>ID</th>
							<th>Familia</th>
							<th>Marca</th>
							<th>Producto/Servicio</th>
							<th>Descripción</th>
							<th>Proveedor</th>
							<th>Contacto</th>
							 
							
							 
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM `family&products` ORDER BY `family&products`.`id` DESC");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td>
										<?php if (verificarmostrarDatos(mostrarDatos(7), 3) != -1) { ?>
											<a href="editar_family&product.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
											<?php } ?>
											<?php if (verificarmostrarDatos(mostrarDatos(7), 4) != -1) { ?>
											<form action="eliminar_family&product.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
												<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
											</form>
											<?php } ?>
									</td>
									<td><?php echo $data['id']; ?></td>
									<td><?php echo $data['Familia']; ?></td>
									<td><?php echo $data['Marca']; ?></td>
									<td><?php echo $data['Producto/Servicio']; ?></td>
									<td><?php echo $data['Descripción']; ?></td>
									<td><?php echo $data['Proveedor']; ?></td>
									<td><?php echo $data['Contacto']; ?></td>
									
									 
									
									 
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