<?php 
 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= JHS_todaslascuentas.xls");

?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
					    	 
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
						session_start();
						include "../../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM account");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
							    	 
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