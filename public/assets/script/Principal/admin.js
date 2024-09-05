$(document).ready(function() {
    /****************************************************************************
                                    VENTANA MODAL
    ****************************************************************************/
    let modal = $("#modal");
    let title_form = $('#title-form');
    $(".modal-productos").on('click', function() {
        modal.fadeIn();
        title_form.html('Nuevo Producto');
        $('#form-productos')[0].reset();
        $('input').removeClass('error');
        $('textarea').removeClass('error');
        $('label[class="error"]').css('display', 'none');
        $("#image-frame").css('border', '2px solid #e1e1e1');
        $("#image-frame img").remove();
        $("#image-frame").html('<p>La imágen aparecerá aquí</p>');

    });

    $(".modal-usuario").on('click', function() {
        loadRoles();
        modal.fadeIn();
        title_form.html('Nuevo Usuario');
        $('#form-usuarios')[0].reset();
        $('input').removeClass('error');
        $('label[class="error"]').css('display', 'none');
    });

    $("#close").on('click', function() {
        modal.hide(300);
    });

    $(window).on('click', function(e) {
        if (e.target === $("#modal-flex")[0]) {
            modal.hide(300);
        }
    });

    $(window).on('keyup', function(e) {
        if (e.key === 'Escape') {
            modal.hide(300);
        }
    });

    let pagina = window.location.href;
    if (pagina === (url + 'Productos') || pagina === (url + 'Galeria')) {
        // Cargar imagen
        var img = document.getElementById('upload'),
            nombre = document.getElementById('nombre_imagen');
        img.addEventListener('change', function() {

            if (valSize(this)) {
                var _URL = window.URL || window.webkitURL;
                var imgFile = new Image();
                imgFile.src = _URL.createObjectURL(this.files[0]);
                imgFile.onload = function() {
                    var ancho = imgFile.width;
                    var alto = imgFile.height
                    console.log(ancho + ' ' + alto);
                    if (ancho <= 2000 && alto <= 2000) {
                        nombre.value = img.files[0].name;

                        var fileSelected = document.getElementById("upload").files;
                        if (fileSelected.length > 0) {
                            var fileToLoad = fileSelected[0];
                            var fileReader = new FileReader();
                            fileReader.onload = function(fileLoadedEvent) {
                                var srcData = fileLoadedEvent.target.result;
                                document.getElementById("image-frame").innerHTML = '<img src="' + srcData +
                                    '" class="print-image"/>';
                            };
                            fileReader.readAsDataURL(fileToLoad);
                        }
                    } else {
                        img.value = ''; //for clearing with Jquery
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            iconColor: '#fff',
                            background: '#f00',
                            title: '<p style="color: #fff; font-size: 1.18em;">Dimensiones m\u00e1ximas 2000x2000, elija una imagen adecuada...</p>',
                            confirmButtonColor: "#343a40"
                        });
                    }
                }
            } else {
                this.value = ''; //for clearing with Jquery
                nombre.value = '';
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    iconColor: '#fff',
                    background: '#f00',
                    title: '<p style="color: #fff; font-size: 1.18em;">Tama\u00f1o m\u00e1ximo 5Mb, elija una imagen menos pesada... </p>',
                    confirmButtonColor: "#343a40"
                });
            }

        });


    }

    /****************************************************************************
                                        TABLAS
    ****************************************************************************/
    $('#usuarios').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "usuario/tblUsuariosNuevo",
            "type": "POST",
            "beforeSend": function() {
                console.log('dataTables_processing: Iniciado')
                $('.dataTables_wrapper .dataTables_processing').css({'display': 'flex'});
            },
            "complete": function() {
                console.log('dataTables_processing: Terminado')
            }
        },
        "order": [],
        "language": {
            idioma_espanol,
            "processing": 'Cargando datos, por favor espera...'
        }
    });

    $('#galeria').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "galeria/tblGaleria",
            "type": "GET",
            "beforeSend": function() {
                console.log('dataTables_processing: Iniciado')
                $('.dataTables_wrapper .dataTables_processing').css({'display': 'flex'});
            },
            "complete": function() {
                console.log('dataTables_processing: Terminado')
            }
        },
        "order": [],
        "language": {
            idioma_espanol,
            "processing": 'Cargando datos, por favor espera...'
        }
    });

    $('#productos').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "productos/tblProductos",
            "type": "GET",
            "beforeSend": function() {
                console.log('dataTables_processing: Iniciado')
                $('.dataTables_wrapper .dataTables_processing').css({'display': 'flex'});
            },
            "complete": function() {
                console.log('dataTables_processing: Terminado')
            }
        },
        "order": [],
        "language": {
            idioma_espanol,
            "processing": 'Cargando datos, por favor espera...'
        }
    });

    $('#solimayores').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "solicitudes/verSolicitudMayores",
            "type": "GET",
            "beforeSend": function() {
                console.log('dataTables_processing: Iniciado')
                $('.dataTables_wrapper .dataTables_processing').css({'display': 'flex'});
            },
            "complete": function() {
                console.log('dataTables_processing: Terminado')
            }
        },
        "order": [],
        "language": {
            idioma_espanol,
            "processing": 'Cargando datos, por favor espera...'
        }
    });

    $('#solim').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "solicitudes/verSolicitudMenores",
            "type": "GET",
            "beforeSend": function() {
                console.log('dataTables_processing: Iniciado')
                $('.dataTables_wrapper .dataTables_processing').css({'display': 'flex'});
            },
            "complete": function() {
                console.log('dataTables_processing: Terminado')
            }
        },
        "order": [],
        "language": {
            idioma_espanol,
            "processing": 'Cargando datos, por favor espera...'
        }
    });

    $('#tbl-menores').css('visibility', 'collapse');
    $('#tbl-solicitud').change(function() {
        if ($(this).val() === 'mayor') {
            $('#tbl-mayores').css('display', 'block');
            $('#tbl-menores').css('visibility', 'collapse');
        } else {
            $('#tbl-mayores').css('display', 'none');
            $('#tbl-menores').css('visibility', 'visible');
        }
    });

});

