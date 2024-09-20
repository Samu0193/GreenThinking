        <div class="container-galeria">
            <h2>Imágenes de la galería </h2><br><br>
            <table id="galeria" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Usuario crea</th>
                        <th>Fecha creación</th>
                        <th>Acción</th>
                    </tr>
                </thead>

            </table>
        </div>

        <!-- Ventana Modal -->
        <div class="modal" id="modal" style="top: 0;">
            <div id="modal-flex" class="modal-flex">
                <div class="content-modal">

                    <div class="header-modal" style="margin: 0;">
                        <div class="logo">
                            <img src="<?= base_url('assets/img/hojinobg.png'); ?>" alt="Green-Thinking" />
                        </div>
                        <h2 class="title-form" id="title-form"></h2>
                        <i class="fa fa-times" id="close"></i>
                    </div>

                    <form class="form-modal" id="form-galeria" autocomplete="off" method="POST"
                        action="<?= site_url('galeria/cambiarImg'); ?>" enctype="multipart/form-data" novalidate>

                        <div class="input-field" style="margin-bottom: 20px;">
                            <input type="hidden" name="id_galeria" id="id_galeria">
                            <input type="hidden" name="nom_last_img" id="nom_last_img">
                            <div class="cont-image">
                                <div class="image-frame" id="image-frame">
                                    <p>La imagen aparecerá aquí</p>
                                </div>
                                <input type="text" name="nombre_imagen" id="nombre_imagen" style="position: absolute; transform: translateY(-100%);">
                                <label for="file-upload" class="btn">
                                    <i class="fas fa-upload"></i> Cargar
                                </label>
                                <input type="file" name="file-upload" id="file-upload" accept=".jpg,.jpeg,.png" style="display: none;" required>
                            </div>
                            <button class="btn" type="submit" id="insert-producto" style="width: 100%;">
                                <i class="far fa-images"></i> Actualizar
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>