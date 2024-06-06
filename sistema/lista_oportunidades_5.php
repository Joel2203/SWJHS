<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Permisos</h1>
		<a href="registro_allproducto.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
      <table class="mi-tabla" id="table">
                                      <thead class="thead-dark">
                                        <tr>
                                          <th>Id</th>
                                          <th>Added</th>
                                          <th>Name</th>
                                          <th>Company</th>
                                          <th>Status</th>
                                          <th>Priority</th>
                                          <th>MRC</th>
                                          <th>Account Owner</th>
                                          <th>Detalle</th>
                                          <th>Phone</th>
                                          <th>Expected Close</th>
                                          <th>Contacto Cliente</th>
                                          <th>FCV</th>
                                          <th>One Shot</th>
                                          <th>Producto</th>
                                          <th>Propuesta</th>
                                          <?php if ($_SESSION['rol'] == 1) { ?>
                                          <th>ACCIONES</th>
                                          <?php } ?>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        include "../conexion.php";
                                        $id = $_SESSION['idUser'];

                                        if($_SESSION['rol'] == 1){
                                          $query = mysqli_query($conexion, "SELECT  fs.url, s.id,s.Oportunidad,cu.Company,s.Status,s.Priorit,s.MRC,u.usuario,s.Detalle,co.celular,s.`Expected Close`, co.nombre, s.FCV, s.`One Shot`, s.Producto, s.Propuesta FROM sales as s inner join file_sales as fs on s.id = fs.COD_idSales INNER JOIN customers as cu on cu.idCliente = s.idCustomer INNER JOIN contacts as co on co.idContacts = s.idContact INNER JOIN usuario as u on s.idUsuario = u.idusuario");
                                        }else{
                                          $query = mysqli_query($conexion, "SELECT  fs.url, s.id,s.Oportunidad,cu.Company,s.Status,s.Priorit,s.MRC,u.usuario,s.Detalle,co.celular,s.`Expected Close`, co.nombre, s.FCV, s.`One Shot`, s.Producto, s.Propuesta FROM sales as s inner join file_sales as fs on s.id = fs.COD_idSales INNER JOIN customers as cu on cu.idCliente = s.idCustomer INNER JOIN contacts as co on co.idContacts = s.idContact INNER JOIN usuario as u on s.idUsuario = u.idusuario where s.idUsuario = $id;");
                                        }
                                      


                                        $result = mysqli_num_rows($query);
                                        if ($result > 0) {
                                          while ($data = mysqli_fetch_assoc($query)) { ?>
                                            <tr>
                                              <td><?php echo $data['id']; ?></td>
                                              <td><?php echo $data['Added']; ?></td>
                                              <td><?php echo $data['Oportunidad']; ?></td>
                                              <td><?php echo $data['Company']; ?></td>
                                              <td>
                                              <?php
                                              $status = $data['Status'];
                                              if ($status == "Lead") {
                                                echo '<span class="badge bg-success text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                                              } elseif ($status == "Qualified") {
                                                echo '<span class="badge bg-info text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                                              } elseif ($status == "Proposal") {
                                                echo '<span class="badge bg-primary text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                                              } elseif ($status == "Negotiation") {
                                                echo '<span class="badge bg-warning text-dark badge-lg" style="font-size: 20px;">'.$status.'</span>';
                                              } elseif ($status == "Ganada") {
                                                echo '<span class="badge bg-secondary text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                                              } else  {
                                                echo '<span class="badge bg-danger text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                                              } 
                                              ?>
                                              </td>
                                              <td>
                                                <?php
                                                  $priority = $data['Priorit'];
                                                  if ($priority == 'High') {
                                                    echo '<div class="btn btn-warning btn-icon-split">
                                                        <span class="icon text-white-50">
                                                          <i class="fas fa-exclamation-triangle"></i>
                                                        </span>
                                                        <span class="text">' . $priority . '</span>
                                                      </div>';
                                                  } elseif ($priority == 'Medium') {
                                                    echo '<div class="btn btn-info btn-icon-split">
                                                        <span class="icon text-white-50">
                                                          <i class="fas fa-info-circle"></i>
                                                        </span>
                                                        <span class="text">' . $priority . '</span>
                                                      </div>';
                                                  } elseif ($priority == 'Low') {
                                                    echo '<div class="btn btn-success btn-icon-split">
                                                        <span class="icon text-white-50">
                                                          <i class="fas fa-check"></i>
                                                        </span>
                                                        <span class="text">' . $priority . '</span>
                                                      </div>';
                                                  } else {
                                                    echo $priority;
                                                  }
                                                ?>
                                              </td>

                                              <td><?php echo $data['MRC']; ?></td>
                                              <td><?php echo $data['usuario']; ?></td>                                       
                                              <td><?php echo $data['Detalle']; ?></td>
                                              <td><?php echo $data['celular']; ?></td>
                                              <td><?php echo $data['Expected Close']; ?></td>
                                              <td><?php echo $data['nombre']; ?></td>
                                              <td><?php echo $data['FCV']; ?></td>
                                              <td><?php echo $data['One Shot']; ?></td>
                                              <td><?php echo $data['Producto']; ?></td>

                                              <td>
                                                <?php if ($data['Propuesta'] !== 'none') {?>
                                                <button onclick="openModelPDF('<?php echo $url .'/'. $data['url']; ?>')" class="btn btn-primary" style="background-color: orange; color: white;" type="button"><i class="far fa-eye" style="color: white;"></i></button>								  
                                                    <a href="<?php echo $url .'/'. $data['url']; ?>" target="_black" class="btn btn-primary" style="background-color: blue;"><i class="fas fa-file-pdf"></i></a>
                                                <?php 
                                              }else { 
                                              ?>	<div class="alert alert-info">Sin documento</div>
                                              <?php } ?>
                                            
                                              <td>
                                                
                                                  

                                                  <?php if (verificarmostrarDatos(mostrarDatos(6), 3) != -1) { ?>
                                                <a href="editar_sales1.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                                <?php } ?>
                                                <?php if (verificarmostrarDatos(mostrarDatos(6), 4) != -1) { ?>
                                                <form action="eliminar_sale.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                                                  <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                                </form>
                                                
                                                <?php } ?>
                                              </td>
                                            
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