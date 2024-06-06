<?php include_once "includes/header.php"; 
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
?>

<style>
  @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Montserrat', sans-serif;
}
</style>

<div class="card">
  <div class="card-header">
    <ul class="nav nav-pills nav-justified flex-column flex-xl-row nav-wizard">
      <li class="nav-item">
            <a class="nav-link active" id="wizard1-tab" data-toggle="pill" href="#wizard1" role="tab" aria-controls="wizard1" aria-selected="true">
        <div class="wizard-step-icon">1</div>
        <div class="wizard-step-text">
            <div class="wizard-step-text-name">All Records</div>
            <div class="wizard-step-text-details">Basic details and information</div>
        </div>
        </a>
        </li>
    
      <li class="nav-item">
        <a class="nav-link" id="wizard3-tab" data-toggle="pill" href="#wizard3" role="tab" aria-controls="wizard3" aria-selected="false">
          <div class="wizard-step-icon">2</div>
          <div class="wizard-step-text">
            <div class="wizard-step-text-name">By status</div>
            <div class="wizard-step-text-details">Notification and account options</div>
          </div>
        </a>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content" id="cardTabContent">
        <div class="tab-pane fade show active" id="wizard1" role="tabpanel" aria-labelledby="wizard1-tab">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-12">
                    <div class="card-body">
                        <div class="tab-content" id="cardTabContent">
                            <!-- Wizard tab pane item 1-->
                            <div class="container-fluid">

                            <!-- Page Heading -->
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                              <h1 class="h3 mb-0 text-gray-800">Sales</h1>
                              <a href="registro_sales.php" class="btn btn-primary">Nuevo</a>
                            </div>

                            
                            <?php 
      include "../conexion.php";
     if ($_SESSION['rol'] == 1) {
      $sql = "SELECT
          (SELECT SUM(MRC) FROM sales WHERE tipo = 'PEN') AS sum_mrc_pen,
          (SELECT SUM(FCV) FROM sales WHERE tipo = 'PEN') AS sum_fcv_pen,
          (SELECT SUM(`One Shot`) FROM sales WHERE tipo = 'PEN') AS sum_one_shot_pen,
          (SELECT SUM(`One Shot`) FROM sales WHERE tipo = 'USD') AS sum_fcv_usd;";
  } else {
      $sql = "SELECT
          (SELECT SUM(MRC) FROM sales WHERE tipo = 'PEN' AND idUsuario = 1) AS sum_mrc_pen,
          (SELECT SUM(FCV) FROM sales WHERE tipo = 'PEN' AND idUsuario = 1) AS sum_fcv_pen,
          (SELECT SUM(`One Shot`) FROM sales WHERE tipo = 'PEN' AND idUsuario = 1) AS sum_one_shot_pen,
          (SELECT SUM(`One Shot`) FROM sales WHERE tipo = 'USD' AND idUsuario = 1) AS sum_fcv_usd;";
  }
      $query1 = mysqli_query($conexion, $sql); 
      $data = mysqli_fetch_assoc($query1);
      $a = $data['sum_mrc_pen'];
      $b = $data['sum_fcv_pen'];
      $c = $data['sum_one_shot_pen'];
      $d = $data['sum_fcv_usd'];
      
      ?>               
   <div  >
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suma de MRC (PEN)</h5>
                        <p class="card-text">
                            <?php
                                echo $a;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suma de FCV (PEN)</h5>
                        <p class="card-text">
                            <?php
                                echo $b;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suma de One Shot (PEN)</h5>
                        <p class="card-text">
                            <?php
                                echo $c;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suma de One Shot (USD)</h5>
                        <p class="card-text">
                            <?php
                                echo $d;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
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
                                            } elseif ($status == "Lost") {
                                              echo '<span class="badge bg-danger text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                                            } else {
                                              echo '<td>'.$status.'</td>';
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
                         
                        
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary" type="button" data-target="#wizard2" data-toggle="pill">Next</button>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    </div>
   
    <link rel="stylesheet" href="styles.css">
    
      <div class="tab-pane fade" id="wizard3" role="tabpanel" aria-labelledby="wizard3-tab">
      <!-- Page Heading -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
                              <h1 class="h3 mb-0 text-gray-800">Sales</h1>
                              <a href="registro_sales.php" class="btn btn-primary">Nuevo</a>
                            </div>

      <div class="container mt-4">
        <div class="row">
            <div class="col">
                <div class="kanban-board">
                    <?php
                    include "../conexion.php";

                    $columns = ['Lead', 'Qualified', 'Proposal', 'Negotiation', 'Ganada', 'Lost'];

                    foreach ($columns as $column) {
                        echo '<div class="column" id="' . strtolower($column) . '">';
                        echo '<h2>' . $column . '</h2>';
                        
                        $sql = "SELECT  fs.url, s.id,s.Oportunidad,cu.Company,s.Status,s.Priorit,s.MRC,u.usuario,s.Detalle,co.celular,s.`Expected Close`, co.nombre, s.FCV, s.`One Shot`, s.Producto, s.Propuesta FROM sales as s inner join file_sales as fs on s.id = fs.COD_idSales INNER JOIN customers as cu on cu.idCliente = s.idCustomer INNER JOIN contacts as co on co.idContacts = s.idContact INNER JOIN usuario as u on s.idUsuario = u.idusuario WHERE Status = '$column'";
                        $result = $conexion->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              echo '<div class="card mb-2" id="item-' . $row['id'] . '">' .
                              '<div class="card-body">' .
                              '<h5 class="card-title">' . $row['Oportunidad'] . '</h5>' .
                              '<p class="card-text">Company: ' . $row['Company'] . '</p>' .
                              '<p class="card-text">MRC: ' . $row['MRC'] . '</p>' .
                              '<p class="card-text">Detalle: ' . $row['Detalle'] . '</p>' .
                              '<a href="editar_sales1.php?id='.$row['id'].'" class="btn btn-primary">Actualizar</a>' .
                              '<form action="eliminar_sale.php?id=' . $row['id'] . '" method="post" class="confirmar d-inline">' .
                                  '<button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i>Eliminar</button>' .
                              '</form>' .
                              '</div>' .
                          '</div>';
                            }
                        } else {
                            echo '<p class="text-muted">No hay registros en esta columna</p>';
                        }

                        echo '</div>';
                    }

                    $conexion->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> <!-- Agrega esta línea -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        
        <script src="scriptKanban.js"></script>
      </div>
     
    </div>
  </div>
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
                  <button type="button" class="btn btn-primary" id="cerrarModal">Cerrar</button>
								</div>
							</div>
						</div>
					</div>
							</table>
						</div>

					</div>
				</div>

</div>
<script>
$(document).ready(function(){
    $('#cerrarModal').click(function(){
        $('#modalPdf').modal('hide');
    });
});
</script>
<!-- End of Main Content -->
 
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <script>
                            function openModelPDF(url) {
                                $('#modalPdf').modal('show');
                                $('#iframePDF').attr('src',url);
                            }
</script>



 <!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/all.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/sweetalert2@10.js"></script>
 
<script type="text/javascript" src="js/producto.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      language: {
        "decimal": "",
        "emptyTable": "No hay datos",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
        "infoFiltered": "(Filtro de _MAX_ total registros)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron coincidencias",
        "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
        },
        "aria": {
          "sortAscending": ": Activar orden de columna ascendente",
          "sortDescending": ": Activar orden de columna desendente"
        }
      }
    });
    var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
    searchForDetalle(usuarioid);
  });
</script>

 

 