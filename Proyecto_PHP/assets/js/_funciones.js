function openLoader(retorno) {
    // Recuperar contenedor
    var modalLoader = document.getElementById("sdr-modal-loader"),
        axContent = null;
    // Si no existe el contenedor de errores
    if (!modalLoader) {
        // Buscar body
        axContent = document.getElementsByTagName("body");
        // Preparar elementos
        var divPricipal = document.createElement("div"),
            divDeApy = document.createElement("div"),
            img = document.createElement("img"),
            h3 = document.createElement("h3");
        // Asignar atributos
        divPricipal.setAttribute('class', 'reveal callout sdr-style-modal-loader');
        divPricipal.setAttribute('data-close-on-click', 'false'); // Para evitar su cierre
        divPricipal.setAttribute('data-close-on-esc', 'false'); // Para evitar su cierre
        divPricipal.setAttribute('id', 'sdr-modal-loader');
        divPricipal.setAttribute('data-reveal', '');
        // Asignar atributos
        divDeApy.setAttribute('class', 'sdr-align-center');
        // Establecer imagen
        img.setAttribute('class', 'sdr-align-center sdr-style-img-loader');
        img.setAttribute('src', '/Proyecto_PHP/assets/img/PrimerBus.png');
        img.setAttribute('id', 'sdr-img-loader');
        // Establecer leyenda
        h3.appendChild(document.createTextNode("Cargando..."));
        h3.setAttribute('class', 'sdr-align-center');
        // Agregar contenido al div de apoyo
        divDeApy.appendChild(img);
        divDeApy.appendChild(h3);
        // Agregar contenido al div pricipal
        divPricipal.appendChild(divDeApy);
        // Agregar elementos al contenedor
        axContent[0].appendChild(divPricipal);
        // Preparación de elemento modal
        $(divPricipal).foundation();
        modalLoader = divPricipal;
    }
    // Revelar elemento modal
    $(modalLoader).foundation('open');
    // Función de retorno
    if (retorno) {
        retorno(function() {
            $(modalLoader).foundation('close');
        });
    } else {
        return function() {
            $(modalLoader).foundation('close');
        };
    }
}

function emitirAlerta(mensaje) {
    // Recuperar contenedor
    var modalError = document.getElementById("sdr-modal-error"),
        btnStart = document.createElement("button"),
        axContent = null;
    // Si no existe el contenedor de errores
    if (!modalError) {
        // Buscar body
        axContent = document.getElementsByTagName("body");
        // Preparar elementos
        var div = document.createElement("div"),
            h3 = document.createElement("h3"),
            p = document.createElement("p"),
            btn = document.createElement("button"),
            span = document.createElement("span");
        // Asignar atributos
        div.setAttribute('class', 'reveal callout alert');
        div.setAttribute('id', 'sdr-modal-error');
        div.setAttribute('data-reveal', '');

        h3.appendChild(document.createTextNode("Error"));

        p.setAttribute('class', 'lead');
        p.setAttribute('id', 'sdr-modal-text');

        btn.setAttribute('class', 'close-button');
        btn.setAttribute('data-close', '');
        btn.setAttribute('aria-label', 'Close modal');
        btn.setAttribute('type', 'button');

        span.setAttribute('aria-hidden', 'true');
        span.innerHTML = "&times;";
        // Adjuntar elementos
        btn.appendChild(span);
        div.appendChild(h3);
        div.appendChild(p);
        div.appendChild(btn);
        // Agregar elementos al contenedor
        axContent[0].appendChild(div);
        // Ajuste
        $(document).foundation();
    }
    // Recuperar espacio de texto
    axContent = document.getElementById("sdr-modal-text");
    // Limpiar espacio de texto
    while (axContent.lastChild) {
        axContent.removeChild(axContent.lastChild);
    }
    while (mensaje.indexOf("\n") >= 0) {
        mensaje = mensaje.replace("\n", "<br/>");
    }
    axContent.innerHTML = (mensaje || '');
    // Validar botón
    btnStart = document.querySelectorAll('[data-open="sdr-modal-error"]');
    if (btnStart.length <= 0) {
        // Preparar botón
        btnStart = document.createElement("button"),
            btnStart.setAttribute('data-open', 'sdr-modal-error');
        btnStart.setAttribute('class', 'button sdr-ocultar-existencia');
        axContent = document.getElementsByTagName("body");
        axContent[0].appendChild(btnStart);
    } else {
        btnStart = btnStart[0];
    }
    // btnStart = document.querySelectorAll('[data-open="sdr-modal-error"]')[0];
    btnStart.click();
}

function validarDatosJson(datosJson, ajusTipoApy) {
    // Ajustar tipo de apoyo
    ajusTipoApy = (typeof ajusTipoApy === 'boolean') ? ajusTipoApy : true;
    // Validar
    if (datosJson instanceof Array) {
        // Recorrer datos JSON
        datosJson.forEach(function(tempObject) {
            // Realizar ajustes
            if (ajusTipoApy) {
                if (tempObject['tipo'] &&
                    (tempObject['tipo'] === 'Por dia')) {
                    tempObject['tipo'] = 'Por día';
                }
            }
            if ((tempObject['hay_apy'] === 1) ||
                (tempObject['hay_apy'] === '1')) {
                tempObject['hay_apy'] = true;
            } else {
                tempObject['hay_apy'] = false;
            }

            if (tempObject['hr_ini']) {
                tempObject['hr_ini'] = convertTo12Hour(tempObject['hr_ini']);
            }
            if (tempObject['hr_fin']) {
                tempObject['hr_fin'] = convertTo12Hour(tempObject['hr_fin']);
            }

            if (tempObject['dia_ini']) {
                tempObject['dia_ini'] = formatStrDate(tempObject['dia_ini'], '-', '/', false);
            }
            if (tempObject['dia_fin']) {
                tempObject['dia_fin'] = formatStrDate(tempObject['dia_fin'], '-', '/', false);
            }
            if (tempObject['tels']) {
                var axT = tempObject['tels'].split('|'),
                    ax = null,
                    i = 0;
                tempObject['tels'] = '';
                for (i = 0; ax = axT[i]; i++) {
                    if (ax.trim()) {
                        if (tempObject['tels'].trim()) {
                            tempObject['tels'] += (', ' + ax.trim());
                        } else {
                            tempObject['tels'] = ax.trim();
                        }
                    }
                }
            }
        });
    } else {
        datosJson = [];
    }
    // Devolver datos JSON
    return datosJson;
}