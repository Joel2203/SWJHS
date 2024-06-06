<div class="modal" id="modalUpdateEvento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="formUpdateEvento" id="formUpdateEvento" action="UpdateEvento.php" class="form-horizontal" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                      <label for="asignado">Asignado</label>
                      <input type="text" readonly value="<?php echo $_SESSION['nombre']?> - <?php echo $_SESSION['email']?>" placeholder="Ingrese asignado" name="asignado" id="asignado" class="form-control">
                    </div>
                    <input type="hidden" name="idEvento" id="idEvento"> <!-- Campo oculto para el ID del evento -->
                    <div class="form-group">
                        <label for="asuntoUpdate">Asunto</label>
                        <input type="text" placeholder="Ingrese asunto" name="asuntoUpdate" id="asuntoUpdate" class="form-control">
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
                        <label for="ubicacionUpdate">Ubicación</label>
                        <input type="text" placeholder="Ingrese ubicación(opcional)" name="ubicacionUpdate" id="ubicacionUpdate" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="mostrar_horaUpdate">Mostrar hora como</label>
                        <select name="mostrar_horaUpdate" id="mostrar_horaUpdate" class="form-control">
                            <option value="--Ninguno--">--Ninguno--</option>
                            <option value="Ocupada">Ocupada</option>
                            <option value="Fuera de la oficina">Fuera de la oficina</option>
                            <option value="Disponible">Disponible</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcionUpdate">Descripción</label>
                        <textarea name="descripcionUpdate" id="descripcionUpdate" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <input type="hidden" value="<?php echo $_SESSION['rol']?>" placeholder="Ingrese código de rol" name="COD_idrolUpdate" id="COD_idrolUpdate" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Actualizar Evento</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>
