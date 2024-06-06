<?php 
 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= JHS_cobranza.xls");

?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
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
						session_start();
						include "../../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM `collections` as c inner join customers as cu on c.CODidcustomer = cu.idCliente inner join files as f on c.idCollections = f.COD_idCollections;");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									 
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