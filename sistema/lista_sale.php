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
      <li class="nav-item">
        <a class="nav-link" id="wizard3-tab" data-toggle="pill" href="#wizard3" role="tab" aria-controls="wizard3" aria-selected="false">
          <div class="wizard-step-icon">3</div>
          <div class="wizard-step-text">
            <div class="wizard-step-text-name">Preferences</div>
            <div class="wizard-step-text-details">Notification and account options</div>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="wizard4-tab" data-toggle="pill" href="#wizard4" role="tab" aria-controls="wizard4" aria-selected="false">
          <div class="wizard-step-icon">4</div>
          <div class="wizard-step-text">
            <div class="wizard-step-text-name">Review &amp; Submit</div>
            <div class="wizard-step-text-details">Review and submit changes</div>
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

<div class="row">
  <div class="col-lg-12">
    <div class="table-responsive">
      <table class="mi-tabla" id="table">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Prioridad</th>
            <th>MRC</th>
            <th>Empresa</th>
            <th>Último contacto</th>
            <th>Cierre esperado</th>
            <th>Teléfono</th>
            <th>Detalle</th>
            <th>Propietario de cuenta</th>
            <th>Añadido</th>
            <th>Contacto de cliente</th>
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

          $query = mysqli_query($conexion, "SELECT * FROM `sales`");
          $result = mysqli_num_rows($query);
          if ($result > 0) {
            while ($data = mysqli_fetch_assoc($query)) { ?>
              <tr>
                  <td><?php echo $data['id']; ?></td>
                <td><?php echo $data['name']; ?></td>
                <td>
                <?php
                $status = $data['status'];
                if ($status == "Lead") {
                  echo '<span class="badge bg-success text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                } elseif ($status == "Qualified") {
                  echo '<span class="badge bg-info text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                } elseif ($status == "Proposal") {
                  echo '<span class="badge bg-primary text-white badge-lg" style="font-size: 20px;">'.$status.'</span>';
                } elseif ($status == "Negotiation") {
                  echo '<span class="badge bg-warning text-dark badge-lg" style="font-size: 20px;">'.$status.'</span>';
                } elseif ($status == "Closed") {
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
                    $priority = $data['priority'];
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

                <td><?php echo $data['mrc']; ?></td>
                <td><?php echo $data['company']; ?></td>
                <td><?php echo $data['last_contact']; ?></td>
                <td><?php echo $data['expected_close']; ?></td>
                <td><?php echo $data['phone']; ?></td>
                <td><?php echo $data['detalle']; ?></td>
                <td><?php echo $data['account_owner']; ?></td>
                <td><?php echo $data['added']; ?></td>
                <td><?php echo $data['contacto_cliente']; ?></td>
                <td><?php echo $data['fcv']; ?></td>
                <td><?php echo $data['one_shot']; ?></td>
                <td><?php echo $data['producto']; ?></td>
                <td><?php echo $data['propuesta']; ?></td>
                <?php if ($_SESSION['rol'] == 1) { ?>
                <td>
                  <a href="editar_cliente.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                  <form action="eliminar_cliente.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                  </form>
                </td>
                <?php } ?>
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
 
      <div class="tab-pane fade" id="wizard2" role="tabpanel" aria-labelledby="wizard2-tab">
        <div class="row justify-content-center">
          <div class="col-xxl-6 col-xl-12">
          <div class="container-fluid">

<!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Customers</h1>
                <a href="agregar_customer.php" class="btn btn-primary">Nuevo</a>
            </div>

            <div class="row">
                <div class="col-lg-12">

                    <div class="table-responsive">
                        <table class="mi-tabla" id="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>RUC</th>
                                    <th>URL</th>
                                    <th>Tipo_Contacto</th>
                                    <th>Contact_Name</th>
                                    <th>Apellido_Paterno</th>
                                    <th>Apellido_Materno</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Direccion</th>
                                    <th>Distrito</th>
                                    <th>Provincia</th>
                                    <th>Departamento</th>
                                    <th>Pais</th>
                                    <th>Cargo</th>
                                    <th>Cantidad_Empleados</th>
                                    <th>OrigenCliente</th>
                                    <?php if ($_SESSION['rol'] == 1) { ?>
                                    <th>ACCIONES</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "../conexion.php";

                                $query = mysqli_query($conexion, "SELECT * FROM customers as c inner join typecustomer as t on c.idCliente = t.COD_idCliente WHERE t.type =2");
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
                                            <td><?php echo $data['Tipo_Contacto']; ?></td>
                                            <td><?php echo $data['Contact_Name']; ?></td>
                                            <td><?php echo $data['Apellido_Paterno']; ?></td>
                                            <td><?php echo $data['Apellido_Materno']; ?></td>
                                            <td><?php echo $data['Email']; ?></td>
                                            <td><?php echo $data['Phone']; ?></td>
                                            <td><?php echo $data['Direccion']; ?></td>
                                            <td><?php echo $data['Distrito']; ?></td>
                                            <td><?php echo $data['Provincia']; ?></td>
                                            <td><?php echo $data['Departamento']; ?></td>
                                            <td><?php echo $data['Pais']; ?></td>
                                            <td><?php echo $data['Cargo']; ?></td>
                                            <td><?php echo $data['Cantidad_Empleados']; ?></td>
                                            <td><?php echo $data['OrigenCliente']; ?></td>

                                            <?php if ($_SESSION['rol'] == 1) { ?>
                                            <td>
                                                <a href="editar_customer.php?id=<?php echo $data['idCliente']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                                <form action="eliminar_customer.php?id=<?php echo $data['idCliente']; ?>" method="post" class="confirmar d-inline">
                                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                                </form>
                                            </td>
                                            <?php } ?>
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
      <div class="tab-pane fade" id="wizard3" role="tabpanel" aria-labelledby="wizard3-tab">
        <div class="row justify-content-center">
          <div class="col-xxl-6 col-xl-8">
            <h3 class="text-primary">Step 3</h3>
            <h5 class="card-title mb-4">Choose when you want to receive email notifications</h5>
            <form>
              <div class="form-check mb-2">
                <input class="form-check-input" id="checkAccountChanges" type="checkbox" checked="">
                <label class="form-check-label" for="checkAccountChanges">Changes made to your account</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" id="checkAccountGroups" type="checkbox" checked="">
                <label class="form-check-label" for="checkAccountGroups">Changes are made to groups you're part of</label>
              </div>
              <!-- Add more checkboxes here as needed -->
              <hr class="my-4">
              <div class="d-flex justify-content-between">
                <button class="btn btn-light" type="button" data-target="#wizard2" data-toggle="pill">Previous</button>
                <button class="btn btn-primary" type="button" data-target="#wizard4" data-toggle="pill">Next</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="wizard4" role="tabpanel" aria-labelledby="wizard4-tab">
        <div class="row justify-content-center">
          <div class="col-xxl-6 col-xl-8">
            <h3 class="text-primary">Step 4</h3>
            <h5 class="card-title mb-4">Review the following information and submit</h5>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Username:</em></div>
              <div class="col">username</div>
            </div>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Name:</em></div>
              <div class="col">Valerie Luna</div>
            </div>
            <!-- Add more information here as needed -->
            <hr class="my-4">
            <div class="d-flex justify-content-between">
              <button class="btn btn-light" type="button" data-target="#wizard3" data-toggle="pill">Previous</button>
              <button class="btn btn-primary" type="button">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php include_once "includes/footer.php"; ?>