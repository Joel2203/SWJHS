<?php 
 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= JHS_contact.xls");

?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<div class="table-responsive">
				<table class="mi-tabla" id="table">
					<thead class="thead-dark">
						<tr>
					 
							<th>ID</th>
							<th>nombre</th>
							<th>segundo_nombre</th>
							<th>apellido_paterno</th>
							<th>apellido_materno</th>
							
							<th>Nivel de interés</th>
							<th>Observaciones</th>
                            <th>Added</th>
							<th>email</th>
							<th>telefono_fijo</th>
							<th>celular</th>
							 
							
							 
						</tr>
					</thead>
					<tbody>
						<?php
						session_start();
						include "../../conexion.php";
						$quey_message = mysqli_query($conexion, "SELECT * FROM configuracion limit 1");
						$dataM = mysqli_fetch_assoc($quey_message);
						if ($_SESSION['rol'] == 1) { 
							$query = mysqli_query($conexion, "SELECT * FROM `contacts`");
						}else{
							$query = mysqli_query($conexion, "SELECT * FROM `contacts` WHERE COD_idusuario = " . $_SESSION['idUser']);
						}
						
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
								     
									<td><?php echo $data['idContacts']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									<td><?php echo $data['segundo_nombre']; ?></td>
									<td><?php echo $data['apellido_paterno']; ?></td>
									<td><?php echo $data['apellido_materno']; ?></td>									 							 

									<?php
									$nivelInteres = $data['Nivel de interés'];
									$colorClass = '';

									if ($nivelInteres === 'Bajo') {
									$colorClass = 'bg-warning';
									} elseif ($nivelInteres === 'Medio') {
									$colorClass = 'bg-info';
									} elseif ($nivelInteres === 'Alto') {
									$colorClass = 'bg-success';
									}

									?>

									<?php if (!empty($colorClass)): ?>
									<td><span class="badge <?php echo $colorClass; ?>"><?php echo $nivelInteres; ?></span></td>
									<?php else: ?>
									<td><?php echo $nivelInteres; ?></td>
									<?php endif; ?>

									<td><?php echo $data['observaciones']; ?></td>
									<td><?php echo $data['AddedContacts']; ?></td>
                                    <td><?php echo $data['email']; ?></td>
                                    <td><?php echo $data['telefono_fijo']; ?></td>
                                    <td><?php echo $data['celular']; ?></td>									 
									
									 
								</tr>
						<?php }
						} ?>
					</tbody>

				</table>
			</div>