/****************************************************************************
                                    REGISTRO
****************************************************************************/
function loadRoles() {
    $.ajax({
        url: url + "usuario/setRoles",
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data) {
            var options = "<option selected disabled value=''>Seleccionar... </option>";
            $.each(data, function(index, object) {
                options += '<option value="' + object.id_rol + '">' + object.rol + '</option>';
            });
            $("[name='id_rol']").html(options);
        }
    });
}

// COMPARAR CONTRASEÑA 
var password = $('#password');
var rePassword = $('#re_password');
$('#password').change(function() {
    if (password.val() != rePassword.val() && rePassword.val() != '') {
        Swal.fire({
            toast: true,
            icon: 'error',
            iconColor: '#fff',
            background: '#f00',
            title: '<p style="color: #fff; font-size: 1.27em;">Las contrase\u00f1as no coinciden!</p>',
            confirmButtonColor: "#343a40"
        })
        $('#re_password').val('');
    }
});

// COMPARAR CONTRASEÑA CON CONTRASEÑA
$('#re_password').change(function() {
    if (password.val() != rePassword.val()) {
        Swal.fire({
            toast: true,
            icon: 'error',
            iconColor: '#fff',
            background: '#f00',
            title: '<p style="color: #fff; font-size: 1.27em;">Las contrase\u00f1as no coinciden!</p>',
            confirmButtonColor: "#343a40"
        })
        $(this).val('');
    }
});

/****************************************************************************
                            INSERTAR USUARIO
****************************************************************************/
$(function() {
    $("#form-usuarios").validate({
        rules: {
            nombres: { required: true, alfaOespacio: true },
            apellidos: { required: true, alfaOespacio: true },
            f_nacimiento_mayor: { required: true, min: false, max: false, minEdadMay: true, maxEdadMay: true },
            DUI: { required: true, isDUI: true },
            email: { required: true, correo: true },
            telefono: { required: true },
            nombre_usuario: { required: true },
            password: { required: true },
            re_password: { required: true }
        },
        messages: {
            nombres: { required: 'Nombres requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            apellidos: { required: 'Apellidos requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            f_nacimiento_mayor: {
                required: 'Fechade nacimiento requerida.',
                minEdadMay: 'Edad m\u00e1xima 40 a\u00f1os',
                maxEdadMay: 'Edad m\u00ednima 18 a\u00f1os'
            },
            DUI: { required: 'DUI requerido.', isDUI: 'DUI inv\u00e1lido.' },
            email: { required: 'Email requerido.', correo: 'Ingrese un email v\u00e1lido.' },
            telefono: 'Tel\u00f3fono requerido.',
            nombre_usuario: 'Usuario requerido.',
            password: 'Contrase\u00f1a requerida.',
            re_password: 'Repetir contrase\u00f1a requerido.'
        },
        invalidHandler: function(error, element) {
            Swal.fire({
                icon: 'error',
                iconColor: '#fff',
                background: '#f00',
                title: '<p style="color: #fff; font-size: 1.18em;">Campos vac\u00edos o inv\u00e1lidos!</p>',
                confirmButtonColor: "#343a40"
            });
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $.ajax({
                url: url + 'usuario/guardar',
                data: $(form).serialize(),
                type: 'POST',
                async: false,
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        iconColor: 'white',
                        position: 'top-end',
                        background: 'dodgerblue',
                        title: '<p style="color: white; font-size: 1.06em;">Usuario guardado!</p>',
                        showConfirmButton: false,
                        timerProgressBar: true,
                        timer: 3000
                    });
                    // form.submit();
                    $("#modal").hide(300);
                    $(form)[0].reset();
                    $('#usuarios').DataTable().ajax.reload(null, false);
                }
            });
            return false;
        }
    });
});


