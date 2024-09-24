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

                    <form enctype="multipart/form-data" autocomplete="off" id="form-galeria" class="form-modal" novalidate>
                        <div class="input-field" style="margin-bottom: 20px;">

                            <div class="cont-image">
                                <div class="image-frame" id="image-frame" title="">
                                    <p>La imagen aparecerá aquí</p>
                                </div>
                                <input type="hidden" name="id_galeria" id="id_galeria">
                                <input type="hidden" name="nom_last_img" id="nom_last_img">
                                <input type="text" name="nombre_imagen" id="nombre_imagen">
                                <input type="file" name="fileUpload" id="fileUpload" class="file-img" required>
                                <label for="fileUpload" class="btn">
                                    <i class="fas fa-upload"></i> Cargar
                                </label>
                            </div>
                            <button class="btn" type="submit" id="submit-galeria" style="width: 100%;">
                                <i class="far fa-images"></i> Actualizar
                            </button>

                        </div>
                    </form>
                    
                </div>
            </div>
        </div>