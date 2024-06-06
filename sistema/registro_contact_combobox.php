<?php 
include "../conexion.php";

$selectedValue = $_POST['selectedValue'];


$query_combo = mysqli_query($conexion, "SELECT c.idContacts,c.nombre,c.segundo_nombre,c.apellido_paterno,c.apellido_materno from customers as cu INNER JOIN contacts as c on c.idContacts = cu.COD_idcontacto where cu.idCliente = '$selectedValue'");

?>

<div class="form-group">
                                <label for="recurrente">Contacto</label>
                                <?php
                                    $query_combo = mysqli_query($conexion, "SELECT c.idContacts,c.nombre,c.segundo_nombre,c.apellido_paterno,c.apellido_materno from customers as cu INNER JOIN contacts as c on c.idContacts = cu.COD_idcontacto where cu.idCliente ='$selectedValue'");
                                    $resultado_customer = mysqli_num_rows($query_combo);
                                    //mysqli_close($conexion);
                                    
                                    ?>
                                    <div class="d-flex">
                                    <select name="COD_idContact" id="COD_idContact" class="form-control"> 
                                    <?php
                                    if ($resultado_customer > 0) {
                                        while ($account = mysqli_fetch_array($query_combo)) {
                                    ?>
                                            <option value="<?php echo $account["idContacts"]; ?>"><?php echo $account["nombre"] ?> <?php echo $account["segundo_nombre"] ?> <?php echo $account["apellido_paterno"] ?> <?php echo $account["apellido_materno"] ?></option>
                                    <?php

                                        }
                                    }
                                    ?>
                                    </select>
                                    <a href="lista_contact.php" id="btnSubmit" class="btn btn-primary ml-2">Crear Nuevo</a>
                                    </div>
                            </div>

