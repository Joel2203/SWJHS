$(document).ready(function() {
    $(".column").sortable({
        connectWith: ".column",
        receive: function(event, ui) {
            var item = ui.item;
            var columnId = item.parent().attr("id");
            var itemId = item.attr("id").replace("item-", "");

            // Aquí puedes realizar una llamada AJAX para actualizar el estado del elemento en la base de datos
            // Puedes enviar columnId e itemId al servidor para procesar la actualización
            // Por ejemplo:
             $.ajax({
                type: "POST",
                url: "actualizar_kanban.php",
                data: { id: itemId, estado: columnId },
                 success: function(response) {
                    console.log(response);
               }
            });

            console.log("Elemento movido a la columna:", columnId);
            console.log("ID del elemento:", itemId);
        }
    }).disableSelection();
});
