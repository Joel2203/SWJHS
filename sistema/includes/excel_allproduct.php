<?php 
 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= JHS_todoslosproductos.xls");

?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
					    
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
						session_start();
						include "../../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM `allproduct` ORDER BY `allproduct`.`idAllproduct` DESC");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
						 
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