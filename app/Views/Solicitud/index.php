		<div class="container-solicitudes">
			<div class="input-field">
				<label for="solicitudes">Tipo de Solicitud: </label>
				<select name="solicitudes" class="select-solicitudes" id="tbl-solicitud">
					<option selected disabled value="">Seleccione...</option>
					<option value="mayor">Mayores de edad</option>
					<option value="menor">Menores de edad</option>
				</select>
			</div>

			<div id="tbl-mayores">
				<h2>Solicitudes de Mayores de edad </h2><br><br>
				<table id="soli-mayores" class="display responsive nowrap" style="width: 100%;">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre completo</th>
							<th>Departamento</th>
							<th>Fecha de Ingreso</th>
							<th>Acción</th>
						</tr>
					</thead>

				</table>
			</div>

			<div id="tbl-menores">
				<h2>Solicitudes de Menores de edad </h2><br><br>
				<table id="soli-menores" class="display responsive nowrap" style="width: 100%;">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre completo</th>
							<th>Parentesco</th>
							<th>DUI referencia</th>
							<th>Departamento</th>
							<th>Fecha de Ingreso</th>
							<th>Accion</th>
						</tr>
					</thead>

				</table>
			</div>

		</div>