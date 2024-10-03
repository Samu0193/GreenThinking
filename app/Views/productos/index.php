        <div class="container-productos">
            <div class="input-field">
                <button class="btn modal-productos">
                    <i class="fas fa-plus-circle"></i> Nuevo Producto
                </button>
            </div>

            <h2>Productos registrados </h2><br><br>
            <table id="productos" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Usuario crea</th>
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
                            <img src="<?= base_url('public/assets/img/hojinobg.png'); ?>" alt="Green-Thinking" />
                        </div>
                        <h2 class="title-form" id="title-form"></h2>
                        <i class="fa fa-times" id="close"></i>
                    </div>

                    <!-- <form class="form-modal" id="form-productos" autocomplete="off" method="POST"
                        action="<?= site_url('productos/guardar'); ?>" enctype="multipart/form-data" novalidate> -->

                    <form enctype="multipart/form-data" autocomplete="off" id="form-productos" class="form-modal" novalidate>

                        <div class="input-field">
                            <label for="nombre_producto">Nombre: </label>
                            <input type="text" name="nombre_producto" id="nombre_producto" placeholder="Ingrese Nombre" required>
                            <label for="descripcion">Descripción: </label>
                            <textarea rows="5" cols="" name="descripcion" id="descripcion" placeholder="Ingrese Descripción" required></textarea>
                            <label for="precio">Precio: </label>
                            <input type="text" name="precio" id="precio" placeholder="Ingrese Precio" required>
                        </div>
                        <div class="input-field">
                            <div class="cont-image">
                                <div class="image-frame" id="image-frame" title="">
                                    <p>La imágen aparecerá aquí</p>
                                </div>
                                <input type="text" name="nombre_imagen" id="nombre_imagen">
                                <input type="file" name="fileUpload" id="fileUpload" class="file-img" required>
                                <label for="fileUpload" class="btn">
                                    <i class="fas fa-upload"></i> Cargar
                                </label>

                                <!-- <label for="fileUpload" class="error"> Imagen requerida</label> -->
                            </div>
                        </div>

                        <button class="btn" type="submit" id="insert-producto"><i class="fas fa-cart-plus"></i> Registrar</button>

                    </form>
                </div>
            </div>
        </div>