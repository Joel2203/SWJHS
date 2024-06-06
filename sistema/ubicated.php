<?php
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $latitud = $_GET['lat'];
    $longitud = $_GET['lon'];
    
    echo "La ubicación de la persona es: Latitud $latitud, Longitud $longitud";
} else {
    echo "No se proporcionaron datos de ubicación.";
}
?>
if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log("Latitud: " + latitude);
    console.log("Longitud: " + longitude);

    // Puedes hacer lo que necesites con las coordenadas aquí
  }, function(error) {
    console.error("Error al obtener la ubicación: " + error.message);
  });
} else {
  console.log("La geolocalización no está disponible en este navegador.");
}
<script>
 if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    
    // Crear el enlace a Google Maps con las coordenadas
    var googleMapsLink = `https://www.google.com/maps/place/${latitude},${longitude}`;
    
    // Crear un elemento de enlace y configurar sus atributos
    var linkElement = document.createElement("a");
    linkElement.href = googleMapsLink;
    linkElement.textContent = "Ver ubicación en Google Maps";
    
    // Agregar el enlace al documento
    document.body.appendChild(linkElement);
  }, function(error) {
    console.error("Error al obtener la ubicación: " + error.message);
  });
} else {
  console.log("La geolocalización no está disponible en este navegador.");
}

</script>