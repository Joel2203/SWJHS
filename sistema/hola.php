<script src="js/jquery.min.js"></script>
<?php
$memoria = memory_get_usage();
$memoriaEnMB = round($memoria / 1048576, 3); // Convertir a megabytes y redondear a 3 decimales
echo "Uso de memoria: " . $memoriaEnMB . " MB";
echo "<br>";

$memoriaPico = memory_get_peak_usage();
$memoriaPicoEnMB = round($memoriaPico / 1048576, 3); // Convertir a megabytes y redondear a 3 decimales
echo "Pico máximo de uso de memoria: " . $memoriaPicoEnMB . " MB";

echo "<br>";
$nombreProcesador = getenv('PROCESSOR_IDENTIFIER');

if ($nombreProcesador) {
    echo "Nombre de la familia del procesador: " . $nombreProcesador;
} else {
    echo "No se pudo obtener el nombre del procesador.";
}

echo "<br>";


$memoriaUtilizada = round(memory_get_peak_usage(true) / (1024 * 1024), 2);

echo "Cantidad de RAM utilizada por el script PHP: " . $memoriaUtilizada . " MB";

function getRAMUsage() {
    $memoryLimit = ini_get('memory_limit');
    $memoryLimit = convertToBytes($memoryLimit);

    $usedMemory = memory_get_usage(true);
    $freeMemory = $memoryLimit - $usedMemory;

    return [
        'total' => $memoryLimit,
        'used' => $usedMemory,
        'free' => $freeMemory
    ];
}
echo "<br>";
$nombreProcesador = shell_exec("wmic cpu get Name");
$nombreProcesador = trim(str_replace("Name", "", $nombreProcesador));

echo "Nombre del procesador: " . $nombreProcesador;
echo "<br>";
$diskFreeSpace = disk_free_space('/');
$diskFreeSpaceGB = round($diskFreeSpace / 1024 / 1024 / 1024, 2); // Convertir a gigabytes con dos decimales

echo "Almacenamiento disponible: " . $diskFreeSpaceGB . " GB";
echo "<br>";
$ip = $_SERVER['REMOTE_ADDR'];

// Mostrar la dirección IP
echo "Tu dirección IP es: " . $ip; 
?>
<br>
<label for="data_as" id="as" name="as"></label>
<br>
<label for="data_city" id="city" name="city"></label>
<br>
<label for="data_country" id="country" name="country"></label>
<br>
<label for="data_countryCode" id="countryCode" name="countryCode"></label>
<br>
<label for="data_isp" id="isp" name="isp"></label>
<br>
<label for="data_lat" id="lat" name="lat"></label>
<br>
<label for="data_lon" id="lon" name="lon"></label>
<br>
<label for="data_org" id="org" name="org"></label>
<br>
<label for="data_query" id="query" name="query"></label>
<br>
<label for="data_region" id="region" name="region"></label>
<br>
<label for="data_regionName" id="regionName" name="regionName"></label>
<br>
<label for="data_status" id="status" name="status"></label>
<br>
<label for="data_timezone" id="timezone" name="timezone"></label>
<br>
<label for="data_zip" id="zip" name="zip"></label>

<script>
var ip = "<?php echo $ip; ?>";
console.log(ip);
if(ip=="::1"){
    ip="";
}
$.ajax({
    url: 'http://ip-api.com/json/' + ip,
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      // Se ha obtenido la respuesta exitosamente
      console.log(data);
        $("#as").html(data.as);
        $("#city").html(data.city);
        $("#country").html(data.country);
        $("#countryCode").html(data.countryCode);
        $("#isp").html(data.isp);
        $("#lat").html(data.lat);
        $("#lon").html(data.lon);
        $("#org").html(data.org);
        $("#query").html(data.query);
        $("#region").html(data.region);
        $("#regionName").html(data.regionName);
        $("#status").html(data.status);
        $("#timezone").html(data.timezone);
        $("#zip").html(data.zip);
    },
    error: function(error) {
      // Ha ocurrido un error en la solicitud AJAX
      console.log('Error al obtener información de IP: ' + error);
    }
  });
</script>