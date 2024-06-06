<?php include_once "includes/header.php"; 
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
?>

 
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
     
            </div>
        </div>
    </div>


        <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>


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

 <!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/all.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/sweetalert2@10.js"></script>
 
<script type="text/javascript" src="js/producto.js"></script>


 

<?php include_once "includes/footer.php"; ?>