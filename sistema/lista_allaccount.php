<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">All Account</h1>
		<form action="./includes/excel_allaccount.php" method="POST">
		<input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
		<br>
		<button type="sumbit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
		</form>
		<?php if (verificarmostrarDatos(mostrarDatos(4), 1) != -1) { ?>
		<a href="registro_allaccount.php" class="btn btn-primary">Nuevo</a>
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
							<th>Cuenta</th>
							<th>Direccion</th>
							<th>Ciudad</th>
							<th>Contacto principal</th>
							<th>Secot</th>
							<th>Propietario</th>
							<th>Origen cliente</th>
					 
						
						 
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM account");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
							    	<td>
									<?php if (verificarmostrarDatos(mostrarDatos(4), 3) != -1) { ?>
										<a href="editar_allproduct.php?id=<?php echo $data['idAccount']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
										<?php } ?>
										<?php if (verificarmostrarDatos(mostrarDatos(4), 4) != -1) { ?>
										<form action="eliminar_allproduct.php?id=<?php echo $data['idAccount']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
										<?php } ?>
									</td>
									<td><?php echo $data['idAccount']; ?></td>
									<td><?php echo $data['cuenta']; ?></td>
									<td><?php echo $data['direccion']; ?></td>
									<td><?php echo $data['ciudad']; ?></td>
									<td><?php echo $data['contacto_principal']; ?></td>
                                    <td><?php echo $data['secot']; ?></td>
                                    <td><?php echo $data['propietario']; ?></td>
                                    <td><?php echo $data['origen_cliente']; ?></td>
								 
									
									 
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