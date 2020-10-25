// VARIABLES
var grid = null,
    rowG = null;

// Función para limpiar controles
var funcLimpiarCtrls = function(evitarAjuste) {
    // Ajuste
    evitarAjuste = evitarAjuste || false;
    // Obtener variables de controles
    var ctrl_reg = document.getElementById("sdr-ctrl-REG"),
        ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
        ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
        ctrl_fechaini = document.getElementById("sdr-ctrl-FECHAINI"),
        ctrl_fechafin = document.getElementById("sdr-ctrl-FECHAFIN"),
        ctrl_horaini = document.getElementById("sdr-ctrl-HORAINI"),
        ctrl_horafin = document.getElementById("sdr-ctrl-HORAFIN"),
        ctrl_haypy = document.getElementById("sdr-ctrl-HAYAPY");
    // Limpiar controles
    ctrl_reg.value = '';
    ctrl_tipo.value = '';
    ctrl_patron.value = '';
    ctrl_fechaini.value = '';
    ctrl_fechafin.value = '';
    ctrl_horaini.value = '';
    ctrl_horafin.value = '';
    ctrl_haypy.checked = false;
    // Validar
    if (!evitarAjuste) {
        // Ajuste
        funcOnChangeValues();
    }
};
// Función para salvar registro
var funcSalvar = function() {
    // Cargar loader
    openLoader(function(closeLoader) {
        // Obtener variables de controles
        var ctrl_reg = document.getElementById("sdr-ctrl-REG"),
            ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
            ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
            ctrl_fechaini = document.getElementById("sdr-ctrl-FECHAINI"),
            ctrl_fechafin = document.getElementById("sdr-ctrl-FECHAFIN"),
            ctrl_horaini = document.getElementById("sdr-ctrl-HORAINI"),
            ctrl_horafin = document.getElementById("sdr-ctrl-HORAFIN"),
            ctrl_haypy = document.getElementById("sdr-ctrl-HAYAPY");
        // Obtener valores
        var reg = (ctrl_reg.value || '').toString().trim(),
            tipo = ((ctrl_tipo.selectedIndex >= 0) ?
                (ctrl_tipo.options[ctrl_tipo.selectedIndex].value || '').trim() :
                ''),
            patron = ((ctrl_patron.selectedIndex >= 0) ?
                (ctrl_patron.options[ctrl_patron.selectedIndex].value || '').trim() :
                ''),
            fechaini = (ctrl_fechaini.value || '').trim(),
            fechafin = (ctrl_fechafin.value || '').trim(),
            hrini = (ctrl_horaini.value || '').trim(),
            hrfin = (ctrl_horafin.value || '').trim(),
            haypy = ctrl_haypy.checked;
        // Validar opciones

        if (!tipo) {
            closeLoader();
            emitirAlerta('No indicaste el tipo de apoyo.');
            return;
        }
        if (!patron) {
            closeLoader();
            emitirAlerta('No indicaste quien patrocina el apoyo.');
            return;
        }
        if (!fechaini) {
            closeLoader();
            if (tipo === '2') {
                emitirAlerta('No indicaste la fecha de inicio del apoyo.');
            } else {
                emitirAlerta('No indicaste la fecha del apoyo.');
            }
            return;
        }
        if (!fechafin && (tipo === '2')) {
            closeLoader();
            emitirAlerta('No indicaste la fecha en que finaliza el apoyo.');
            return;
        }
        if (!hrini && haypy) {
            closeLoader();
            emitirAlerta('No indicaste la hora de inicio del apoyo.');
            return;
        }
        if (!hrfin && (hrini || haypy)) {
            closeLoader();
            emitirAlerta('No indicaste la hora en que finaliza el apoyo.');
            return;
        }

        // Invocación ajax
        (function(funcRetorno) {
            // Variable de petición
            var miPeticionAjax = new XMLHttpRequest(),
                paramsURL = (
                    ('1=' + strToHex(reg)) +
                    '&' +
                    ('2=' + strToHex(tipo)) +
                    '&' +
                    ('3=' + strToHex(patron)) +
                    '&' +
                    ('4=' + strToHex(fechaini)) +
                    '&' +
                    ('5=' + strToHex(fechafin)) +
                    '&' +
                    ('6=' + strToHex(hrini)) +
                    '&' +
                    ('7=' + strToHex(hrfin)) +
                    '&' +
                    ('8=' + strToHex((haypy ? 'S' : 'N')))
                );
            // Definción de ejecusión AJAX
            miPeticionAjax.onload = function() {
                if (this.readyState == 4 && this.status == 200) {
                    funcRetorno(this.responseText);
                }
            };
            // Solicitar recursos por AJAX
            miPeticionAjax.open("POST", ('/Proyecto_PHP/index.php/apoyo_en_transporte/apoyos'), true);
            miPeticionAjax.setRequestHeader('Content-type',
                'application/x-www-form-urlencoded');
            miPeticionAjax.send(paramsURL);
            // Función de retorno
        })(function(rData) {
            if (rData.indexOf('|') >= 0) {
                rData = rData.split('|');
                // Si primer segmento es "S"
                if (rData[0] === 'S') {
                    // Ejecutar busqueda inicial al cargar
                    funcReconstruir(closeLoader);
                } else {
                    closeLoader();
                    emitirAlerta("Ocurrió un error al intentar guardar.");
                }
            } else {
                closeLoader();
                emitirAlerta(rData);
            }
        });
    });
};

