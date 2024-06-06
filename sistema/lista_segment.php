<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Segmento</h1>
		<a href="insertar_segment.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>marca</th>
							<th>fecha_De_registro</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM segment");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['idSegment']; ?></td>
									<td><?php echo $data['marca']; ?></td>
									<td><?php echo $data['fecha_De_registro']; ?></td>
									
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<a href="editar_customer.php?id=<?php echo $data['idSegment']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
										<form action="eliminar_customer.php?id=<?php echo $data['idSegment']; ?>" method="post" class="confirmar d-inline">
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


<?php include_once "includes/footer.php"; ?>