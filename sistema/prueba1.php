<?php include_once "includes/header.php"; ?>

<div class="card">
  <div class="card-header">
    <ul class="nav nav-pills nav-justified flex-column flex-xl-row nav-wizard">
      <li class="nav-item">
            <a class="nav-link active" id="wizard1-tab" data-toggle="pill" href="#wizard1" role="tab" aria-controls="wizard1" aria-selected="true">
        <div class="wizard-step-icon">1</div>
        <div class="wizard-step-text">
            <div class="wizard-step-text-name">Account Setup</div>
            <div class="wizard-step-text-details">Basic details and information</div>
        </div>
        </a>
        </li>
        <li class="nav-item">
        <a class="nav-link" id="wizard2-tab" data-toggle="pill" href="#wizard2" role="tab" aria-controls="wizard2" aria-selected="false">
        <div class="wizard-step-icon">2</div>
        <div class="wizard-step-text">
            <div class="wizard-step-text-name">Customer Details</div>
            <div class="wizard-step-text-details">Current Customer</div>
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
                                    <h1 class="h3 mb-0 text-gray-800">Account</h1>
                                    <form action="./includes/excel_account.php" method="POST">
                                    <input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
                                    <br>
                                    <button type="sumbit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
                                    </form>
                                    <?php if (verificarmostrarDatos(mostrarDatos(0), 1) != -1) { ?>
                                    <a href="agregar_account.php" class="btn btn-primary">Nuevo</a>
                                    <?php } ?>
                                </div>

                                <div class="row">
                                <div class="table-responsive">
                                    <table class="mi-tabla" id="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ACCIONES</th>
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
                                                <th>Added</th>
                                                <th>Contacto</th>
                                               
                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include "../conexion.php";

                                            if ($_SESSION['rol'] == 1) {
                                                $query = mysqli_query($conexion, "SELECT * FROM customers as c INNER JOIN typecustomer as t ON c.idCliente = t.COD_idCliente INNER JOIN contacts as co on co.idContacts = c.COD_idcontacto WHERE t.type = 1");
                                            } else {
                                                $query = mysqli_query($conexion, "SELECT * FROM customers as c INNER JOIN typecustomer as t ON c.idCliente = t.COD_idCliente INNER JOIN contacts as co on co.idContacts = c.COD_idcontacto WHERE t.type = 1 AND c.COD_idusuario = " . $_SESSION['idUser']);
                                            }
                                            
                                            $result = mysqli_num_rows($query);
                                            if ($result > 0) {
                                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                                    <tr>
                                                        <td>
                                                            <a href="do_customer.php?id=<?php echo $data['idCliente']; ?>" class="btn btn-primary">
                                                            <i class="fas fa-check"></i>
                                                            </a>
                                                            <?php if (verificarmostrarDatos(mostrarDatos(0), 3) != -1) { ?>
                                                            <a href="editar_customer.php?id=<?php echo $data['idCliente']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                                            <?php } ?>
                                                            <?php if (verificarmostrarDatos(mostrarDatos(0), 4) != -1) { ?>
                                                            <form action="eliminar_customer.php?id=<?php echo $data['idCliente']; ?>" method="post" class="confirmar d-inline">
                                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                                            </form>
                                                            <?php } ?>
                                                        </td>            
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
                                                        <td><?php echo $data['AddedCustomers']; ?></td>
                                                        <td><?php echo $data["nombre"] ?> <?php echo $data["segundo_nombre"] ?> <?php echo $data["apellido_paterno"] ?> <?php echo $data["apellido_materno"] ?></td>
                                                                      
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
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
 
      <div class="tab-pane fade" id="wizard2" role="tabpanel" aria-labelledby="wizard2-tab">
        <div class="row justify-content-center">
          <div class="col-xxl-6 col-xl-12">
          <div class="container-fluid">

<!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Customers</h1>
                <form action="./includes/excel_customer.php" method="POST">
                <input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
                <br>
                <button type="sumbit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
                </form>
            </div>

            <div class="row">
                <div class="col-lg-12">

                    <div class="table-responsive">
                        <table class="mi-tabla" id="table">
                            <thead class="thead-dark">
                                <tr>
                                <th>ACCIONES</th>  
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
                                include "../conexion.php";
                                
                                if ($_SESSION['rol'] == 1) {
                                    $query = mysqli_query($conexion, "SELECT * FROM customers as c INNER JOIN typecustomer as t ON c.idCliente = t.COD_idCliente INNER JOIN contacts as co on co.idContacts = c.COD_idcontacto WHERE t.type = 2");
                                } else {
                                    $query = mysqli_query($conexion, "SELECT * FROM customers as c INNER JOIN typecustomer as t ON c.idCliente = t.COD_idCliente INNER JOIN contacts as co on co.idContacts = c.COD_idcontacto WHERE t.type = 2 AND c.COD_idusuario = " . $_SESSION['idUser']);
                                }

                                $result = mysqli_num_rows($query);
                                if ($result > 0) {
                                    while ($data = mysqli_fetch_assoc($query)) { ?>
                                        <tr>
                                        <td>
                                            <?php if (verificarmostrarDatos(mostrarDatos(0), 3) != -1) { ?>
                                                <a href="editar_customer.php?id=<?php echo $data['idCliente']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                                <?php } ?>
                                            <?php if (verificarmostrarDatos(mostrarDatos(0), 4) != -1) { ?>    
                                                <form action="eliminar_customer.php?id=<?php echo $data['idCliente']; ?>" method="post" class="confirmar d-inline">
                                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                                </form>
                                                <?php } ?>
                                        </td>
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

                </div>
            </div>


            </div>
          </div>
        </div>
      </div>
 
      
    </div>
  </div>
</div>



<?php include_once "includes/footer.php"; ?>