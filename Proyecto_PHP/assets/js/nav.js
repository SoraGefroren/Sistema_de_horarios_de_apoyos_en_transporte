// --------------------------------------
var btnCS = document.getElementById("sdr-ctrl-cerrar");
// --------------------------------------
btnCS.addEventListener("click", function(sender) {
    // Invocación ajax
    (function(funcRetorno) {
        // Variable de petición
        var miPeticionAjax = new XMLHttpRequest();
        // Definción de ejecusión AJAX
        miPeticionAjax.onload = function() {
            if (this.readyState == 4 && this.status == 200) {
                funcRetorno(this.responseText);
            }
        };
        // Solicitar recursos por AJAX
        miPeticionAjax.open("POST", ('/Proyecto_PHP/index.php/apoyo_en_transporte/cerrar'), true);
        miPeticionAjax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        miPeticionAjax.send("");
    })(function(rData) {
        // Validar retorno
        if (rData.indexOf('|') > 0) {
            rData = rData.split('|');
            // Si primer segmento es "S"
            if (rData[0] === 'S') {
                // Redireccionar
                window.location.href = rData[1];
            } else {
                emitirAlerta("No se puede cerrar la sesión");
            }
        } else {
            emitirAlerta("No se puede cerrar la sesión");
        }
    });
    // Prevenir evento normal
    sender.preventDefault();
    return;
});