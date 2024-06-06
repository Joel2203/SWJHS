<?php include_once "includes/header.php"; 
include "../conexion.php";
 
if (isset($_GET['id'])) {
    $variable = $_GET['id'];
    // La variable $variable ahora contiene el valor de id si es igual a 27.
	$query = mysqli_query($conexion, "SELECT CONCAT(nombre, ', ', correo, ', ', usuario) AS result  FROM usuario as u inner join rol as r on r.idrol = u.rol where r.idrol = $variable");

	echo "<td><div class='container-fluid'>Estos usuarios están asignados a ese rol</div></td>";
	echo "<td><div class='container-fluid'><div class='alert alert-warning' role='alert'>Nombre - Correo - Usuario</div></div></td>";
	while ($data1 = mysqli_fetch_assoc($query)) { 
		echo "<td><div class='container-fluid'><div class='alert alert-warning' role='alert'>".$data1['result']."</div></div></td>";
	}
} 


?>


<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Roles</h1>
		<a href="registro_rol.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>ROL</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						

						$query = mysqli_query($conexion, "SELECT * FROM rol");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['idrol']; ?></td>
									<td><?php echo $data['rol']; ?></td>

										<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<a href="editar_roles.php?id=<?php echo $data['idrol']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

										<form action="eliminar_rol.php?id=<?php echo $data['idrol']; ?>" method="post" class="confirmar d-inline">
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