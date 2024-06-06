<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Tareas</h1>
		<form action="./includes/excel_tareas.php" method="POST">
		<input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
		<br>
		<button type="sumbit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
		</form>
	</div>
	<a href="lista_tarea.php" class="btn btn-primary">Volver</a>
	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>Asignado</th>
							<th>Asunto</th>
							<th>Fecha inicio</th>
							<th>Fecha fin</th>
							<th>ubicación</th>
							<th>Mostar_hora</th>
							<th>Descripción</th>
							 
 
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";
						$id = $_SESSION['idUser'];
                        if ($_SESSION['rol'] == 1) {
							$query = mysqli_query($conexion, "SELECT * FROM eventos1");
						}else{
							$query = mysqli_query($conexion, "SELECT * FROM eventos1 WHERE COD_idusuario = $id");
						}
						
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['id']; ?></td>
									<td><?php echo $data['asignado']; ?></td>
									<td><?php echo $data['asunto']; ?></td>
									<td><?php echo $data['fecha_inicio']; ?></td>
									<td><?php echo $data['fecha_fin']; ?></td>
									<td><?php echo $data['ubicacion']; ?></td>
									<td><?php echo $data['mostrar_hora']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>

									 
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