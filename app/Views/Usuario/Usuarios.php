        <div class="container-productos">
            <div class="input-field">
                <button class="btn modal-usuario">
                    <i class="fas fa-plus-circle"></i> Nuevo Usuario
                </button>
            </div>

            <h2>Usuarios registrados </h2><br><br>
            <table id="usuarios" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Persona</th>
                        <th>Rol</th>
                        <th>Usuario</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Fecha creación</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>

            </table>
        </div>

        <!-- Ventana Modal -->
        <div class="modal" id="modal" style="top: 0;">
            <div id="modal-flex" class="modal-flex">
                <div class="content-modal">

                    <div class="header-modal">
                        <div class="logo">
                            <img src="<?= base_url('assets/img/hojinobg.png'); ?>" alt="Green-Thinking" />
                        </div>
                        <h2 class="title-form" id="title-form"></h2>
                        <i class="fa fa-times" id="close"></i>
                    </div>
                    <!-- INSERT USUARIO -->
                    <form class="form-modal" id="form-usuarios" autocomplete="off" novalidate>
                        <div class="input-field">
                            <label for="nombres">Nombres: </label>
                            <input type="text" name="nombres" id="nombres" placeholder="Ingrese Nombres" required>
                            <label for="apellidos">Apellidos: </label>
                            <input type="text" name="apellidos" id="apellidos" placeholder="Ingrese Apellidos" required>
                            <label for="f_nacimiento_mayor">Fecha de Nacimiento: </label>
                            <input type="date" name="f_nacimiento_mayor" id="f_nacimiento_mayor" required>
                            <label for="telefono">Teléfono: </label>
                            <input type="text" name="telefono" id="telefono" placeholder="9999-9999"
                                onchange="valTel(this);" required>
                            <label for="email">Email: </label>
                            <input type="text" name="email" id="email" placeholder="Ingrese Email"
                                onchange="valEmailUser(this);" required>
                        </div>
                        <div class="input-field">
                            <label for="DUI">DUI: </label>
                            <input type="text" name="DUI" id="DUI" placeholder="99999999-9" onchange="valDui(this);"
                                required>
                            </select>
                            <label for="nombre_usuario">Nombre Usuario: </label>
                            <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Ingrese Usuario" required />
                            <label for="password">Contraseña: </label>
                            <input type="password" name="password" id="password" placeholder="••••••••••••" required>
                            <label for="re_password">Repetir Contraseña: </label>
                            <input type="password" name="re_password" id="re_password" placeholder="••••••••••••" required>
                        </div>
                        <button class="btn" type="submit"><i class="fas fa-user-plus"></i>
                            Registrar</button>
                    </form>
                </div>
            </div>
        </div>