<?php include_once "includes/header.php"; ?>

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
							<th>PRODUCTO</th>
							<th>PRECIO</th>
							<th>STOCK</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT x.idPermiso FROM usuario u INNER JOIN rol r ON u.rol = r.idrol INNER JOIN rolxpermisos as x on x.idRol = r.idrol INNER JOIN permisos as p on p.id = x.idPermiso WHERE u.usuario = 'veneco' AND u.clave = '8c6e2d3b12029c208f561db58ee14a6a';");
						$result = mysqli_num_rows($query);

							$permisos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $permisos[] = $row['idPermiso'];
    }
	// Mostrar el array de IDs de permisos
    print_r($permisos);
?>
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