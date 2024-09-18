<?php

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= ordenes_de_trabajo.xls");

?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<div class="table-responsive">
    <table class="mi-tabla" id="table">
        <thead class="thead-dark">
            <tr>
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
            session_start();
            include "../../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM `work`;");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr>
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
