<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="grid-container sdr-contenedor-mxw">
	<div class="cell small-12">
		<div class="sdr-contenedor">
			<section class="sdr-align-center">
				<h3><?php echo ($titulo) ?></h3>
			</section>
			<hr/>
			<section>
				<form
					accept-charset="utf-8"
					id="sdr-ctrl-FRM">
					<!-- FILA 1 -->
					<div class="grid-x grid-padding-x">
						<!-- TIPOS DE USUARIOS -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-TIPO"
					    		class="text-right middle">*Tipo de usuario:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<select
								id="sdr-ctrl-TIPO">
								<option value=""></option>
								<?php
								// RECORRER TIPOS DE USUARIOS
								foreach($tipos_usr as $rowt) { ?>
									<option
										value="<?php echo ($rowt->reg); ?>"
									>
										<?php

											switch ($rowt->reg) {
												case '1': case 1:
													echo ('Administrador');
													break;
												case '2': case 2:
													echo ('Directivo');
													break;
												case '3': case 3:
													echo ('Representante');
													break;
												default:
													echo ('');
													break;
											}
										?>
									</option>
								<?php } ?>
							</select>
					    </div>
				   	</div>
					<!-- FILA 2 -->
					<div class="grid-x grid-padding-x">
						<!-- PATROCINADORES -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-PATRON"
					    		class="text-right middle">Patrocinadores:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
							<div class="small-12 cell align-self-middle">
								<div class="expanded button-group sdr-no-margins">
									<button id="sdr-ctrl-BTNAGREGAR-PATRON"
									   class="button success sdr-no-margins">
										Agregar >>
									</button>
								</div>
							</div>
							<div class="small-12 cell align-self-middle">
								<select
									size="4"
									id="sdr-ctrl-PATRON"
									class="sdr-ctrl-selector-multiple">
									<option value=""></option>
									<?php
									// RECORRER PATROCIONADORES
									foreach($patrones as $rowp) { ?>
										<option
											data-direccion="<?php echo ($rowp->direccion); ?>"
											data-tels="<?php echo ($rowp->tels); ?>"
											data-nom="<?php echo ($rowp->patron); ?>"
											value="<?php echo ($rowp->reg); ?>"
										>
											<?php echo ($rowp->patron); ?>
										</option>
									<?php } ?>
								</select>
							</div>
					    </div>
					    <!-- PATRONES RELACIONADOS -->
					    <div class="small-12 medium-6 cell align-self-middle">
					    	<div class="small-12 cell align-self-middle">
								<div class="expanded button-group sdr-no-margins">
									<button id="sdr-ctrl-BTNREMOVER-PATRON"
									   class="button alert sdr-no-margins">
										Remover
									</button>
								</div>
							</div>
							<div class="small-12 cell align-self-middle">
								<select
									size="4"
									id="sdr-ctrl-PATRON-RELS"
									class="sdr-ctrl-selector-multiple">
								</select>
							</div>
					    </div>
					</div>
					<!-- FILA 3 -->
					<div class="grid-x grid-padding-x">
						<!-- NOMBRE -->
						<!-- ****** EMAIL -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-EMAIL"
					    		class="text-right middle">*Correo electrónico:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-EMAIL"
								type="email"
								value=""
								maxlength="50"
								placeholder="Correo electrónico">
					    </div>
						<!-- ****** NOMBRE -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-NOMBRE"
					    		class="text-right middle">*Nombre:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-NOMBRE"
								type="text"
								value=""
								maxlength="50"
								placeholder="Nombre">
					    </div>
					</div>
					<!-- FILA 4 -->
					<div class="grid-x grid-padding-x">
						<!-- NOMBRE -->
						<!-- ****** APELLIDO PATERNO -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-AP"
					    		class="text-right middle">*Apellido paterno:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-AP"
								type="text"
								value=""
								maxlength="25"
								placeholder="Apellido paterno">
					    </div>
					    <!-- ****** APELLIDO MATERNO -->
					    <div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-AM"
					    		class="text-right middle">Apellido materno:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-AM"
								type="text"
								value=""
								maxlength="25"
								placeholder="Apellido materno">
					    </div>
					</div>
					<!-- FILA 5 -->
					<div class="grid-x grid-padding-x">
						<!-- CONTRASEÑA -->
						<!-- ****** CONTRASEÑA -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-PASS"
					    		class="text-right middle">Nueva contraseña:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-PASS"
								type="password"
								value=""
								maxlength="100"
								placeholder="Nueva contraseña">
					    </div>
					    <!-- ****** CONFIRMAR CONTRASEÑA -->
					    <div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-CPASS"
					    		class="text-right middle">Confirmar contraseña:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-CPASS"
								type="password"
								value=""
								maxlength="100"
								placeholder="Confirmar contraseña">
					    </div>
					</div>
					<!-- FILA 6 -->
					<div class="grid-x grid-padding-x">
						<!-- ESTADO -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-ESTADO"
					    		class="text-right middle">Estado:
					    	</label>
						</div>
						<div class="small-2 medium-4 cell align-self-middle">
							<input
								id="sdr-ctrl-ESTADO"
								type="checkbox">
						</div>
						<!-- DIR -->
						<div class="small-5 medium-6 cell align-self-middle sdr-ocultar-existencia">
							<input
								id="sdr-ctrl-REG"
								type="password">
						</div>
						<!-- BOTONES -->
						<div class="small-12 medium-6 cell align-self-middle">
							<div class="expanded button-group">
								<button id="sdr-ctrl-BTNNUEVO"
								   class="button secondary">
									Nuevo
								</button>
								<button id="sdr-ctrl-BTNEDITAR"
								   class="button">
									Editar
								</button>
								<button id="sdr-ctrl-BTNSALVAR"
								   class="button success">
									Guardar
								</button>
							</div>
						</div>
					</div>
				</form>
			</section>
			<section>
				<div id="sdr-grid-usrs"></div>
			</section>
		</div>
	</div>
</div>