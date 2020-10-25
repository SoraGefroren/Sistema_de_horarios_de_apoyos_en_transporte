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
						<!-- PATRON -->
						<!-- ****** PATRON -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-PATRON"
					    		class="text-right middle">*Patrocinador:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-PATRON"
								type="text"
								value=""
								maxlength="50"
								placeholder="Patrocinador">
					    </div>
					</div>
					<!-- FILA 2 -->
					<div class="grid-x grid-padding-x">
						<!-- DIRECCION -->
						<!-- ****** DIRECCION -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-DIRECCION"
					    		class="text-right middle">*Dirección:
					    	</label>
						</div>
						<div class="small-7 medium-10 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-DIRECCION"
								type="text"
								value=""
								maxlength="100"
								placeholder="Dirección">
					    </div>
					</div>
					<!-- FILA 3 -->
					<div class="grid-x grid-padding-x">
						<!-- TELEFONOS -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-TEL"
					    		class="text-right middle">*Teléfonos:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
							<div class="small-12 cell align-self-middle">
								<div class="expanded button-group sdr-no-margins">
									<button id="sdr-ctrl-BTNAGREGAR-TEL"
									   class="button success sdr-no-margins">
										Agregar >>
									</button>
								</div>
							</div>
							<div class="small-12 cell">
								<input
						    		id="sdr-ctrl-TEL"
									type="number"
									value=""
									maxlength="15"
									max="999999999999999"
									placeholder="Teléfono">
							</div>
					    </div>
					    <!-- TEL DEL PATRON -->
					    <div class="small-12 medium-6 cell align-self-middle">
					    	<div class="small-12 cell align-self-middle">
								<div class="expanded button-group sdr-no-margins">
									<button id="sdr-ctrl-BTNREMOVER-TEL"
									   class="button alert sdr-no-margins">
										Remover
									</button>
								</div>
							</div>
							<div class="small-12 cell align-self-middle">
								<select
									size="4"
									id="sdr-ctrl-TEL-RELS"
									class="sdr-ctrl-selector-multiple">
								</select>
							</div>
					    </div>
					</div>
					<!-- FILA 4 -->
					<div class="grid-x grid-padding-x">
						<!-- DIR -->
						<div class="small-7 medium-6 cell align-self-middle">
							<input
								id="sdr-ctrl-REG"
								type="password"
								class="sdr-ocultar-existencia">
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
				<div id="sdr-grid-patrones"></div>
			</section>
		</div>
	</div>
</div>