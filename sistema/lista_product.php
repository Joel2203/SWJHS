<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">All Products</h1>
		<form action="./includes/excel_allproduct.php" method="POST">
		<input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
		<br>
		<button type="sumbit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
		</form>
		<?php if (verificarmostrarDatos(mostrarDatos(3), 1) != -1) { ?>	
		<a href="registro_allproducto.php" class="btn btn-primary">Nuevo</a>
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
							<th>Producto</th>
							<th>FechaModificacion</th>
							<th>Nombre</th>
							<th>Fabricante</th>
							<th>IdProducto</th>
							<th>PrecioListado</th>
							<th>Segmento</th>
							<th>ListaPreciosPredeterminada</th>
							 
							
							 
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM `allproduct` ORDER BY `allproduct`.`idAllproduct` DESC");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
								    <td>
										<?php if (verificarmostrarDatos(mostrarDatos(3), 3) != -1) { ?>	
											<a href="editar_allproduct.php?id=<?php echo $data['idAllproduct']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
											<?php } ?>
											<?php if (verificarmostrarDatos(mostrarDatos(3), 4) != -1) { ?>	
											<form action="eliminar_allproduct.php?id=<?php echo $data['idAllproduct']; ?>" method="post" class="confirmar d-inline">
												<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
											</form>
											<?php } ?>
									</td>
									<td><?php echo $data['idAllproduct']; ?></td>
									<td><?php echo $data['Producto']; ?></td>
									<td><?php echo $data['Fecha de modificación']; ?></td>
									<td><?php echo $data['Nombre']; ?></td>
									<td><?php echo $data['Fabricante']; ?></td>
									<td><?php echo $data['Id. de producto']; ?></td>
									<td><?php echo $data['Precio listado']; ?></td>
									<td><?php echo $data['Segmento']; ?></td>
									<td><?php echo $data['Lista de precios predeterminada']; ?></td>
			 
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