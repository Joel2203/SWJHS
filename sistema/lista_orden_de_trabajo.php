<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lista de Órdenes de Trabajo</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="table-responsive">
                <table class="table table-bordered" id="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nº Orden</th>
                            <th>Fecha Orden</th>
                            <th>Nombre Cliente</th>
                            <th>Dirección Cliente</th>
                            <th>Teléfono Cliente</th>
                            <th>Web Cliente</th>
                            <th>Datos Facturación</th>
                            <th>Datos Empresa</th>
                            <?php if ($_SESSION['rol'] == 1) { ?>
                            <th>ACCIONES</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "SELECT * FROM orden_trabajo");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><?php echo $data['id']; ?></td>
                                    <td><?php echo $data['numero_orden']; ?></td>
                                    <td><?php echo $data['fecha_orden']; ?></td>
                                    <td><?php echo $data['nombre_cliente']; ?></td>
                                    <td><?php echo $data['direccion_cliente']; ?></td>
                                    <td><?php echo $data['telefono_cliente']; ?></td>
                                    <td><?php echo $data['web_cliente']; ?></td>
                                    <td><?php echo $data['datos_facturacion']; ?></td>
                                    <td><?php echo $data['datos_empresa']; ?></td>
                                    <?php if ($_SESSION['rol'] == 1) { ?>
                                    <td>
                                       <a href="generar_pdf.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-file'></i></a>
                                        <a href="editar_orden_trabajo.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                        <form action="eliminar_orden_trabajo.php?id=<?php echo $data['id']; ?>" method="post" class="d-inline">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i></button>
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
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php include_once "includes/footer.php"; ?>
