 
<body>
    <div class="container mt-5">
        <h2 class="text-center">Formulario de Cotización</h2>
        <form action="./factura/factura.php" method="post">
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="senores">Empresa</label>
                <input type="text" class="form-control" id="senores" name="senores" required>
            </div>
            <div class="form-group">
                <label for="atencion">Atención</label>
                <input type="text" class="form-control" id="atencion" name="atencion" required>
            </div>
            <div class="form-group">
                <label for="dni_ruc">DNI/RUC</label>
                <input type="text" class="form-control" id="dni_ruc" name="dni_ruc" required>
            </div>
            <h4 class="mt-4">Productos</h4>
            <div id="productos">
                <div class="form-row product-row">
                    <div class="form-group col-md-2">
                        <label for="codigo">Código</label>
                        <select class="form-control codigo" name="codigo[]" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="descripcion">Descripción del Producto</label>
                        <input type="text" class="form-control descripcion" name="descripcion[]" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control cantidad" name="cantidad[]" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="unidad">Unidad S/.</label>
                        <input type="number" class="form-control unidad" name="unidad[]" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="total">Total S/.</label>
                        <input type="number" class="form-control total" name="total[]" readonly>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProduct()">Agregar Producto</button>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        const productos = [
            { codigo: 'MED001', descripcion: 'Cama hospitalaria estándar', preciounidad: 2500.00 },
            { codigo: 'MED002', descripcion: 'Silla de ruedas plegable', preciounidad: 1200.00 },
            { codigo: 'MED003', descripcion: 'Mesa de examen ajustable', preciounidad: 1800.00 },
            { codigo: 'MED004', descripcion: 'Monitor de signos vitales', preciounidad: 8500.00 },
            { codigo: 'MED005', descripcion: 'Lámpara quirúrgica', preciounidad: 4500.00 },
            { codigo: 'MED006', descripcion: 'Carro de emergencia', preciounidad: 3000.00 },
            { codigo: 'MED007', descripcion: 'Colchón antiescaras', preciounidad: 1500.00 },
            { codigo: 'MED008', descripcion: 'Desfibrilador automático', preciounidad: 12000.00 },
            { codigo: 'MED009', descripcion: 'Mesa de mayo', preciounidad: 800.00 },
            { codigo: 'MED010', descripcion: 'Paro cardíaco', preciounidad: 8500.00 },
            { codigo: 'MED011', descripcion: 'Bomba de infusión', preciounidad: 6500.00 },
            { codigo: 'MED012', descripcion: 'Ventilador mecánico', preciounidad: 15000.00 },
            { codigo: 'MED013', descripcion: 'Escalera de dos peldaños', preciounidad: 300.00 },
            { codigo: 'MED014', descripcion: 'Lámpara de pie LED', preciounidad: 700.00 },
            { codigo: 'MED015', descripcion: 'Equipo de succión', preciounidad: 2000.00 },
            { codigo: 'MED016', descripcion: 'Báscula digital', preciounidad: 1200.00 },
            { codigo: 'MED017', descripcion: 'Mobiliario para instrumental', preciounidad: 2300.00 },
            { codigo: 'MED018', descripcion: 'Camilla de transporte', preciounidad: 3500.00 },
            { codigo: 'MED019', descripcion: 'Estetoscopio electrónico', preciounidad: 1500.00 },
            { codigo: 'MED020', descripcion: 'Oxímetro de pulso', preciounidad: 800.00 }
        ];

        function populateProductOptions() {
            const selectElements = document.querySelectorAll('.codigo');
            selectElements.forEach(select => {
                productos.forEach(producto => {
                    const option = document.createElement('option');
                    option.value = producto.codigo;
                    option.textContent = producto.codigo;
                    select.appendChild(option);
                });
            });
        }

        function addProduct() {
            const currentProductRows = document.querySelectorAll('.product-row').length;
            if (currentProductRows >= 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Límite alcanzado',
                    text: 'Solo se pueden agregar hasta 6 productos.',
                });
                return;
            }

            const productDiv = document.createElement('div');
            productDiv.className = 'form-row product-row';
            productDiv.innerHTML = `
                <div class="form-group col-md-2">
                    <select class="form-control codigo" name="codigo[]" required>
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <input type="text" class="form-control descripcion" name="descripcion[]" readonly>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" class="form-control cantidad" name="cantidad[]" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" class="form-control unidad" name="unidad[]" readonly>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" class="form-control total" name="total[]" readonly>
                </div>
            `;
            document.getElementById('productos').appendChild(productDiv);
            populateProductOptions();
            attachEventListeners(productDiv);
        }

        function attachEventListeners(parent) {
            const codigoSelects = parent.querySelectorAll('.codigo');
            const cantidadInputs = parent.querySelectorAll('.cantidad');
            const unidadInputs = parent.querySelectorAll('.unidad');
            const totalInputs = parent.querySelectorAll('.total');

            codigoSelects.forEach((select, index) => {
                select.addEventListener('change', () => updateProductDetails(select, parent));
            });

            cantidadInputs.forEach((input, index) => {
                input.addEventListener('input', () => updateTotal(input, unidadInputs[index], totalInputs[index]));
            });
        }

        function updateProductDetails(select, parent) {
            const selectedCode = select.value;
            const producto = productos.find(p => p.codigo === selectedCode);
            if (producto) {
                parent.querySelector('.descripcion').value = producto.descripcion;
                parent.querySelector('.unidad').value = producto.preciounidad.toFixed(2);
                updateTotal(parent.querySelector('.cantidad'), parent.querySelector('.unidad'), parent.querySelector('.total'));
            }
        }

        function updateTotal(cantidadInput, unidadInput, totalInput) {
            const cantidad = parseFloat(cantidadInput.value) || 0;
            const unidad = parseFloat(unidadInput.value) || 0;
            totalInput.value = (cantidad * unidad).toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', () => {
            populateProductOptions();
            attachEventListeners(document);
        });
    </script>
</body>
</html>
