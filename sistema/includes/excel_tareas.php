<?php 
 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= JHS_tareas.xls");

?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
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
						session_start();
						include "../../conexion.php";
						$id = $_SESSION['idUser'];
                        if ($_SESSION['rol'] == 1) {
							$query = mysqli_query($conexion, "SELECT * FROM eventos");
						}else{
							$query = mysqli_query($conexion, "SELECT * FROM eventos WHERE COD_idusuario = $id");
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