$(document).ready(function () {
    /********************************************************************************************************************************************************
     *!*     VENTANA MODAL:
     ********************************************************************************************************************************************************/
    let modal = $("#modal");
    let title_form = $('#title-form');
    $(".modal-productos").on('click', function () {
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

    $(".modal-usuario").on('click', function () {
        loadRoles();
        modal.fadeIn();
        title_form.html('Nuevo Usuario');
        $('#form-usuarios')[0].reset();
        $('input').removeClass('error');
        $('label[class="error"]').css('display', 'none');
    });

    $("#close").on('click', function () {
        modal.hide(300);
    });

    $(window).on('click', function (e) {
        if (e.target === $("#modal-flex")[0]) {
            modal.hide(300);
        }
    });

    $(window).on('keyup', function (e) {
        if (e.key === 'Escape') {
            modal.hide(300);
        }
    });

    let pagina = window.location.href;
    if (pagina === (url + 'productos') || pagina === (url + 'galeria')) {
        // Cargar imagen
        var img = document.getElementById('upload'),
            nombre = document.getElementById('nombre_imagen');
        img.addEventListener('change', function () {

            if (valSize(this)) {
                var _URL = window.URL || window.webkitURL;
                var imgFile = new Image();
                imgFile.src = _URL.createObjectURL(this.files[0]);
                imgFile.onload = function () {
                    var ancho = imgFile.width;
                    var alto = imgFile.height
                    console.log(ancho + ' ' + alto);
                    if (ancho <= 2000 && alto <= 2000) {
                        nombre.value = img.files[0].name;

                        var fileSelected = document.getElementById("upload").files;
                        if (fileSelected.length > 0) {
                            var fileToLoad = fileSelected[0];
                            var fileReader = new FileReader();
                            fileReader.onload = function (fileLoadedEvent) {
                                var srcData = fileLoadedEvent.target.result;
                                document.getElementById("image-frame").innerHTML = '<img src="' + srcData +
                                    '" class="print-image"/>';
                            };
                            fileReader.readAsDataURL(fileToLoad);
                        }
                    } else {
                        img.value = ''; //for clearing with Jquery
                        modalErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Dimensiones m\u00e1ximas 2000x2000, elija una imagen adecuada...</p>`);
                    }
                }
            } else {
                this.value = ''; //for clearing with Jquery
                nombre.value = '';
                modalErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Tama\u00f1o m\u00e1ximo 5Mb, elija una imagen menos pesada... </p>`);
            }

        });

    }

    /********************************************************************************************************************************************************
    *!*     TABLAS:
    ********************************************************************************************************************************************************/
    $('#usuarios').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "usuario/tblUsuarios",
            "type": "POST",
            "beforeSend": function () {
                // console.log('dataTables_processing: Iniciado')
                $('.dataTables_wrapper .dataTables_processing').css({ 'display': 'flex' });
            },
            "complete": function () {
                // console.log('dataTables_processing: Terminado')
            }
        },
        "order": [],
        "language": idioma_espanol,
    });

    $('#galeria').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "galeria/tblGaleria",
            "type": "GET",
            "beforeSend": function () {
                $('.dataTables_wrapper .dataTables_processing').css({ 'display': 'flex' });
            }
        },
        "order": [],
        "language": idioma_espanol,
    });

    $('#productos').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "productos/tblProductos",
            "type": "GET",
            "beforeSend": function () {
                $('.dataTables_wrapper .dataTables_processing').css({ 'display': 'flex' });
            }
        },
        "order": [],
        "language": idioma_espanol,
    });

    $('#solimayores').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "solicitudes/verSolicitudMayores",
            "type": "GET",
            "beforeSend": function () {
                $('.dataTables_wrapper .dataTables_processing').css({ 'display': 'flex' });
            }
        },
        "order": [],
        "language": idioma_espanol,
    });

    $('#solim').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url + "solicitudes/verSolicitudMenores",
            "type": "GET",
            "beforeSend": function () {
                $('.dataTables_wrapper .dataTables_processing').css({ 'display': 'flex' });
            },
        },
        "order": [],
        "language": idioma_espanol,
    });

    $('#tbl-menores').css('visibility', 'collapse');
    $('#tbl-solicitud').change(function () {
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
        url: `${url}usuario/setRoles`,
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function (data) {
            var options = "<option selected disabled value=''>Seleccionar... </option>";
            $.each(data, function (index, object) {
                options += '<option value="' + object.id_rol + '">' + object.rol + '</option>';
            });
            $("[name='id_rol']").html(options);
        }
    });
}

