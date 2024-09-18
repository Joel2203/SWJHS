<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Orden de Trabajo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Formulario de Orden de Trabajo</h2>
        <form action="./orden/trabajo.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="web">Web</label>
                <input type="text" class="form-control" id="web" name="web" required>
            </div>
            <div class="form-group">
                <label for="numero_orden">Nº Orden</label>
                <input type="text" class="form-control" id="numero_orden" name="numero_orden" required>
            </div>
            <div class="form-group">
                <label for="fecha_orden">Fecha Orden</label>
                <input type="date" class="form-control" id="fecha_orden" name="fecha_orden" required>
            </div>
            <div class="form-group">
                <label for="cliente">Cliente</label>
                <input type="text" class="form-control" id="cliente" name="cliente" required>
            </div>
            <div class="form-group">
                <label for="descripcion_trabajo">Descripción del trabajo a realizar</label>
                <textarea class="form-control" id="descripcion_trabajo" name="descripcion_trabajo" rows="3" required></textarea>
            </div>
            <h4 class="mt-4">Productos</h4>
            <div id="productos">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad[]" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion[]" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="impuestos">Impuestos</label>
                        <input type="number" class="form-control" id="impuestos" name="impuestos[]" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="precio_unitario">Precio unitario S/.</label>
                        <input type="number" class="form-control" id="precio_unitario" name="precio_unitario[]" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="total">Total S/.</label>
                        <input type="number" class="form-control" id="total" name="total[]" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProduct()">Agregar Producto</button>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Enviar</button>
        </form>
    </div>

    <script>
        function addProduct() {
            const productDiv = document.createElement('div');
            productDiv.className = 'form-row';
            productDiv.innerHTML = `
                <div class="form-group col-md-2">
                    <input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" required>
                </div>
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" name="descripcion[]" placeholder="Descripción" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" class="form-control" name="impuestos[]" placeholder="Impuestos" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" class="form-control" name="precio_unitario[]" placeholder="Precio unitario S/." required>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" class="form-control" name="total[]" placeholder="Total S/." required>
                </div>
            `;
            document.getElementById('productos').appendChild(productDiv);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
