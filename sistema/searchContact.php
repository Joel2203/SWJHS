<?php 
include "../conexion.php";
// Verificamos si se recibió un valor a través de POST
$id = $_POST['id'];

if(isset($_POST['inputValue'])) {
    // Sanitizamos y guardamos el valor en una variable
    $searchQuery = htmlspecialchars($_POST['inputValue']);

    if($id == 1){
        $sql =  "SELECT * FROM contacts WHERE nombre LIKE '%$searchQuery%' OR segundo_nombre LIKE '%$searchQuery%' OR apellido_paterno LIKE '%$searchQuery%' OR apellido_materno LIKE '%$searchQuery%';";
    }else{
        $sql =  "SELECT * FROM contacts WHERE COD_idusuario = $id and (nombre LIKE '%$searchQuery%' OR segundo_nombre LIKE '%$searchQuery%' OR apellido_paterno LIKE '%$searchQuery%' OR apellido_materno LIKE '%$searchQuery%');";
    }
    $query_combo = mysqli_query($conexion,$sql);

}

?>    

<div class="d-flex justify-content-center">
    <select name="COD_idContact" id="COD_idContact" class="form-control">
        <?php
        
            while ($customer = mysqli_fetch_array($query_combo)) {
        ?>
                <option value="<?php echo $customer["idContacts"]; ?>"><?php echo $customer["nombre"] ?> <?php echo $customer["segundo_nombre"] ?> <?php echo $customer["apellido_paterno"] ?> <?php echo $customer["apellido_materno"] ?></option>
        <?php
                
        }
        ?>
    </select>
    <p id="valores"></p>
    <a href="agregar_contact.php" id="btnSubmit" class="btn btn-primary ml-2">Nuevo</a>
</div>
 