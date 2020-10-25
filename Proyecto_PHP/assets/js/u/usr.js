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
        ctrl_patron_rels = document.getElementById("sdr-ctrl-PATRON-RELS"),
        ctrl_email = document.getElementById("sdr-ctrl-EMAIL"),
        ctrl_nombre = document.getElementById("sdr-ctrl-NOMBRE"),
        ctrl_ap = document.getElementById("sdr-ctrl-AP"),
        ctrl_am = document.getElementById("sdr-ctrl-AM"),
        ctrl_pass = document.getElementById("sdr-ctrl-PASS"),
        ctrl_cpass = document.getElementById("sdr-ctrl-CPASS"),
        ctrl_estado = document.getElementById("sdr-ctrl-ESTADO");
    // Limpiar controles
    ctrl_reg.value = '';
    ctrl_tipo.value = '';
    ctrl_patron.value = '';
    ctrl_patron_rels.value = '';

    // Limpiar patrones relacionados
    while (ctrl_patron_rels.lastChild) {
        ctrl_patron_rels.removeChild(ctrl_patron_rels.lastChild);
    }

    ctrl_email.value = '';
    ctrl_nombre.value = '';
    ctrl_ap.value = '';
    ctrl_am.value = '';
    ctrl_pass.value = '';
    ctrl_cpass.value = '';
    ctrl_estado.checked = false;
    // Validar
    if (!evitarAjuste) {
        // Ajuste
        funcOnChangeValues();
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
        miPeticionAjax.open("GET", '/Proyecto_PHP/index.php/apoyo_en_transporte/usrs', true);
        miPeticionAjax.send();
        // Función de retorno
    })(function(datosJson) {
        // Parsear datos
        try {
            datosJson = validarDatosJson(JSON.parse(datosJson), false);
        } catch (e) {
            datosJson = [];
        }

        // Variable
        var dataTemp = [],
            jsonTemp = null;

        // Variables - Datos
        var vReg = '',
            vNombre = '',
            vAp = '',
            vAm = '',
            vCorreo = '',
            vEstado = '',
            vTipo = '',
            vTipo2 = '',
            vRegPatron = '',
            vPatron = '',
            vPatron2 = '',
            vDireccion = '',
            vTels = '';

        // Recorrer arreglo
        datosJson.forEach(function(tempObject) {
            // Recuperar datos
            vReg = (tempObject['reg'] || tempObject['REG'] || '').trim();
            vNombre = (tempObject['nombre'] || tempObject['NOMBRE'] || '').trim();
            vAp = (tempObject['ap_paterno'] || tempObject['AP_PATERNO'] || '').trim();
            vAm = (tempObject['ap_materno'] || tempObject['AP_MATERNO'] || '').trim();
            vCorreo = (tempObject['correo'] || tempObject['CORREO'] || '').trim();
            vEstado = (tempObject['estado'] || tempObject['ESTADO'] || '').trim();
            vRegTipo = (tempObject['reg_tipo'] || tempObject['REG_TIPO'] || '').trim();
            vTipo = (tempObject['tipo'] || tempObject['TIPO'] || '').trim();
            vTipo2 = '';
            vRegPatron = (tempObject['reg_patron'] || tempObject['REG_PATRON'] || '').trim();
            vPatron = (tempObject['patron'] || tempObject['PATRON'] || '').trim();
            vPatron2 = '';
            vDireccion = (tempObject['direccion'] || tempObject['DIRECCION'] || '').trim();
            vTels = (tempObject['tels'] || tempObject['TELS'] || '').trim();
            // Componer Patrones
            vPatron2 = vPatron;
            while (vPatron2.indexOf('[|<>|] ') >= 0) {
                vPatron2 = vPatron2.replace('[|<>|] ', ', ');
            }
            // Componer tipo
            switch (vRegTipo) {
                case '1':
                case 1:
                    vTipo2 = 'Administrador';
                    break;
                case '2':
                case 2:
                    vTipo2 = 'Directivo';
                    break;
                case '3':
                case 3:
                    vTipo2 = 'Representante';
                    break;
                default:
                    break;
            }
            // Formar JSON
            jsonTemp = {
                reg: vReg,
                nombre: vNombre,
                ap_paterno: vAp,
                ap_materno: vAm,
                correo: vCorreo,
                estado: (((vEstado === '1') || (vEstado === 'true') || (vEstado === 'TRUE')) ? true : false),
                reg_tipo: vRegTipo,
                tipo: vTipo,
                tipo2: vTipo2,
                reg_patron: vRegPatron,
                patron: vPatron,
                patron2: vPatron2,
                direccion: vDireccion,
                tels: vTels,
            };
            // Resguardar JSON
            if (jsonTemp) {
                dataTemp.push(jsonTemp);
            }
        });

        // --------------------------------------
        var viewExtCols = true;
        if ($("#sdr-grid-usrs").width() < 750) {
            viewExtCols = false;
        }

        // --------------------------------------
        $(function() {
            // Si existe el Grid
            if (grid && (grid.length > 0)) {
                grid.jsGrid("destroy");
            }
            // Construir grid
            grid = $("#sdr-grid-usrs").jsGrid({
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

                data: dataTemp,

                fields: [{
                        title: "Nombre",
                        width: 65,
                        name: "nombre",
                        type: "text"
                    },
                    {
                        title: "Apellido paterno",
                        width: 75,
                        name: "ap_paterno",
                        type: "text",
                        visible: viewExtCols
                    },
                    {
                        title: "Apellido materno",
                        width: 75,
                        name: "ap_materno",
                        type: "text",
                        visible: viewExtCols
                    },

                    {
                        title: "Correo electrónico",
                        width: 115,
                        name: "correo",
                        type: "text"
                    },

                    {
                        title: "Tipo de usuario",
                        width: 65,
                        name: "tipo2",
                        type: "text"
                    },
                    {
                        title: "Estado?",
                        width: 50,
                        name: "estado",
                        type: "checkbox"
                    },

                    {
                        title: "Patrocinadores",
                        width: 150,
                        name: "patron2",
                        type: "text",
                        align: "center",
                        visible: viewExtCols
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
            ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
            ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
            ctrl_patron_rels = document.getElementById("sdr-ctrl-PATRON-RELS"),
            ctrl_email = document.getElementById("sdr-ctrl-EMAIL"),
            ctrl_nombre = document.getElementById("sdr-ctrl-NOMBRE"),
            ctrl_ap = document.getElementById("sdr-ctrl-AP"),
            ctrl_am = document.getElementById("sdr-ctrl-AM"),
            ctrl_estado = document.getElementById("sdr-ctrl-ESTADO"),
            // Vars de apoyo
            patron_option = null,
            patronesRels_P = null,
            patronesRels_R = null,
            rSelected = null;
        // Validar Grid y fila seleccionada
        if (grid && (grid.length > 0) && rowG) {
            // Obtener fila objetivo seleccionada
            rSelected = grid.data("JSGrid").rowByItem(rowG)
                // Validar que la fila objetivo se encuentre seleccionada
            if ((rSelected.length > 0) && rSelected.hasClass('sdr-highlight')) {
                /* Si la fila objetivo se encuentra seleccionada */
                // Limpiar controles
                funcLimpiarCtrls(true);
                // Asignar valores
                ctrl_tipo.value = (rowG['reg_tipo'] || '').trim();
                if (rowG['estado']) {
                    ctrl_estado.checked = true;
                } else {
                    ctrl_estado.checked = false;
                }
                // Realizar ajuste
                funcOnChangeValues();

                // Asignar valores
                ctrl_reg.value = (rowG['reg'] || '').trim();

                patronesRels_R = (rowG['reg_patron'] || '').trim().split('[|<>|] ');
                patronesRels_P = (rowG['patron'] || '').trim().split('[|<>|] ');

                for (var i = 0, patron = null, reg_p = null, numPR = patronesRels_R.length; patron = patronesRels_P[i]; i++) {
                    if ((i < numPR) && patronesRels_R[i].trim()) {
                        patron_option = document.createElement("option");
                        patron_option.setAttribute('value', patronesRels_R[i].trim());
                        patron_option.setAttribute('data-nom', patron);
                        patron_option.appendChild(document.createTextNode(patron));
                        ctrl_patron_rels.appendChild(patron_option);
                    } else {
                        break;
                    }
                }

                ctrl_email.value = (rowG['correo'] || '').trim();
                ctrl_nombre.value = (rowG['nombre'] || '').trim();
                ctrl_ap.value = (rowG['ap_paterno'] || '').trim();
                ctrl_am.value = (rowG['ap_materno'] || '').trim();


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
            ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
            ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
            ctrl_patron_rels = document.getElementById("sdr-ctrl-PATRON-RELS")
            .getElementsByTagName('option'),
            patronRel = null,
            ctrl_email = document.getElementById("sdr-ctrl-EMAIL"),
            ctrl_nombre = document.getElementById("sdr-ctrl-NOMBRE"),
            ctrl_ap = document.getElementById("sdr-ctrl-AP"),
            ctrl_am = document.getElementById("sdr-ctrl-AM"),
            ctrl_pass = document.getElementById("sdr-ctrl-PASS"),
            ctrl_cpass = document.getElementById("sdr-ctrl-CPASS"),
            ctrl_estado = document.getElementById("sdr-ctrl-ESTADO");
        // Obtener valores
        var reg = (ctrl_reg.value || '').toString().trim(),
            tipo = ((ctrl_tipo.selectedIndex >= 0) ?
                (ctrl_tipo.options[ctrl_tipo.selectedIndex].value || '').trim() :
                ''),
            patrones = '',
            email = (ctrl_email.value || '').trim(),
            nombre = (ctrl_nombre.value || '').trim(),
            ap = (ctrl_ap.value || '').trim(),
            am = (ctrl_am.value || '').trim(),
            pass = (ctrl_pass.value || '').trim(),
            cpass = (ctrl_cpass.value || '').trim(),
            estado = ctrl_estado.checked;
        // Validar existencia de patrones relacionados
        if (ctrl_patron_rels && (ctrl_patron_rels.length > 0)) {
            // Recorrer patrones relacionados
            [].forEach.call(ctrl_patron_rels, function(mi_ctrl) {
                patronRel = (mi_ctrl.getAttribute('value') || '').trim();
                if (patronRel) {
                    patrones += (patrones ? ('|' + patronRel) : patronRel);
                }
            });
        }
        // Validar opciones
        if (!tipo) {
            closeLoader();
            emitirAlerta('No indicaste el tipo de usuario.');
            return;
        }
        if (!patrones && (tipo === '3')) {
            closeLoader();
            emitirAlerta('No indicaste patrocinadores relacionados a este usuario.');
            return;
        }
        if (!email) {
            closeLoader();
            emitirAlerta('No indicaste el correo electrónico del usuario.');
            return;
        }
        if (!nombre) {
            closeLoader();
            emitirAlerta('No indicaste el nombre del usuario.');
            return;
        }
        if (!ap) {
            closeLoader();
            emitirAlerta('No indicaste el apellido paterno del usuario.');
            return;
        }
        if ((pass || cpass) && (pass !== cpass)) {
            closeLoader();
            emitirAlerta('Las contraseñas no coinciden.');
            return;
        }
        if (pass && (pass.length < 8)) {
            closeLoader();
            emitirAlerta('La contraseña que indicaste no puede ser menor a 8 caracteres.');
            return;
        }

        // Probar seguridad de contraseña
        if (pass) {
            var bandRex = true,
                rex = new RegExp("[a-z]+");
            if (!rex.test(pass)) {
                bandRex = false;
            }
            rex = new RegExp("[A-Z]+");
            if (!rex.test(pass)) {
                bandRex = false;
            }
            rex = new RegExp("[0-9]+");
            if (!rex.test(pass)) {
                bandRex = false;
            }
            rex = new RegExp("^[a-zA-Z0-9]+$");
            if (!rex.test(pass)) {
                bandRex = false;
            }
            if (!bandRex) {
                closeLoader();
                emitirAlerta('La contraseña debe incluir al menos una letra mayúscula, una letra minúscula, un número, y tiene que iniciar y terminar con un valor alfanumérico.');
                return;
            }
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
                    ('3=' + strToHex(patrones)) +
                    '&' +
                    ('4=' + strToHex(email)) +
                    '&' +
                    ('5=' + strToHex(nombre)) +
                    '&' +
                    ('6=' + strToHex(ap)) +
                    '&' +
                    ('7=' + strToHex(am)) +
                    '&' +
                    ('8=' + strToHex(pass)) +
                    '&' +
                    ('9=' + strToHex(cpass)) +
                    '&' +
                    ('10=' + strToHex((estado ? 'S' : 'N')))
                );
            // Definción de ejecusión AJAX
            miPeticionAjax.onload = function() {
                if (this.readyState == 4 && this.status == 200) {
                    funcRetorno(this.responseText);
                }
            };
            // Solicitar recursos por AJAX
            miPeticionAjax.open("POST", ('/Proyecto_PHP/index.php/apoyo_en_transporte/usuarios'), true);
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
    var ctrl_patron_rels = document.getElementById("sdr-ctrl-PATRON-RELS");
    // Valores
    var vPatron = ((ctrl_patron_rels.selectedIndex >= 0) ?
            (ctrl_patron_rels.options[ctrl_patron_rels.selectedIndex].value || '').trim() :
            ''),
        patron_option = null,
        patronRel = null;

    // Validar existencia de patrones relacionados
    if (ctrl_patron_rels && (ctrl_patron_rels.length > 0)) {
        // Recorrer patrones relacionados
        [].forEach.call(ctrl_patron_rels, function(mi_ctrl) {
            patronRel = (mi_ctrl.getAttribute('value') || '').trim();
            if (patronRel === vPatron) {
                patron_option = mi_ctrl;
            }
        });
    }

    // Si todo bien
    if (patron_option) {
        ctrl_patron_rels.removeChild(patron_option)
    }
};

var funcAgregarItem = function() {
    // Controles
    var ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
        ctrl_patron_rels = document.getElementById("sdr-ctrl-PATRON-RELS");
    // Valores
    var vPatron = ((ctrl_patron.selectedIndex >= 0) ?
            (ctrl_patron.options[ctrl_patron.selectedIndex].value || '').trim() :
            ''),
        vPatronNom = ((ctrl_patron.selectedIndex >= 0) ?
            (ctrl_patron.options[ctrl_patron.selectedIndex].getAttribute('data-nom') || '').trim() :
            ''),
        patron_option = null,
        patronRel = null,
        isOk = true;

    // Validar existencia de patrones relacionados
    if (ctrl_patron_rels && (ctrl_patron_rels.length > 0)) {
        // Recorrer patrones relacionados
        [].forEach.call(ctrl_patron_rels, function(mi_ctrl) {
            patronRel = (mi_ctrl.getAttribute('value') || '').trim();
            if (patronRel === vPatron) {
                isOk = false;
            }
        });
    }

    // Si todo bien
    if (vPatron && vPatronNom && isOk) {
        patron_option = document.createElement("option");
        patron_option.setAttribute('value', vPatron);
        patron_option.setAttribute('data-nom', vPatronNom);
        patron_option.appendChild(document.createTextNode(vPatronNom));
        ctrl_patron_rels.appendChild(patron_option);
    }
};

var funcOnChangeValues = function(sender) {
    // Obtener variables de controles
    var ctrl_tipo = document.getElementById("sdr-ctrl-TIPO"),
        ctrl_patron = document.getElementById("sdr-ctrl-PATRON"),
        ctrl_patron_rels = document.getElementById("sdr-ctrl-PATRON-RELS"),
        ctrl_email = document.getElementById("sdr-ctrl-EMAIL"),
        ctrl_nombre = document.getElementById("sdr-ctrl-NOMBRE"),
        ctrl_ap = document.getElementById("sdr-ctrl-AP"),
        ctrl_am = document.getElementById("sdr-ctrl-AM"),
        ctrl_pass = document.getElementById("sdr-ctrl-PASS"),
        ctrl_cpass = document.getElementById("sdr-ctrl-CPASS"),
        ctrl_estado = document.getElementById("sdr-ctrl-ESTADO");
    // Ctrls CRUD
    var btn_agregarPatron = document.getElementById("sdr-ctrl-BTNAGREGAR-PATRON"),
        btn_removerPatron = document.getElementById("sdr-ctrl-BTNREMOVER-PATRON");
    // Recuperar valores
    var tipo = ((ctrl_tipo.selectedIndex >= 0) ?
            (ctrl_tipo.options[ctrl_tipo.selectedIndex].value || '').trim() : ''),
        estado = ctrl_estado.checked;
    //  Habilitar cambio de estado
    ctrl_estado.removeAttribute("disabled");
    // Validar tipo y estado
    if ((tipo === '1' /*A*/ ) || (tipo === '2' /*D*/ ) || (tipo === '3' /*R*/ )) {
        // Si esta habilitado
        if (estado) {
            if (tipo === '3') {
                // Habilitar/Deshabilitar controles
                ctrl_patron.removeAttribute("disabled");
                ctrl_patron_rels.removeAttribute("disabled");
                // Habilitar/Deshabilitar botones
                btn_agregarPatron.removeAttribute("disabled");
                btn_removerPatron.removeAttribute("disabled");
            } else {
                // Habilitar/Deshabilitar controles
                ctrl_patron.setAttribute("disabled", "true");
                ctrl_patron_rels.setAttribute("disabled", "true");
                // Habilitar/Deshabilitar botones
                btn_agregarPatron.setAttribute("disabled", "true");
                btn_removerPatron.setAttribute("disabled", "true");
            }
            // Habilitar/Deshabilitar controles
            ctrl_email.removeAttribute("disabled");
            ctrl_nombre.removeAttribute("disabled");
            ctrl_ap.removeAttribute("disabled");
            ctrl_am.removeAttribute("disabled");
            ctrl_pass.removeAttribute("disabled");
            ctrl_cpass.removeAttribute("disabled");
        } else {
            // Habilitar/Deshabilitar controles
            ctrl_patron.setAttribute("disabled", "true");
            ctrl_patron_rels.setAttribute("disabled", "true");
            ctrl_email.setAttribute("disabled", "true");
            ctrl_nombre.setAttribute("disabled", "true");
            ctrl_ap.setAttribute("disabled", "true");
            ctrl_am.setAttribute("disabled", "true");
            ctrl_pass.setAttribute("disabled", "true");
            ctrl_cpass.setAttribute("disabled", "true");
            // Habilitar/Deshabilitar botones
            btn_agregarPatron.setAttribute("disabled", "true");
            btn_removerPatron.setAttribute("disabled", "true");
        }
    } else {
        // Habilitar/Deshabilitar controles
        ctrl_patron.setAttribute("disabled", "true");
        ctrl_patron_rels.setAttribute("disabled", "true");
        ctrl_email.setAttribute("disabled", "true");
        ctrl_nombre.setAttribute("disabled", "true");
        ctrl_ap.setAttribute("disabled", "true");
        ctrl_am.setAttribute("disabled", "true");
        ctrl_pass.setAttribute("disabled", "true");
        ctrl_cpass.setAttribute("disabled", "true");
        // Habilitar/Deshabilitar botones
        btn_agregarPatron.setAttribute("disabled", "true");
        btn_removerPatron.setAttribute("disabled", "true");
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
            ctrl_estado = document.getElementById("sdr-ctrl-ESTADO");
        // Ctrls CRUD
        var btn_agregarPatron = document.getElementById("sdr-ctrl-BTNAGREGAR-PATRON"),
            btn_removerPatron = document.getElementById("sdr-ctrl-BTNREMOVER-PATRON"),
            btn_nuevo = document.getElementById("sdr-ctrl-BTNNUEVO"),
            btn_editar = document.getElementById("sdr-ctrl-BTNEDITAR"),
            btn_salvar = document.getElementById("sdr-ctrl-BTNSALVAR");
        // Control selector tipo
        ctrl_tipo.addEventListener("change", funcOnChangeValues);
        ctrl_estado.addEventListener("change", funcOnChangeValues);
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
        btn_removerPatron.addEventListener("click", function(sender) {
            funcRemoverItem();
            sender.preventDefault();
        });
        btn_agregarPatron.addEventListener("click", function(sender) {
            funcAgregarItem();
            sender.preventDefault();
        });
        // Ejecutar busqueda inicial al cargar
        funcReconstruir(closeLoader);
    });
});