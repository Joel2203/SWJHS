<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    #map { height: 400px; }
</style>
<div class="modal" id="exampleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registrar Nuevo Evento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form name="formEvento" id="formEvento" action="nuevoEvento.php" class="form-horizontal" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="asignado">Asignado</label>
            <input type="text" readonly value="<?php echo $_SESSION['nombre']?> - <?php echo $_SESSION['email']?>" placeholder="Ingrese asignado" name="asignado" id="asignado" class="form-control">
          </div>

          <div class="form-group">
            <label for="asunto">Asunto</label>
            <input type="text" placeholder="Ingrese asunto" name="asunto" id="asunto" class="form-control" required>
          </div>

          <div class="form-group row d-flex justify-content-center">
            <div class="form-group" style="display: flex; align-items: center;">
              <label for="fecha_inicio" class="col-sm-12 control-label" style="flex: 1;">Fecha Inicio</label>
              <div class="col-sm-10" style="flex: 2;">
                <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" placeholder="Fecha Inicio">
              </div>
              <label for="hora_inicio" class="col-sm-12 control-label" style="flex: 1;">Hora Inicio</label>
              <div class="col-sm-10" style="flex: 2;">
                <input type="time" class="form-control" name="hora_inicio" id="hora_inicio" placeholder="Hora Inicio">
              </div>
            </div>

            <div class="form-group" style="display: flex; align-items: center;">
              <label for="fecha_fin" class="col-sm-12 control-label" style="flex: 1;">Fecha Final</label>
              <div class="col-sm-10" style="flex: 2;">
                <input type="text" class="form-control" name="fecha_fin" id="fecha_fin" placeholder="Fecha Final">
              </div>
              <label for="hora_final" class="col-sm-12 control-label" style="flex: 1;">Hora Final</label>
              <div class="col-sm-10" style="flex: 2;">
                <input type="time" class="form-control" name="hora_final" id="hora_final" placeholder="Hora Final">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="ubicacion">Ubicación</label>
            <input type="text" placeholder="Ingrese ubicación(opcional)" name="ubicacion" id="ubicacion" class="form-control">
          </div>
 


          <div id="map" ></div>

          <!-- Script para el mapa y la búsqueda -->
          <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
          <script>
              var map = L.map('map').setView([-12.0464, -77.0428], 13);

              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  maxZoom: 19,
              }).addTo(map);

              map.on('click', function(e) {
                  var latlng = e.latlng;
                  var geocodeUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`;
                  fetch(geocodeUrl)
                      .then(response => response.json())
                      .then(data => {
                          var address = data.display_name;
                          document.getElementById('ubicacion').value = address;
                      });
              });
 
          </script>

          <!-- Campo para almacenar la ubicación -->
          <input type="hidden" id="ubicacion" name="ubicacion">

          <div class="form-group">
            <label for="mostrar_hora">Mostrar hora como</label>
            <select name="mostrar_hora" id="mostrar_hora" class="form-control">
              <option value="--Ninguno--">--Ninguno--</option>
              <option value="Ocupada">Ocupada</option>
              <option value="Fuera de la oficina">Fuera de la oficina</option>
              <option value="Disponible">Disponible</option>
            </select>
          </div>

          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
          </div>

          <div class="form-group">
            <input type="hidden" value="<?php echo $_SESSION['rol']?>" placeholder="Ingrese código de rol" name="COD_idrol" id="COD_idrol" class="form-control">
          </div>

          <div class="form-group">
                <label for="recurrente">Oportunidad</label>
                <?php
                $id = $_SESSION['idUser'];
                if($_SESSION['rol']==1){
                  $query_customer = mysqli_query($conexion, "SELECT * FROM `sales`");
                }else{
                  $query_customer = mysqli_query($conexion, "SELECT * FROM `sales` where idUsuario = $id ");
                }
                
                $resultado_customer = mysqli_num_rows($query_customer);
                mysqli_close($conexion);
                ?>
                <select name="COD_idoportunidad" id="COD_idoportunidad" class="form-control" required>
                    <?php
                    if ($resultado_customer > 0) {
                        while ($customer = mysqli_fetch_array($query_customer)) {
                    ?>
                            <option value="<?php echo $customer["id"]; ?>"><?php echo $customer["Oportunidad"] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar Evento</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
        </div>
      </form>
    </div>
  </div>
</div>