// Función para editar registro
var funcEditar = function() {
    // Cargar loader
    openLoader(function(closeLoader) {
        // Variables de Ctrls
        var ctrl_reg = document.getElementById("sdr-ctrl-REG"),
            ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
            ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
            ctrl_fechaini = document.getElementById("sdr-ctrl-FECHAINI"),
            ctrl_fechafin = document.getElementById("sdr-ctrl-FECHAFIN"),
            ctrl_horaini = document.getElementById("sdr-ctrl-HORAINI"),
            ctrl_horafin = document.getElementById("sdr-ctrl-HORAFIN"),
            ctrl_haypy = document.getElementById("sdr-ctrl-HAYAPY"),
            // Bandera
            rSelected = null;
        // Validar Grid y fila seleccionada
        if (grid && (grid.length > 0) && rowG) {
            // Obtener fila objetivo seleccionada
            rSelected = grid.data("JSGrid").rowByItem(rowG)
                // Validar que la fila objetivo se encuentre seleccionada
            if ((rSelected.length > 0) && rSelected.hasClass('sdr-highlight')) {
                /* Si la fila objetivo se encuentra seleccionada */
                // Limpiar controles
                funcLimpiarCtrls(true /*Evitar ajuste*/ );
                // Asinar valores de configuración
                ctrl_tipo.value = (rowG['reg_tipo'] || '');
                if (rowG['hay_apy']) {
                    ctrl_haypy.checked = true;
                } else {
                    ctrl_haypy.checked = false;
                }
                // Realizar ajuste
                funcOnChangeValues();
                // Asignar valores
                ctrl_reg.value = (rowG['reg'] || '');
                ctrl_patron.value = (rowG['reg_patron'] || '');

                if (rowG['dia_ini']) {
                    ctrl_fechaini.valueAsDate = strToDate(rowG['dia_ini'] || '');
                } else {
                    ctrl_fechaini.valueAsDate = null;
                }

                if (rowG['dia_fin']) {
                    ctrl_fechafin.valueAsDate = strToDate(rowG['dia_fin'] || '');
                } else {
                    ctrl_fechafin.valueAsDate = null;
                }

                if (rowG['hr_ini']) {
                    ctrl_horaini.value = convertTo24Hour(rowG['hr_ini'] || '').trim() + ":00";
                } else {
                    ctrl_horaini.value = '';
                }

                if (rowG['hr_fin']) {
                    ctrl_horafin.value = convertTo24Hour(rowG['hr_fin'] || '').trim() + ":00";
                } else {
                    ctrl_horafin.value = '';
                }
            } else {
                rSelected = null;
            }
        }
        // Cerrar loader
        closeLoader();
        // Validar existencia de fila seleccionada
        if (!rSelected) {
            emitirAlerta("Por favor, selecciona la fila a editar");
        } else {
            // Cambiar modo
            funcCambModo("E");
        }
    });
};

var funcReconstruir = function(funDespDeConst) {
    // Limpiar controles
    funcLimpiarCtrls();
    // Invocación ajax
    (function(funcRetorno) {
        // Variable de petición
        var miPeticionAjax = new XMLHttpRequest();
        // Definción de ejecusión AJAX
        miPeticionAjax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                funcRetorno(this.responseText);
            }
        };
        // Solicitar recursos por AJAX
        miPeticionAjax.open("GET", '/Proyecto_PHP/index.php/apoyo_en_transporte/lista', true);
        miPeticionAjax.send();
        // Función de retorno
    })(function(datosJson) {
        // Parsear datos
        try {
            datosJson = validarDatosJson(JSON.parse(datosJson), false);
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
            // Si existe el Grid
            if (grid && (grid.length > 0)) {
                grid.jsGrid("destroy");
            }
            // Construir grid
            grid = $("#sdr-js-grid").jsGrid({
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

                rowClick: function(args) {
                    // Variables
                    var rowJson = args.item,
                        rowHtml = this.rowByItem(rowJson),
                        // Recuperar otras filas seleccionadas
                        othersRSelected = grid.find('table tr.sdr-highlight');
                    // Si hay otras filas seleccionadas
                    if (othersRSelected.length) {
                        // Remover clase "highlight"
                        othersRSelected.toggleClass('sdr-highlight', false);
                    }
                    // Resguardar Item
                    rowG = rowJson;
                    rowHtml.toggleClass('sdr-highlight', true);
                },
            });
        });
        // Función para despues de construir
        if (funDespDeConst) {
            funDespDeConst();
        }
        // Cambiar modo
        funcCambModo("");
    });
};

