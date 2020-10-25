// Al cargar la página
window.addEventListener("load", function() {
    // --------------------------------------
    // --------------------------------------
    // Invocación ajax
    (function(funcRetorno) {
        // Cargar loader
        openLoader(function(closeLoader) {
            // Variable de petición
            var miPeticionAjax = new XMLHttpRequest();
            // Definción de ejecusión AJAX
            miPeticionAjax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    funcRetorno(this.responseText, closeLoader);
                }
            };
            // Solicitar recursos por AJAX
            miPeticionAjax.open("GET", '/Proyecto_PHP/index.php/apoyo_en_transporte/informacion', true);
            miPeticionAjax.send();
        });
        // Función de retorno
    })(function(datosJson, closeLoader) {
        // Prevenir
        try {
            datosJson = validarDatosJson(JSON.parse(datosJson), true);
        } catch (e) {
            datosJson = [];
        }
        // --------------------------------------
        var viewExtCols = true;
        if ($("#sdr-js-grid").width() < 750) {
            viewExtCols = false;
        }
        // --------------------------------------
        $(function() {
            $("#sdr-js-grid").jsGrid({
                height: "445px",
                width: "100%",

                filtering: false,
                selecting: true,
                sorting: true,
                paging: true,
                pageSize: 5,
                pagerFormat: "Página actual: {pageIndex} &nbsp;&nbsp; {first} {prev} {pages} {next} {last} &nbsp;&nbsp; Páginas totales: {pageCount}",
                pagePrevText: "<",
                pageNextText: ">",
                pageFirstText: "<<",
                pageLastText: ">>",

                editing: false,
                inserting: false,

                data: datosJson,

                fields: [{
                        title: "Tipo de apoyo",
                        width: 60,
                        name: "tipo",
                        type: "text"
                    },
                    {
                        title: "¿Hay apoyo?",
                        width: 50,
                        name: "hay_apy",
                        type: "checkbox"
                    },
                    {
                        title: "Fecha inicial",
                        width: 70,
                        name: "dia_ini",
                        type: "text",
                        align: "center"
                    },
                    {
                        title: "Fecha final",
                        width: 70,
                        name: "dia_fin",
                        type: "text",
                        align: "center"
                    },
                    {
                        title: "Hora inicial",
                        width: 60,
                        name: "hr_ini",
                        type: "text",
                        align: "right"
                    },
                    {
                        title: "Hora final",
                        width: 60,
                        name: "hr_fin",
                        type: "text",
                        align: "right"
                    },
                    {
                        title: "Patrocinador",
                        width: 70,
                        name: "patron",
                        type: "text",
                        align: "center",
                        visible: viewExtCols
                    },
                    {
                        title: "Dirección",
                        width: 150,
                        name: "direccion",
                        type: "text",
                        visible: viewExtCols
                    },
                    {
                        title: "Teléfonos",
                        width: 75,
                        name: "tels",
                        type: "text",
                        visible: viewExtCols
                    }
                ],

                noDataContent: "Sin apoyos disponibles",
            });
        });
        // Cerrar loader
        closeLoader();
    });
    // --------------------------------------
    // --------------------------------------
});