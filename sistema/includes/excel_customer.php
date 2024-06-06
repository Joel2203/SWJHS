<?php 
 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= JHS_clientes.xls");

?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div class="table-responsive">
	<table class="mi-tabla" id="table">
		<thead class="thead-dark">
			<tr>
			<th>ID</th>
			<th>Company</th>
			<th>RUC</th>
			<th>URL</th>                                              
			<th>Direccion</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Departamento</th>
			<th>Pais</th>
			<th>Cargo</th>
			<th>Cantidad_Empleados</th>
			<th>OrigenCliente</th>
			<th>Contacto</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
			session_start();
			include "../../conexion.php";
			
			if ($_SESSION['rol'] == 1) {
				$query = mysqli_query($conexion, "SELECT * FROM customers as c INNER JOIN typecustomer as t ON c.idCliente = t.COD_idCliente INNER JOIN contacts as co on co.idContacts = c.COD_idcontacto WHERE t.type = 2");
			} else {
				$query = mysqli_query($conexion, "SELECT * FROM customers as c INNER JOIN typecustomer as t ON c.idCliente = t.COD_idCliente INNER JOIN contacts as co on co.idContacts = c.COD_idcontacto WHERE t.type = 2 AND c.COD_idusuario = " . $_SESSION['idUser']);
			}

			$result = mysqli_num_rows($query);
			if ($result > 0) {
				while ($data = mysqli_fetch_assoc($query)) { ?>
					<tr>
					<td><?php echo $data['idCliente']; ?></td>
									<td><?php echo $data['Company']; ?></td>
									<td><?php echo $data['RUC']; ?></td>
									<td>
										<?php if (!empty($data['URL'])) : ?>
											<a href="<?php echo $data['URL']; ?>" class="btn btn-primary shadow" target="_blank"><?php echo $data['URL']; ?></a>
										<?php else : ?>
											<?php echo $data['URL']; ?>
										<?php endif; ?>
									</td>                                   
									<td><?php echo $data['Direccion']; ?></td>
									<td><?php echo $data['Distrito']; ?></td>
									<td><?php echo $data['Provincia']; ?></td>
									<td><?php echo $data['Departamento']; ?></td>
									<td><?php echo $data['Pais']; ?></td>
									<td><?php echo $data['Cargo']; ?></td>
									<td><?php echo $data['Cantidad_Empleados']; ?></td>
									<td><?php echo $data['OrigenCliente']; ?></td>
									<td><?php echo $data["nombre"] ?> <?php echo $data["segundo_nombre"] ?> <?php echo $data["apellido_paterno"] ?> <?php echo $data["apellido_materno"] ?></td>		
					</tr>
			<?php }
			} ?>
		</tbody>

	</table>
</div>