var funcOnChangeValues = function(sender) {
    // Control principales
    // Selector
    var ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
        tipo = ((ctrl_tipo.selectedIndex >= 0) ?
            (ctrl_tipo.options[ctrl_tipo.selectedIndex].value || '').trim() : ''),
        // Checkbox
        ctrl_haypy = document.getElementById("sdr-ctrl-HAYAPY"),
        haypy = ctrl_haypy.checked;
    // Otros controles
    var ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
        ctrl_fechaini = document.getElementById("sdr-ctrl-FECHAINI"),
        ctrl_fechafin = document.getElementById("sdr-ctrl-FECHAFIN"),
        ctrl_horaini = document.getElementById("sdr-ctrl-HORAINI"),
        ctrl_horafin = document.getElementById("sdr-ctrl-HORAFIN");
    // Validar tipo
    if (tipo === '1') {
        // Patron y fechas
        ctrl_patron.removeAttribute("disabled");
        ctrl_fechaini.removeAttribute("disabled");
        ctrl_fechafin.setAttribute("disabled", "true");
        // ctrl_fechafin.value = "";
    } else if (tipo === '2') {
        // Patron y fechas
        ctrl_patron.removeAttribute("disabled");
        ctrl_fechaini.removeAttribute("disabled");
        ctrl_fechafin.removeAttribute("disabled");
    } else {
        // Patron y fechas
        ctrl_patron.setAttribute("disabled", "true");
        ctrl_fechaini.setAttribute("disabled", "true");
        ctrl_fechafin.setAttribute("disabled", "true");
        // Patron y fechas
        // ctrl_patron.value = "";
        // ctrl_fechaini.value = "";
        // ctrl_fechafin.value = "";
    }
    // Si hay un tipo de registro seleccionado
    if ((tipo === '1') || (tipo === '2')) {
        // Control checkbox
        ctrl_haypy.removeAttribute("disabled");
        // Si hay apoyo
        if (haypy) {
            ctrl_horaini.removeAttribute("disabled");
            ctrl_horafin.removeAttribute("disabled");
        } else {
            ctrl_horaini.setAttribute("disabled", "true");
            ctrl_horafin.setAttribute("disabled", "true");
            // ctrl_horaini.value = "";
            // ctrl_horafin.value = "";
        }
    } else {
        // Control checkbox
        ctrl_haypy.setAttribute("disabled", "true");
        // ctrl_haypy.checked = false;
        // Controles hora
        ctrl_horaini.setAttribute("disabled", "true");
        ctrl_horafin.setAttribute("disabled", "true");
        // ctrl_horaini.value = "";
        // ctrl_horafin.value = "";
    }
};

var funcCambModo = function(modo) {
    // Variables
    var btn_nuevo = document.getElementById("sdr-ctrl-BTNNUEVO"),
        btn_editar = document.getElementById("sdr-ctrl-BTNEDITAR");
    // Seleccion de modo
    switch (modo) {
        case "E":
            $(btn_nuevo).removeClass('sdr-box-shadow');
            $(btn_editar).addClass('sdr-box-shadow');
            break;
        default:
            $(btn_nuevo).addClass('sdr-box-shadow');
            $(btn_editar).removeClass('sdr-box-shadow');
            break;
    }
};

// MAIN
window.addEventListener('load', function() {
    // Cargar loader
    openLoader(function(closeLoader) {
        // Form CRUD
        var frm_ctrls = document.getElementById("sdr-ctrl-FRM"),
            ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
            ctrl_haypy = document.getElementById("sdr-ctrl-HAYAPY");
        // Ctrls CRUD
        var btn_nuevo = document.getElementById("sdr-ctrl-BTNNUEVO"),
            btn_editar = document.getElementById("sdr-ctrl-BTNEDITAR"),
            btn_salvar = document.getElementById("sdr-ctrl-BTNSALVAR");
        // Control selector tipo
        ctrl_tipo.addEventListener("change", funcOnChangeValues);
        ctrl_haypy.addEventListener("change", funcOnChangeValues);
        // Relacionar botones con eventos
        frm_ctrls.addEventListener("submit", function(sender) {
            sender.preventDefault();
        });
        btn_salvar.addEventListener("click", function(sender) {
            funcSalvar();
            sender.preventDefault();
        });
        btn_editar.addEventListener("click", function(sender) {
            funcEditar();
            sender.preventDefault();
        });
        btn_nuevo.addEventListener("click", function(sender) {
            sender.preventDefault();
            // Cambiar modo
            funcCambModo("");
            funcLimpiarCtrls();
        });
        // Ejecutar busqueda inicial al cargar
        funcReconstruir(closeLoader);
    });
});