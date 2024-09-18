<?php
// Incluir archivo de conexión
include "../../conexion.php";

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$web = $_POST['web'];
$fecha_orden = $_POST['fecha_orden'];
$cliente = $_POST['cliente'];
$descripcion_trabajo = $_POST['descripcion_trabajo'];
$observaciones = $_POST['observaciones'];

$cantidades = $_POST['cantidad'];
$descripciones = $_POST['descripcion'];
$impuestos = $_POST['impuestos'];
$precios_unitarios = $_POST['precio_unitario'];
$totales = $_POST['total'];

// Inserción en la tabla orden_trabajo
$sql_orden_trabajo = "INSERT INTO orden_trabajo (fecha_orden, nombre_cliente, direccion_cliente, telefono_cliente, web_cliente, datos_facturacion, datos_empresa) 
VALUES ('$fecha_orden', '$nombre', '$direccion', '$telefono', '$web', 'Facturación a nombre de $nombre', 'Empresa XYZ')";

if ($conexion->query($sql_orden_trabajo) === TRUE) {
    $id_orden = $conexion->insert_id;
    $numero_orden = str_pad($id_orden, 4, '0', STR_PAD_LEFT);

    // Actualizar el numero_orden
    $sql_update = "UPDATE orden_trabajo SET numero_orden = '$numero_orden' WHERE id = $id_orden";
    $conexion->query($sql_update);

    // Inserción en la tabla detalle_orden
    for ($i = 0; $i < count($cantidades); $i++) {
        $cantidad = $cantidades[$i];
        $descripcion = $descripciones[$i];
        $impuesto = $impuestos[$i];
        $precio_unitario = $precios_unitarios[$i];
        $total = $totales[$i];

        $sql_detalle_orden = "INSERT INTO detalle_orden (id_orden, cantidad, descripcion, impuestos, precio_unitario, total) 
        VALUES ($id_orden, $cantidad, '$descripcion', $impuesto, $precio_unitario, $total)";
        
        $conexion->query($sql_detalle_orden);
    }

    echo "Orden de trabajo y detalles insertados correctamente.";
    header("Location: ../lista_orden_de_trabajo.php"); 
} else {
    echo "Error: " . $sql_orden_trabajo . "<br>" . $conexion->error;
}

$conexion->close();
?>