/********************************************************************************************************************************************************
*!*     COMPARAR CONTRASEÑAS:
********************************************************************************************************************************************************/
var password = $('#password');
var rePassword = $('#re_password');

password.change(function () {
    if ($(this).val() !== rePassword.val() && rePassword.val() !== '') {
        rePassword.addClass('error');
        rePassword.after(`<label id="${rePassword.attr('name')}-error" class="error" for="${rePassword.attr('name')}">Las contrase\u00f1as no coinciden!</label>`);
        rePassword.val('');
        // modalErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Las contrase\u00f1as no coinciden!</p>`);
    }
});

rePassword.change(function () {
    if (password.val() !== $(this).val()) {
        console.log($(this));
        $(this).addClass('error');
        $(this).after(`<label id="${$(this).attr('name')}-error" class="error" for="${$(this).attr('name')}">Las contrase\u00f1as no coinciden!</label>`);
        $(this).val('');
        // modalErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Las contrase\u00f1as no coinciden!</p>`);
    }
});

/********************************************************************************************************************************************************
*!*     INSERTAR USUARIO:
********************************************************************************************************************************************************/
$(function () {
    $("#form-usuarios").validate({
        rules: {
            nombres: { required: false, alfaOespacio: false },
            apellidos: { required: false, alfaOespacio: false },
            f_nacimiento_mayor: { required: false, minEdadMay: false, maxEdadMay: false },
            DUI: { required: false, isDUI: false },
            email: { required: false, correo: false },
            telefono: { required: false },
            nombre_usuario: { required: false },
            password: { required: false },
            re_password: { required: false }
        },
        messages: {
            nombres: { required: 'Nombres requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            apellidos: { required: 'Apellidos requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            f_nacimiento_mayor: {
                required: 'Fecha de nacimiento requerida.',
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
        invalidHandler: function (error, element) {
            // console.log(error, element);
            modalErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Formulario inválido!</p>`);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            $.ajax({
                url: `${url}usuario/guardar`,
                data: $(form).serialize(),
                type: 'POST',
                async: false,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    toastSuccesMessage(`<p style="color: white; font-size: 1.06em;">Usuario guardado!</p>`);
                    // form.submit();
                    $("#modal").hide(300);
                    $(form)[0].reset();
                    $('#usuarios').DataTable().ajax.reload(null, false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(`Error en la solicitud AJAX:\nStatus: ${textStatus}\nError Thrown: ${errorThrown}\nMessage: ${jqXHR.responseJSON.message}`);

                    // Limpia las clases de error previas
                    $(form).find('.error').removeClass('error');
                    // $(form).find('.invalid-feedback').remove();

                    // Mensaje amigable para el usuario basado en el mensaje del servidor
                    let errorMessage = 'Ocurrió un problema al procesar su solicitud. Por favor, inténtelo de nuevo más tarde.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = ''

                        if (Array.isArray(jqXHR.responseJSON.message)) { // ErrorMessage es un array
                            jqXHR.responseJSON.message.forEach(function (item) {
                                errorMessage += item + '\n';
                            });

                        } else if (typeof jqXHR.responseJSON.message === 'object') { // ErrorMessage es un objeto.
                            $.each(jqXHR.responseJSON.message, function (campo, mensaje) {
                                // Encuentra el campo correspondiente y agrega una clase de error
                                let input = $(form).find(`[name="${campo}"]`);
                                if (input.length) {
                                    input.addClass('error');
                                    input.after(`<label id="${campo}-error" class="error" for="${campo}">${mensaje}</label>`);
                                }

                                errorMessage += mensaje + '\n';
                            });

                        } else if (typeof jqXHR.responseJSON.message === 'string') { // ErrorMessage es un string
                            errorMessage = jqXHR.responseJSON.message;

                        } else { // ErrorMessage es otro tipo de dato
                            console.log('Otro tipo de dato:', typeof jqXHR.responseJSON.message);
                        }
                    }

                    modalErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Formulario inválido!</p>`);
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
        url: `${url}galeria/cargarImg`,
        method: 'POST',
        data: { 'id_galeria': id_imagen },
        dataType: 'json',
        success: function (response) {
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
$(function () {
    $('#form-galeria').submit(function (event) {
        var forms = $('#form-galeria');
        // let modal = $("#modal");
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                // modal.hide(300);
                $('#form-galeria')[0].reset();
                toastInfoMessage(`<p style="color:white; font-size: 1.3em;">Elije una nueva imagen!</p>`);
            }
        });
    });
});

/****************************************************************************
                            INSERTAR PRODUCTO
****************************************************************************/
$(function () {
    $("#form-productos").validate({
        rules: {
            nombre_producto: { required: true },
            descripcion: { required: true },
            precio: { required: true, decimal: true },
            nombre_imagen: { required: true }
        },
        messages: {
            nombre_producto: 'Nombre requerido.',
            descripcion: 'Descripcion requerida.',
            precio: { required: 'Precio requerido.', decimal: 'Precio inv\u00e1lido.' },
            nombre_imagen: 'Imagen requerida.'
        },
        invalidHandler: function (error, element) {
            modalErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Formulario inv\u00e1lido!</p>`);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            form.submit();
            return false;
        }
    });
    $('#form-productos').submit(function (event) {
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
        url: `${url}productos/cambiarEstado/${producto}`,
        data: { 'id_producto': producto },
        type: 'POST',
        async: false,
        dataType: 'json',
        success: function () {
            toastSuccesMessage(`<p style="color: white; font-size: 1.18em;">Estado cambiado!</p>`);
            $('#productos').DataTable().ajax.reload(null, false);
        },
        error: function () {
            toastErrorMessage(`<p style="color: #fff; font-size: 1.18em;">Error al cambiar estado!</p>`);
        }
    });
}

/****************************************************************************
                            CAMBIAR ESTADO USUARIO
****************************************************************************/
function cambiarEstadoUsuario(usuario) {
    $.ajax({
        url: `${url}usuario/cambiarEstado`,
        data: { 'id_usuario': usuario },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            toastSuccesMessage('<p style="color: white; font-size: 1.18em;">' + response.message + '</p>');
            $('#usuarios').DataTable().ajax.reload(null, false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(`Error en la solicitud AJAX:\nStatus: ${textStatus}\nError Thrown: ${errorThrown}\nMessage: ${jqXHR.responseJSON.message}`);

            // Mensaje amigable para el usuario basado en el mensaje del servidor
            let errorMessage = 'Ocurrió un problema al procesar su solicitud. Por favor, inténtelo de nuevo más tarde.';
            if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                errorMessage = jqXHR.responseJSON.message;
            }

            // toastErrorMessage(`<p style="color: #fff; font-size: 1.18em;">${errorMessage}</p>`);
            toastErrorMessage(`<p style="color: #fff; font-size: 1.27em;">${errorMessage}</p>`);
        }
    });
}

// function cambiarEstadoUsuario(usuario) {
//     console.log('ID Usuario:', usuario); // Verifica que el valor sea correcto

//     $.ajax({
//         url: `${url}usuario/cambiarEstado/${usuario}`,
//         type: 'GET',
//         async: false,
//         dataType: 'json',
//         success: function(response) {
//             console.log('Respuesta del servidor:', response.success); // Verifica la respuesta
//             toastSuccesMessage(`<p style="color: white; font-size: 1.18em;">Estado cambiado!</p>`);
//             $('#usuarios').DataTable().ajax.reload(null, false);
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             console.error('Error en la solicitud AJAX:', textStatus, errorThrown); // Mensajes de error
//             toastErrorMessage(`<p style="color: #fff; font-size: 1.27em;">Error al cambiar estado!</p>`);
//         }
//     });
// }
