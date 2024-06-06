<?php 
include "../conexion.php";

$selectedValue = $_POST['selectedValue'];

$query_combo = mysqli_query($conexion, "SELECT * FROM contacts WHERE COD_idAccount = '$selectedValue'");

?>

<div class="form-group">
                                <label for="recurrente">Contact</label>
                                <?php
                                    $query_combo = mysqli_query($conexion, "SELECT * FROM contacts WHERE COD_idAccount ='$selectedValue'");
                                    $resultado_customer = mysqli_num_rows($query_combo);
                                    //mysqli_close($conexion);
                                    
                                    ?>
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
                                    
                            </div>

