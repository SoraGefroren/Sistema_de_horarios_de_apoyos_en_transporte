// Intentar loguearse
var tryLogin = function(usr, pass) {
        // Invocación ajax
        (function(funcRetorno) {
            // Cargar loader
            openLoader(function(closeLoader) {
                // Variable de petición
                var miPeticionAjax = new XMLHttpRequest(),
                    paramsURL = (
                        ('u=' + strToHex(usr)) +
                        '&' +
                        ('p=' + strToHex(pass))
                    );
                // Definción de ejecusión AJAX
                miPeticionAjax.onload = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        funcRetorno(this.responseText, closeLoader);
                    }
                };
                // Solicitar recursos por AJAX
                miPeticionAjax.open("POST", ('/Proyecto_PHP/index.php/apoyo_en_transporte/acceso'), true);
                miPeticionAjax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                miPeticionAjax.send(paramsURL);
            });
            // Función de retorno
        })(function(rData, closeLoader) {
            if (rData.indexOf('|') >= 0) {
                rData = rData.split('|');
                // Si primer segmento es "S"
                if (rData[0] === 'S') {
                    // Redireccionar
                    window.location.href = rData[1];
                } else {
                    // Cerrar loader
                    closeLoader();
                    // Validar retorno
                    if ((rData.length > 1) && rData[1].trim()) {
                        emitirAlerta(rData[1].trim());
                    } else {
                        emitirAlerta("No se puede iniciar la sesión");
                    }
                }
            } else {
                // Cerrar loader
                closeLoader();
                // Emitir alerta
                emitirAlerta("Credenciales incorrectas");
            }
        });
    },
    evento = function(sender) {
        var txt_email = document.getElementById("sdr-ctrl-email"),
            txt_pass = document.getElementById("sdr-ctrl-passw");
        // Validar usuario y contraseña
        if ((!txt_email.value || !txt_email.value.trim()) ||
            (!txt_pass.value || !txt_pass.value.trim())) {
            try {
                emitirAlerta("Debes ingresar el correo electrónico y la contraseña del usuario.");
                sender.preventDefault();
            } catch (e) {
                sender.preventDefault();
            }
            return;
        }
        // Probar login
        tryLogin(txt_email.value, txt_pass.value);
        sender.preventDefault();
        return;
    };

// Al cargar la página
window.addEventListener("load", function() {
    // --------------------------------------
    var frm = document.getElementById("sdr-ctrl-frm"),
        btn = document.getElementById("sdr-ctrl-submit");
    // --------------------------------------
    frm.addEventListener("submit", evento);
    btn.addEventListener("submit", evento);

});