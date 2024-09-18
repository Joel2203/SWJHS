<?php include_once "includes/header.php";
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Cobranza</h1>
		<form action="./includes/excel_collection.php" method="POST">
		<input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
		<br>
		<button type="sumbit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
		</form>
		<?php if (verificarmostrarDatos(mostrarDatos(1), 1) != -1) { ?>
		<a href="registro_collection.php" class="btn btn-primary">Nuevo</a>
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
							<th>NumeroFactura</th>
							<th>fechaEmision</th>
							<th>fechaVencimiento</th>
							<th>monto</th>
							<th>moneda</th>
							<th>estado</th>
							<th>documento</th>
							<th>observaciones</th>
							<th>recurrente</th>
							<th>Company</th>
							 
							
							 
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM `collections` as c inner join customers as cu on c.CODidcustomer = cu.idCliente inner join files as f on c.idCollections = f.COD_idCollections;");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td>
										<?php if ($data['documento'] !== 'none') {?>
											<button onclick="openModelPDF('<?php echo $url .'/'. $data['url']; ?>')" class="btn btn-primary" style="background-color: orange; color: white;" type="button"><i class="far fa-eye" style="color: white;"></i></button>								  
											<a href="<?php echo $url .'/'. $data['url']; ?>" target="_black" class="btn btn-primary" style="background-color: blue;"><i class="fas fa-file-pdf"></i></a>
										<?php 
										}
										?>			
										
										<?php if (verificarmostrarDatos(mostrarDatos(1), 3) != -1) { ?>
											<a href="editar_collection.php?id=<?php echo $data['idCollections']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
											<?php } ?>
											<?php if (verificarmostrarDatos(mostrarDatos(1), 4) != -1) { ?>
											<form action="eliminar_collection.php?id=<?php echo $data['idCollections']; ?>" method="post" class="confirmar d-inline">
												<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
											</form>
											<?php } ?>
									</td>
									<td><?php echo $data['idCollections']; ?></td>
									<td><?php echo $data['NumeroFactura']; ?></td>
									<td><?php echo $data['fechaEmision']; ?></td>
									<td><?php echo $data['fechaVencimiento']; ?></td>
									<td><?php echo $data['monto']; ?></td>
                                    <td><?php echo $data['moneda']; ?></td>
                                    <td><?php echo $data['estado']; ?></td>
                                    <td><?php echo $data['documento']; ?></td>
                                    <td><?php echo $data['observaciones']; ?></td>
                                    <td><?php echo $data['recurrente']; ?></td>
                                    <td><?php echo $data['Company']; ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
								
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
<!-- End of Main Content -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <script>
                            function openModelPDF(url) {
                                $('#modalPdf').modal('show');
                                $('#iframePDF').attr('src',url);
                            }
</script>

<?php include_once "includes/footer.php"; ?>