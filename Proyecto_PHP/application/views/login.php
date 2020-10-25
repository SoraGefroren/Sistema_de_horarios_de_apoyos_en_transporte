<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="grid-container sdr-contenedor-mlw">
	<div class="cell small-12">
		<div class="sdr-contenedor">
			<section class="sdr-align-center">
				<h3><?php echo ($titulo) ?></h3>
			</section>
			<hr/>
			<form
				accept-charset="utf-8"
				class="sdr-align-center"
				id="sdr-ctrl-frm">
				<div class="grid-x grid-padding-x">
				    <div class="small-4 cell align-self-middle">
				    	<label
				    		for="sdr-ctrl-email"
				    		class="text-right middle">Correo electrónico:
				    	</label>
				    </div>
				    <div class="small-8 cell align-self-middle">
				    	<input
				    		id="sdr-ctrl-email"
							type="email"
							name="sdr-ctrl-email"
							placeholder="Correo electrónico">
				    </div>
				</div>
				<div class="grid-x grid-padding-x">
				    <div class="small-4 cell align-self-middle">
				    	<label
				    		for="sdr-ctrl-passw"
				    		class="text-right middle">Contraseña:
				    	</label>
				    </div>
				    <div class="small-8 cell align-self-middle">
				    	<input
				    		id="sdr-ctrl-passw"
							type="password"
							name="sdr-ctrl-passw"
							placeholder="Contraseña">
				    </div>
				</div>
				<div class="grid-x grid-padding-x">
				    <div class="small-12 cell align-self-middle">
						<div
							class="sdr-align-center">
							<input
								id="sdr-ctrl-submit"
								type="submit"
								name="sdr-ctrl-submit"
								value="Acceder"
								class="success button"/>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script
	src="/Proyecto_PHP/assets/js/crypto/aes.js"
	type="text/javascript"
	charset="utf-8">
</script>