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
						<!-- TIPOS DE APOYO -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-TIPO"
					    		class="text-right middle">*Apoyo por:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<select
								id="sdr-ctrl-TIPO">
								<option value=""></option>
								<?php
								// RECORRER TIPOS DE APOYO
								foreach($tipos_apy as $rowt) { ?>
									<option
										value="<?php echo ($rowt->reg); ?>"
									>
										<?php echo ($rowt->tipo); ?>
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
					    		class="text-right middle">*Patrocinador:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<select
								id="sdr-ctrl-PATRON">
								<option value=""></option>
								<?php
								// RECORRER PATROCIONADORES
								foreach($patrones as $rowp) { ?>
									<option
										data-direccion="<?php echo ($rowp->direccion); ?>"
										data-tels="<?php echo ($rowp->tels); ?>"
										value="<?php echo ($rowp->reg); ?>"
									>
										<?php echo ($rowp->patron); ?>
									</option>
								<?php } ?>
							</select>
					    </div>
					    <div class="small-6 cell align-self-middle">
					    </div>
					</div>
					<!-- FILA 3 -->
					<div class="grid-x grid-padding-x">
						<!-- FECHAS -->
						<!-- ****** FECHA INICIAL -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-FECHAINI"
					    		class="text-right middle">*Fecha inicial:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-FECHAINI"
								type="date"
								value=""
								placeholder="Fecha inicial">
					    </div>
					    <!-- ****** FECHA FINAL -->
					    <div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-FECHAFIN"
					    		class="text-right middle">Fecha final:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-FECHAFIN"
								type="date"
								value=""
								placeholder="Fecha final">
					    </div>
					</div>
					<!-- FILA 4 -->
					<div class="grid-x grid-padding-x">
						<!-- HORAS -->
						<!-- ****** HORA INICIAL -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-HORAINI"
					    		class="text-right middle">Hora inicial:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-HORAINI"
								type="time"
								value=""
								placeholder="Hora inicial">
					    </div>
					    <!-- ****** HORA FINAL -->
					    <div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-HORAFIN"
					    		class="text-right middle">Hora final:
					    	</label>
						</div>
						<div class="small-7 medium-4 cell align-self-middle">
					    	<input
					    		id="sdr-ctrl-HORAFIN"
								type="time"
								value=""
								placeholder="Hora final">
					    </div>
					</div>
					<!-- FILA 5 -->
					<div class="grid-x grid-padding-x">
						<!-- ¿HAY APOYO? -->
						<div class="small-5 medium-2 cell align-self-middle">
							<label
					    		for="sdr-ctrl-HAYAPY"
					    		class="text-right middle">¿Hay apoyo?
					    	</label>
						</div>
						<div class="small-2 medium-4 cell align-self-middle">
							<input
								id="sdr-ctrl-HAYAPY"
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
									<span aria-hidden="true">
										<i class="fi-new"></i>
									</span>
								</button>
								<button id="sdr-ctrl-BTNEDITAR"
								   class="button">
									Editar
									<span aria-hidden="true">
										<i class="fi-edit"></i>
									</span>
								</button>
								<button id="sdr-ctrl-BTNSALVAR"
								   class="button success">
									Guardar
									<span aria-hidden="true">
										<i class="fi-save"></i>
									</span>
								</button>
							</div>
						</div>
					</div>
				</form>
			</section>
			<section>
				<div id="sdr-js-grid"></div>
			</section>
		</div>
	</div>
</div>