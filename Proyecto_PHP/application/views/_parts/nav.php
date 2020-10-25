<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	// Recuperar sesión
	$sse = $this->session->userdata('s_usr');
	$stp = $this->session->userdata('s_tipo');
	$nom = $this->session->userdata('s_nombre');
?>

<div class="title-bar" data-responsive-toggle="sdr-nav-menu" data-hide-for="medium">
  <button class="menu-icon" type="button" data-toggle="sdr-nav-menu"></button>
  <div class="title-bar-title"><?php echo ($titulo) ?></div>
</div>

<div class="top-bar sdr-align-navbar" id="sdr-nav-menu">
  <div class="top-bar-left">
    <ul class="menu">
      <li class="menu">
  		  <a href="/Proyecto_PHP/index.php">
  		  	<strong>
  		  		<?php echo ($titulo) ?>
  		  	</strong>
  		  </a>
  	  </li>
<?php
	// ITEMS DE MENÚ
	// Seleccionar estilo con base la sesión
	if ($sse && !empty($sse)) {
		// Si existe una sesión
		if ($stp == 'super') {
			echo ("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/usuarios\">Usuarios</a></li>");
			echo ("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/patrocinadores\">Patrocinadores</a></li>");
			echo ("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/apoyos\">Apoyos</a></li>");
		} else if ($stp == 'director') {
			echo ("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/usuarios\">Usuarios</a></li>");
			echo ("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/patrocinadores\">Patrocinadores</a></li>");
		} else if ($stp == 'patron') {
			echo ("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/apoyos\">Apoyos</a></li>");
		}
		echo("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/informacion\">Json</a></li>");	
	} else {
		echo("<li><a href=\"/Proyecto_PHP/index.php/apoyo_en_transporte/informacion\">Información sobre apoyos (JSON)</a></li>");	
	}
?>
    </ul>
  </div>
<?php
	// CERRAR SESIÓN
	// Seleccionar estilo con base la sesión
	if ($sse && !empty($sse)) {
		// Si existe una sesión
?>
	  	<div class="top-bar-right">
		    <ul class="menu">
		      <li class="hide-for-small-only">
		      	<label class="middle sdr-no-margins sdr-align-navbar-lbl"><?php echo ($nom) ?></label>
		      </li>
		      <li>
		      	  <a id="sdr-ctrl-cerrar"
		      	  	 class="alert button">Cerrar sesión</a>
		      </li>
		    </ul>
		</div>
<?php
	} else {
		if (!isset($pag_name) || (isset($pag_name) && ($pag_name != 'login')) ) {
?>
		<div class="top-bar-right">
		    <ul class="menu">
		      <li>
		      	  <a href="/Proyecto_PHP/index.php/apoyo_en_transporte/acceso">Iniciar sesión</a>
		      </li>
		    </ul>
		</div>
<?php
		}
	}
?>
</div>

<?php
	// JS PARA NAVEGADOR
	// Seleccionar estilo con base la sesión
	if ($sse && !empty($sse)) {
		// Si existe una sesión
?>
		<script
			src="/Proyecto_PHP/assets/js/nav.js"
			type="text/javascript"
			charset="utf-8">
		</script>
<?php
	}
?>

