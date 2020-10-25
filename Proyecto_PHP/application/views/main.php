<?php
	// https://192.168.100.18/Proyecto_PHP/index.php/apoyo_en_transporte/informacion/
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="grid-container sdr-contenedor-mxw">
	<div class="cell small-12">
		<div class="sdr-contenedor">
			<section class="sdr-align-center">
				<h1><?php echo ($titulo) ?></h1>
			</section>
			<hr/>
			<div class="cell small-12 sdr-align-right">
				<a class="success button"
		      	   href="/Proyecto_PHP/index.php/apoyo_en_transporte/informacion">Ver apoyos (JSON)</a>
			</div>
			<section>
				<div id="sdr-js-grid"></div>
			</section>
		</div>
	</div>
</div>