<script src="js/jquery.min.js"></script>;

$.ajax({
  url: "http://ip-api.com/json/" + ip,
  type: "GET",
  dataType: "json",
  success: function (data) {
    // Se ha obtenido la respuesta exitosamente
    console.log(data);
    // Aquí puedes acceder a los datos específicos que necesites, por ejemplo:
    console.log("Ubicación: " + data.country + ", " + data.regionName);
    console.log("ISP: " + data.isp);
  },
  error: function (error) {
    // Ha ocurrido un error en la solicitud AJAX
    console.log("Error al obtener información de IP: " + error);
  },
});
