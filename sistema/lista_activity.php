<?php include_once "includes/header.php";
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Activity</h1>
		<a href="registro_activity.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>asunto</th>
							<th>descripcion</th>
							<th>fechaInicio</th>
							<th>fechaFin</th>
							<th>COD_idContact</th>
							<th>COD_idAccount</th>					
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM `activities`;");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($row = mysqli_fetch_assoc($query)) { ?>
								<tr>
								<td><?php echo $row['idActivities']; ?></td>
								<td><?php echo $row['asunto']; ?></td>
								<td><?php echo $row['descripcion']; ?></td>
								<td><?php echo $row['fechaInicio']; ?></td>
								<td><?php echo $row['fechaFin']; ?></td>
								<td><?php echo $row['COD_idContact']; ?></td>
								<td><?php echo $row['COD_idAccount']; ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>		
									<a href="editar_activity.php?id=<?php echo $row['idActivities']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
									<form action="eliminar_activity.php?id=<?php echo $row['idActivities']; ?>" method="post" class="confirmar d-inline">
										<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
									</form>
									</td>
									<?php } ?>
								</tr>
						<?php }
						} ?>
					</tbody>
</div>
<!-- /.container-fluid -->
<div class="modal fade" id="modalPdf" tabindex="-1" aria-labelledby="modalPdf" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Ver archivo</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<iframe id="iframePDF" frameborder="0" scrolling="no" width="100%" height="500px"></iframe>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

								</div>
							</div>
						</div>
					</div>
							</table>
						</div>

					</div>
				</div>

</div>
</div>

<?php include_once "includes/footer.php"; ?>