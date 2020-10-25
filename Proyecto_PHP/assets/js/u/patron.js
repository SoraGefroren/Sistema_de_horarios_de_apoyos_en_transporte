// VARIABLES
var grid = null,
    rowG = null;
// Función para limpiar controles
var funcLimpiarCtrls = function() {
    // Obtener variables de controles
    var ctrl_reg = document.getElementById("sdr-ctrl-REG"),
        ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
        ctrl_direccion = document.getElementById("sdr-ctrl-DIRECCION"),
        ctrl_tel = document.getElementById("sdr-ctrl-TEL"),
        ctrl_tel_rels = document.getElementById("sdr-ctrl-TEL-RELS");

    // Limpiar controles
    ctrl_reg.value = '';
    ctrl_patron.value = '';
    ctrl_direccion.value = '';
    ctrl_tel.value = '';

    // Limpiar patrones relacionados
    while (ctrl_tel_rels.lastChild) {
        ctrl_tel_rels.removeChild(ctrl_tel_rels.lastChild);
    }
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
        miPeticionAjax.open("GET", '/Proyecto_PHP/index.php/apoyo_en_transporte/ptrns', true);
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
        if ($("#sdr-grid-patrones").width() < 750) {
            viewExtCols = false;
        }

        // --------------------------------------
        $(function() {
            // Si existe el Grid
            if (grid && (grid.length > 0)) {
                grid.jsGrid("destroy");
            }
            // Construir grid
            grid = $("#sdr-grid-patrones").jsGrid({
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
                        title: "Patrocinador",
                        width: 75,
                        name: "patron",
                        type: "text"
                    },
                    {
                        title: "Dirección",
                        width: 150,
                        name: "direccion",
                        type: "text"
                    },
                    {
                        title: "Teléfonos",
                        width: 100,
                        name: "tels",
                        type: "text"
                    },
                ],

                noDataContent: "Sin usuarios disponibles",

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

// Función para editar registro
var funcEditar = function() {
    // Cargar loader
    openLoader(function(closeLoader) {
        // Variables de Ctrls
        var ctrl_reg = document.getElementById("sdr-ctrl-REG"),
            ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
            ctrl_direccion = document.getElementById("sdr-ctrl-DIRECCION"),
            ctrl_tel = document.getElementById("sdr-ctrl-TEL"),
            ctrl_tel_rels = document.getElementById("sdr-ctrl-TEL-RELS"),
            // Vars de apoyo
            telsPuestos = '',
            tels_delPatron = null,
            tels_option = null,
            rSelected = null;
        // Validar Grid y fila seleccionada
        if (grid && (grid.length > 0) && rowG) {
            // Obtener fila objetivo seleccionada
            rSelected = grid.data("JSGrid").rowByItem(rowG)
                // Validar que la fila objetivo se encuentre seleccionada
            if ((rSelected.length > 0) && rSelected.hasClass('sdr-highlight')) {
                /* Si la fila objetivo se encuentra seleccionada */
                // Limpiar controles
                funcLimpiarCtrls();
                // Asignar valores
                ctrl_reg.value = (rowG['reg'] || '').trim();
                ctrl_patron.value = (rowG['patron'] || '').trim();
                ctrl_direccion.value = (rowG['direccion'] || '').trim();

                tels_delPatron = (rowG['tels'] || '').trim().split(', ');

                for (var i = 0, tel = null; tel = tels_delPatron[i]; i++) {
                    if (tel.trim()) {
                        tel = tel.trim();

                        if (!(telsPuestos.indexOf(tel) >= 0)) {
                            telsPuestos += ('|' + tel);
                            tels_option = document.createElement("option");
                            tels_option.setAttribute('value', tel);
                            tels_option.setAttribute('data-val', tel);
                            tels_option.appendChild(document.createTextNode(tel));
                            ctrl_tel_rels.appendChild(tels_option);
                        }
                    }
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

// Función para salvar registro
var funcSalvar = function() {
    // Cargar loader
    openLoader(function(closeLoader) {
        // Obtener variables de controles
        var ctrl_reg = document.getElementById("sdr-ctrl-REG"),
            ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
            ctrl_direccion = document.getElementById("sdr-ctrl-DIRECCION"),
            ctrl_tel = document.getElementById("sdr-ctrl-TEL"),
            ctrl_tel_rels = document.getElementById("sdr-ctrl-TEL-RELS")
            .getElementsByTagName('option'),
            telRel = null;
        // Obtener valores
        var reg = (ctrl_reg.value || '').toString().trim(),
            patron = (ctrl_patron.value || '').toString().trim(),
            direccion = (ctrl_direccion.value || '').toString().trim(),
            telefonos = '';
        // Validar existencia de telefonos relacionados
        if (ctrl_tel_rels && (ctrl_tel_rels.length > 0)) {
            // Recorrer telefonos relacionados
            [].forEach.call(ctrl_tel_rels, function(mi_ctrl) {
                telRel = (mi_ctrl.getAttribute('value') || '').trim();
                if (telRel) {
                    telefonos += (telefonos ? ('|' + telRel) : telRel);
                }
            });
        }
        // Validar opciones
        if (!patron) {
            closeLoader();
            emitirAlerta('No mencionaste el nombre del patrocinador.');
            return;
        }
        if (!direccion) {
            closeLoader();
            emitirAlerta('No mencionaste la dirección del patrocinador.');
            return;
        }
        if (!telefonos) {
            closeLoader();
            emitirAlerta('No mencionaste al menos un telefono para el patrocinador.');
            return;
        }

        // Invocación ajax
        (function(funcRetorno) {
            // Variable de petición
            var miPeticionAjax = new XMLHttpRequest(),
                paramsURL = (
                    ('1=' + strToHex(reg)) +
                    '&' +
                    ('2=' + strToHex(patron)) +
                    '&' +
                    ('3=' + strToHex(direccion)) +
                    '&' +
                    ('4=' + strToHex(telefonos))
                );
            // Definción de ejecusión AJAX
            miPeticionAjax.onload = function() {
                if (this.readyState == 4 && this.status == 200) {
                    funcRetorno(this.responseText);
                }
            };
            // Solicitar recursos por AJAX
            miPeticionAjax.open("POST", ('/Proyecto_PHP/index.php/apoyo_en_transporte/patrocinadores'), true);
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

// Función para remover ITEM
var funcRemoverItem = function() {
    // Controles
    var ctrl_tel_rels = document.getElementById("sdr-ctrl-TEL-RELS");
    // Valores
    var vTel = ((ctrl_tel_rels.selectedIndex >= 0) ?
            (ctrl_tel_rels.options[ctrl_tel_rels.selectedIndex].value || '').trim() :
            ''),
        tel_option = null,
        telRel = null;

    // Validar existencia de telefonos relacionados
    if (ctrl_tel_rels && (ctrl_tel_rels.length > 0)) {
        // Recorrer telefonos relacionados
        [].forEach.call(ctrl_tel_rels, function(mi_ctrl) {
            telRel = (mi_ctrl.getAttribute('value') || '').trim();
            if (telRel === vTel) {
                tel_option = mi_ctrl;
            }
        });
    }

    // Si todo bien
    if (tel_option) {
        ctrl_tel_rels.removeChild(tel_option)
    }
};

var funcAgregarItem = function() {
    // Controles
    var ctrl_tel = document.getElementById("sdr-ctrl-TEL"),
        ctrl_tel_rels = document.getElementById("sdr-ctrl-TEL-RELS");
    // Valores
    var vtel = (ctrl_tel.value || '').toString().trim(),
        tel_option = null,
        telRel = null,
        isOk = true;

    // Validar existencia de telefonos relacionados
    if (ctrl_tel_rels && (ctrl_tel_rels.length > 0)) {
        // Recorrer telefonos relacionados
        [].forEach.call(ctrl_tel_rels, function(mi_ctrl) {
            telRel = (mi_ctrl.getAttribute('value') || '').trim();
            if (telRel === vtel) {
                isOk = false;
            }
        });
    }

    // Si todo bien
    if (vtel && isOk) {
        tel_option = document.createElement("option");
        tel_option.setAttribute('value', vtel);
        tel_option.appendChild(document.createTextNode(vtel));
        ctrl_tel_rels.appendChild(tel_option);
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
        var frm_ctrls = document.getElementById("sdr-ctrl-FRM");
        // Ctrls CRUD
        var btn_agregarTel = document.getElementById("sdr-ctrl-BTNAGREGAR-TEL"),
            btn_removerTel = document.getElementById("sdr-ctrl-BTNREMOVER-TEL"),
            btn_nuevo = document.getElementById("sdr-ctrl-BTNNUEVO"),
            btn_editar = document.getElementById("sdr-ctrl-BTNEDITAR"),
            btn_salvar = document.getElementById("sdr-ctrl-BTNSALVAR");
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
            funcLimpiarCtrls();
            // Cambiar modo
            funcCambModo("");
            sender.preventDefault();
        });
        btn_removerTel.addEventListener("click", function(sender) {
            funcRemoverItem();
            sender.preventDefault();
        });
        btn_agregarTel.addEventListener("click", function(sender) {
            funcAgregarItem();
            sender.preventDefault();
        });
        // Ejecutar busqueda inicial al cargar
        funcReconstruir(closeLoader);
    });
});