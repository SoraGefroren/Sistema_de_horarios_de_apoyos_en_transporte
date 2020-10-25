
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	// Recuperar sesión
	$sse = $this->session->userdata('s_usr');
	$stp = $this->session->userdata('s_tipo');
?>
	<!-- FOOTER -->
	<div class="grid-x grid-padding-x sdr-align-footer">
		<div class="cell small-12 align-self-middle">
			<div class="sdr-align-center">
				<p>
					leon.blanco@hotmail.com
					<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">
						<img alt="Licencia de Creative Commons"
							 style="border-width:0"
							 src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" />
					</a>
				</a>
				<br/>
				<p>
					Este obra está bajo una<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"> licencia de Creative Commons Reconocimiento-NoComercial 4.0 Internacional</a>.
				</p>
			</div>
		</div>
	</div>
	<!-- SCRIPTS -->
	<script
		src="/Proyecto_PHP/assets/_fz/js/vendor/jquery.js"
		type="text/javascript"
		charset="utf-8">
	</script>
	<script
		src="/Proyecto_PHP/assets/_fz/js/vendor/foundation.min.js"
		type="text/javascript"
		charset="utf-8">
	</script>
	<script
		src="/Proyecto_PHP/assets/_fz/js/app.js"
		type="text/javascript"
		charset="utf-8">
	</script>
	<script
		src="/Proyecto_PHP/assets/_jg/jsgrid.min.js"
		type="text/javascript"
		charset="utf-8">
	</script>
	<script
		src="/Proyecto_PHP/assets/_jg/i18n/jsgrid-es.js"
		type="text/javascript"
		charset="utf-8">
	</script>
	<script
		src="/Proyecto_PHP/assets/js/_libreria.js"
		type="text/javascript"
		charset="utf-8">
	</script>
<?php
	// Validar pagina
	if ($pag_name == 'index') {
		echo ("<script
				src=\"/Proyecto_PHP/assets/js/index.js\"
				type=\"text/javascript\"
				charset=\"utf-8\">
			  </script>");
 	} else if ($pag_name == 'login') {
 		echo ("<script
				src=\"/Proyecto_PHP/assets/js/log.js\"
				type=\"text/javascript\"
				charset=\"utf-8\">
			  </script>");
	} else if ($pag_name == 'usr') {
		// Seleccionar estilo con base la sesión
		if ($sse && !empty($sse)) {
			// Si existe una sesión
			if ($stp == 'super' ||
				$stp == 'director') {
				echo ("<script
						src=\"/Proyecto_PHP/assets/js/u/usr.js\"
						type=\"text/javascript\"
						charset=\"utf-8\">
					  </script>");
			}
		}
	} else if ($pag_name == 'patron') {
		// Seleccionar estilo con base la sesión
		if ($sse && !empty($sse)) {
			// Si existe una sesión
			if ($stp == 'super' ||
				$stp == 'director') {
				echo ("<script
						src=\"/Proyecto_PHP/assets/js/u/patron.js\"
						type=\"text/javascript\"
						charset=\"utf-8\">
					  </script>");
			}
		}
	} else if ($pag_name == 'apy') {
		// Seleccionar estilo con base la sesión
		if ($sse && !empty($sse)) {
			// Si existe una sesión
			if ($stp == 'super' ||
				$stp == 'patron') {
				echo ("<script
						src=\"/Proyecto_PHP/assets/js/u/apy.js\"
						type=\"text/javascript\"
						charset=\"utf-8\">
					  </script>");
			}
		}
	}
?>
</body>
</html>