/****************************************************************************
                            CARGAR IMAGEN
****************************************************************************/
function cargarImg(id_imagen) {
    let modal = $("#modal");
    let title_form = $('#title-form');
    modal.fadeIn();
    title_form.html('Cambiar Imagen');
    $('#form-galeria')[0].reset();

    $.ajax({
        url: url + 'galeria/cargarImg',
        method: 'POST',
        data: { 'id_galeria': id_imagen },
        dataType: 'json',
        success: function(response) {
            var imagenOriginal = response.ruta_archivo.replace(/^.*[\\\/]/, '');
            $('#id_galeria').val(response.id_galeria);
            $('#imagen_original').val(imagenOriginal);
            $("#image-frame").html('<img src="' + response.ruta_archivo + '" class="print-image"/>');
            $('#galeria').DataTable().ajax.reload(null, false);
        }
    });
}

/****************************************************************************
                            CAMBIAR IMAGEN
****************************************************************************/
$(function() {
    $('#form-galeria').submit(function(event) {
        var forms = $('#form-galeria');
        // let modal = $("#modal");
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                // modal.hide(300);
                $('#form-galeria')[0].reset();
                Swal.fire({
                    toast: true,
                    icon: 'info',
                    background: '#f00',
                    iconColor: 'white',
                    title: '<p style="color:white; font-size: 1.3em;">Elije una nueva imagen!</p>',
                    confirmButtonColor: "#343a40"
                });
            }
        });
    });
});

/****************************************************************************
                            INSERTAR PRODUCTO
****************************************************************************/
$(function() {
    $("#form-productos").validate({
        rules: {
            nombre_producto: { required: true },
            apellidos: { required: true },
            precio: { required: true, decimal: true },
            nombre_imagen: { required: true }
        },
        messages: {
            nombre_producto: 'Nombre requerido.',
            descripcion: 'Descripcion requerida.',
            precio: { required: 'Precio requerido.', decimal: 'Precio nv\u00e1lido' },
            nombre_imagen: 'Imagen requerida.'
        },
        invalidHandler: function(error, element) {
            Swal.fire({
                icon: 'error',
                iconColor: '#fff',
                background: '#f00',
                title: '<p style="color: #fff; font-size: 1.18em;">Campos vac\u00edos o inv\u00e1lidos!</p>',
                confirmButtonColor: "#343a40"
            });
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            form.submit();
            return false;
        }
    });
    $('#form-productos').submit(function(event) {
        if ($("#nombre_imagen").val() != '') {
            $("#image-frame").css('border', '2px solid #e1e1e1');
        } else {
            $("#image-frame").css('border', '2px solid #ff0800');
        }
    });
});

/****************************************************************************
                            CAMBIAR ESTADO PRODUCTO
****************************************************************************/
function cambiarEstadoProducto(producto) {
    $.ajax({
        url: url + 'productos/cambiarEstado/' + producto,
        data: { 'id_producto': producto },
        type: 'POST',
        async: false,
        dataType: 'json',
        success: function() {
            Swal.fire({
                toast: true,
                icon: 'success',
                iconColor: 'white',
                position: 'top-end',
                background: 'dodgerblue',
                title: '<p style="color: white; font-size: 1.18em;">Estado cambiado!</p>',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 2500
            });
            $('#productos').DataTable().ajax.reload(null, false);
        },
        error: function() {
            Swal.fire({
                toast: true,
                icon: 'error',
                iconColor: '#fff',
                position: 'top-end',
                background: '#f00',
                title: '<p style="color: #fff; font-size: 1.27em;">Error al cambiar estado!</p>',
                confirmButtonColor: "#343a40"
            });
        }
    });
}

/****************************************************************************
                            CAMBIAR ESTADO USUARIO
****************************************************************************/
function cambiarEstadoUsuario(usuario) {
    $.ajax({
        url: url + 'usuario/cambiarEstado/' + usuario,
        data: { 'id_usuario': usuario },
        type: 'POST',
        async: false,
        dataType: 'json',
        success: function() {
            Swal.fire({
                toast: true,
                icon: 'success',
                iconColor: 'white',
                position: 'top-end',
                background: 'dodgerblue',
                title: '<p style="color: white; font-size: 1.18em;">Estado cambiado!</p>',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 2500
            });
            $('#usuarios').DataTable().ajax.reload(null, false);
        },
        error: function() {
            Swal.fire({
                toast: true,
                icon: 'error',
                iconColor: '#fff',
                position: 'top-end',
                background: '#f00',
                title: '<p style="color: #fff; font-size: 1.27em;">Error al cambiar estado!</p>',
                confirmButtonColor: "#343a40"
            });
        }
    });
}