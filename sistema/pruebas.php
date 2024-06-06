<?php include_once "includes/header.php"; ?>

<div class="card">
  <div class="card-header border-bottom">
    <!-- Wizard navigation-->
    <div class="nav nav-pills nav-justified flex-column flex-xl-row nav-wizard" id="cardTab" role="tablist">
      <!-- Wizard navigation item 1-->
      <a class="nav-item nav-link active" id="wizard1-tab" href="#wizard1" data-bs-toggle="tab" role="tab" aria-controls="wizard1" aria-selected="true">
        <div class="wizard-step-icon">1</div>
        <div class="wizard-step-text">
          <div class="wizard-step-text-name">Account Setup</div>
          <div class="wizard-step-text-details">Basic details and information</div>
        </div>
      </a>
      <!-- Wizard navigation item 2-->
      <a class="nav-item nav-link" id="wizard2-tab" href="#wizard2" data-bs-toggle="tab" role="tab" aria-controls="wizard2" aria-selected="false" tabindex="-1">
        <div class="wizard-step-icon">2</div>
        <div class="wizard-step-text">
          <div class="wizard-step-text-name">Billing Details</div>
          <div class="wizard-step-text-details">Credit card information</div>
        </div>
      </a>
      <!-- Wizard navigation item 3-->
      <a class="nav-item nav-link" id="wizard3-tab" href="#wizard3" data-bs-toggle="tab" role="tab" aria-controls="wizard3" aria-selected="false" tabindex="-1">
        <div class="wizard-step-icon">3</div>
        <div class="wizard-step-text">
          <div class="wizard-step-text-name">Preferences</div>
          <div class="wizard-step-text-details">Notification and account options</div>
        </div>
      </a>
      <!-- Wizard navigation item 4-->
      <a class="nav-item nav-link" id="wizard4-tab" href="#wizard4" data-bs-toggle="tab" role="tab" aria-controls="wizard4" aria-selected="false" tabindex="-1">
        <div class="wizard-step-icon">4</div>
        <div class="wizard-step-text">
          <div class="wizard-step-text-name">Review &amp; Submit</div>
          <div class="wizard-step-text-details">Review and submit changes</div>
        </div>
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="tab-content" id="cardTabContent">
      <!-- Wizard tab pane item 1-->
      <div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Account</h1>
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

						$query = mysqli_query($conexion, "SELECT * FROM customers as c inner join typecustomer as t on c.idCliente = t.COD_idCliente WHERE t.type = 1");
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
      <!-- Wizard tab pane item 2-->
      <!-- Begin Page Content -->
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
      <!-- Wizard tab pane item 3-->
      <div class="tab-pane py-5 py-xl-10 fade" id="wizard3" role="tabpanel" aria-labelledby="wizard3-tab">
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
              <div class="form-check mb-2">
                <input class="form-check-input" id="checkProductUpdates" type="checkbox" checked="">
                <label class="form-check-label" for="checkProductUpdates">Product updates for products you've purchased or starred</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" id="checkProductNew" type="checkbox" checked="">
                <label class="form-check-label" for="checkProductNew">Information on new products and services</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" id="checkPromotional" type="checkbox">
                <label class="form-check-label" for="checkPromotional">Marketing and promotional offers</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" id="checkSecurity" type="checkbox" checked="" disabled="">
                <label class="form-check-label" for="checkSecurity">Security alerts</label>
              </div>
              <hr class="my-4">
              <div class="d-flex justify-content-between">
                <button class="btn btn-light" type="button">Previous</button>
                <button class="btn btn-primary" type="button">Next</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Wizard tab pane item 4-->
      <div class="tab-pane py-5 py-xl-10 fade" id="wizard4" role="tabpanel" aria-labelledby="wizard4-tab">
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
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Organization Name:</em></div>
              <div class="col">Start Bootstrap</div>
            </div>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Location:</em></div>
              <div class="col">San Francisco, CA</div>
            </div>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Email Address:</em></div>
              <div class="col">name@example.com</div>
            </div>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Phone Number:</em></div>
              <div class="col">555-123-4567</div>
            </div>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Birthday:</em></div>
              <div class="col">06/10/1988</div>
            </div>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Credit Card Number:</em></div>
              <div class="col">**** **** **** 1111</div>
            </div>
            <div class="row small text-muted">
              <div class="col-sm-3 text-truncate"><em>Credit Card Expiration:</em></div>
              <div class="col">06/2024</div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-between">
              <button class="btn btn-light" type="button">Previous</button>
              <button class="btn btn-primary" type="button">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php include_once "includes/footer.php"; ?>