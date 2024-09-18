<?php include_once "includes/header.php";
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Órdenes de Trabajo</h1>
        <form action="./includes/excel_work.php" method="POST">
        <input type="hidden" class="form-control" name="user" value="<?php echo $_SESSION['idUser']; ?>">
        <br>
        <button type="submit" class="btn btn-success"> <img src="./img/icon-excel.png" alt="Excel"> Descargar en excel</button>
        </form>
        <a href="registro_work.php" class="btn btn-primary">Nuevo</a>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="table-responsive">
                <table class="mi-tabla" id="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>ACCIONES</th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Web</th>
                            <th>Nº Orden</th>
                            <th>Fecha Orden</th>
                            <th>Cliente</th>
                            <th>Descripción del Trabajo</th>
                            <th>Datos de Facturación</th>
                            <th>Datos Empresa</th>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Impuestos</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                            <th>Observaciones</th>
                            <th>Notas de Pago</th>
                            <th>Total Sin IVA</th>
                            <th>IVA</th>
                            <th>Total con IVA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "SELECT * FROM `work`;");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td>
                                        <!-- Aquí puedes añadir botones de acciones, como ver, editar y eliminar -->
                                        <a href="ver_work.php?id=<?php echo $data['id']; ?>" class="btn btn-primary"><i class="far fa-eye"></i></a>
                                        <a href="editar_work.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                        <form action="eliminar_work.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i></button>
                                        </form>
                                    </td>
                                    <td><?php echo $data['id']; ?></td>
                                    <td><?php echo $data['name']; ?></td>
                                    <td><?php echo $data['address']; ?></td>
                                    <td><?php echo $data['phone']; ?></td>
                                    <td><?php echo $data['website']; ?></td>
                                    <td><?php echo $data['order_number']; ?></td>
                                    <td><?php echo $data['order_date']; ?></td>
                                    <td><?php echo $data['client_name']; ?></td>
                                    <td><?php echo $data['job_description']; ?></td>
                                    <td><?php echo $data['billing_data']; ?></td>
                                    <td><?php echo $data['company_data']; ?></td>
                                    <td><?php echo $data['quantity']; ?></td>
                                    <td><?php echo $data['description']; ?></td>
                                    <td><?php echo $data['taxes']; ?></td>
                                    <td><?php echo $data['unit_price']; ?></td>
                                    <td><?php echo $data['total']; ?></td>
                                    <td><?php echo $data['observations']; ?></td>
                                    <td><?php echo $data['payment_notes']; ?></td>
                                    <td><?php echo $data['total_amount']; ?></td>
                                    <td><?php echo $data['total_tax']; ?></td>
                                    <td><?php echo $data['total_with_tax']; ?></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
<!-- End of Main Content -->
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>
    function openModelPDF(url) {
        $('#modalPdf').modal('show');
        $('#iframePDF').attr('src', url);
    }
</script>

<?php include_once "includes/footer.php"; ?>
