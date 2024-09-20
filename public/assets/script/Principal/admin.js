/********************************************************************************************************************************************************
*!*     COMPARAR CONTRASEÑAS:
********************************************************************************************************************************************************/
jQuery.validator.addMethod('equalPassword', function (value, element, param) {
    return $(param).val() !== '' ? value === $(param).val() : true;
}, 'Las contrase\u00f1as no coinciden');

// var password   = $('#password');
// var rePassword = $('#re_password');

// password.change(function () {
//     if ($(this).val() !== rePassword.val() && rePassword.val() !== '') {
//         rePassword.addClass('error');
//         rePassword.after(`<label id="${rePassword.attr('name')}-error" class="error" for="${rePassword.attr('name')}">Las contrase\u00f1as no coinciden!</label>`);
//         rePassword.val('');
//         // modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Las contrase\u00f1as no coinciden!</p>`);
//     }
// });

// rePassword.change(function () {
//     if (password.val() !== $(this).val()) {
//         $(this).addClass('error');
//         $(this).after(`<label id="${$(this).attr('name')}-error" class="error" for="${$(this).attr('name')}">Las contrase\u00f1as no coinciden!</label>`);
//         $(this).val('');
//         // modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Las contrase\u00f1as no coinciden!</p>`);
//     }
// });

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
                options += `<option value="${object.id_rol}">${object.rol}</option>`;
            });
            $("[name='id_rol']").html(options);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) {
                errorMessage = jsonResponse.message;
            }
            console.log(errorMessage);
        }
    });
}

/****************************************************************************
                            CARGAR IMAGEN
****************************************************************************/
function cargarImg(id_imagen) {
    $.ajax({
        url: `${url}galeria/cargarImg`,
        method: 'POST',
        data: { 'id_galeria': id_imagen },
        dataType: 'json',
        success: function (jsonResponse) {
            let modal = $("#modal");
            let title_form = $('#title-form');
            title_form.html('Cambiar Imagen');
            $('body').addClass('no-scroll'); // Deshabilitar el scroll
            $('#form-galeria')[0].reset();
            modal.fadeIn();

            // console.log(jsonResponse);
            let imagenOriginal = jsonResponse.data.ruta_archivo.replace(/^.*[\\\/]/, '');
            console.log(imagenOriginal);
            $('#id_galeria').val(jsonResponse.data.id_galeria);
            $('#nom_last_img').val(imagenOriginal);
            $("#image-frame").html(`<img src="${jsonResponse.data.ruta_archivo}" class="print-image"/>`);
            $('#galeria').DataTable().ajax.reload(null, false);

        },
        error: function(jqXHR, textStatus, errorThrown) {

            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) {
                errorMessage = jsonResponse.message;
            }
            modalErrorMessage(`<p style="color: #fff; font-size: 1.27em; font-weight: 100;">${errorMessage}</p>`);
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
        success: function (jsonResponse) {
            toastSuccesMessageShort(`<p style="color: white; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
            $('#usuarios').DataTable().ajax.reload(null, false);
        },
        error: function (jqXHR, textStatus, errorThrown) {

            // Mensaje amigable para el usuario basado en el mensaje del servidor
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            console.error(`Error en la solicitud AJAX:\nStatus: ${textStatus}\nError Thrown: ${errorThrown}\nMessage: ${jsonResponse.message}`);

            if (jsonResponse) {
                errorMessage = jsonResponse.message;
            }
            toastErrorMessage(`<p style="color: #fff; font-size: 1.27em; font-weight: 100;">${errorMessage}</p>`);
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
//             toastSuccesMessageShort(`<p style="color: white; font-size: 1.18em; font-weight: 100;">Estado cambiado!</p>`);
//             $('#usuarios').DataTable().ajax.reload(null, false);
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             console.error('Error en la solicitud AJAX:', textStatus, errorThrown); // Mensajes de error
//             toastErrorMessage(`<p style="color: #fff; font-size: 1.27em; font-weight: 100;">Error al cambiar estado!</p>`);
//         }
//     });
// }

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
            toastSuccesMessageShort(`<p style="color: white; font-size: 1.18em; font-weight: 100;">Estado cambiado!</p>`);
            $('#productos').DataTable().ajax.reload(null, false);
        },
        error: function () {
            toastErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Error al cambiar estado!</p>`);
        }
    });
}

$(document).ready(function () {

    /********************************************************************************************************************************************************
     *!*     VENTANA MODAL:
     ********************************************************************************************************************************************************/
    let modal = $("#modal");
    let title_form = $('#title-form');
    $(".modal-productos").on('click', function () {
        $('body').addClass('no-scroll'); // Deshabilitar el scroll
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
        $('body').addClass('no-scroll'); // Deshabilitar el scroll
        loadRoles();
        modal.fadeIn();
        title_form.html('Nuevo Usuario');
        $('#form-usuarios')[0].reset();
        $('input').removeClass('error');
        $('label[class="error"]').css('display', 'none');
    });

    $("#close").on('click', function () {
        $('body').removeClass('no-scroll'); // Deshabilitar el scroll
        modal.hide(300);
    });

    $(window).on('click', function (e) {
        if (e.target === $("#modal-flex")[0]) {
            $('body').removeClass('no-scroll'); // Deshabilitar el scroll
            modal.hide(300);
        }
    });

    $(window).on('keyup', function (e) {
        if (e.key === 'Escape') {
            $('body').removeClass('no-scroll'); // Deshabilitar el scroll
            modal.hide(300);
        }
    });

    let pagina = window.location.href;
    if (pagina === (`${url}productos`) || pagina === (`${url}galeria`)) {
        // Cargar imagen
        var img    = document.getElementById('file-upload'),
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

                        var fileSelected = document.getElementById("file-upload").files;
                        if (fileSelected.length > 0) {
                            var fileToLoad = fileSelected[0];
                            var fileReader = new FileReader();
                            fileReader.onload = function (fileLoadedEvent) {
                                var srcData = fileLoadedEvent.target.result;
                                document.getElementById("image-frame").innerHTML =
                                    `<img src="${srcData}" class="print-image"/>`;
                            };
                            fileReader.readAsDataURL(fileToLoad);
                        }
                    } else {
                        img.value = ''; //for clearing with Jquery
                        modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Dimensiones m\u00e1ximas 2000x2000, elija una imagen adecuada...</p>`);
                    }
                }
            } else {
                this.value = ''; //for clearing with Jquery
                nombre.value = '';
                modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Tama\u00f1o m\u00e1ximo 5Mb, elija una imagen menos pesada... </p>`);
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
            "url": `${url}usuario/tblUsuarios`,
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
            "url": `${url}galeria/tblGaleria`,
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
            "url": `${url}productos/tblProductos`,
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
            "url": `${url}solicitudes/verSolicitudMayores`,
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
            "url": `${url}solicitudes/verSolicitudMenores`,
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

    /********************************************************************************************************************************************************
    *!*     INSERTAR USUARIO:
    ********************************************************************************************************************************************************/
    $(function () {
        $("#form-usuarios").validate({
            rules: {
                nombres: { required: true, alfaOespacio: true },
                apellidos: { required: true, alfaOespacio: true },
                f_nacimiento_mayor: { required: true, minEdadMay: true, maxEdadMay: true },
                DUI: { required: true, isDUI: true },
                email: { required: true, correo: true },
                telefono: { required: true },
                nombre_usuario: { required: true },
                password: { required: true },
                re_password: { required: true, equalPassword: password }
            },
            messages: {
                nombres: { required: 'Nombres requeridos' },
                apellidos: { required: 'Apellidos requeridos' },
                f_nacimiento_mayor: {
                    required: 'Fecha de nacimiento requerida',
                    min: 'Edad m\u00e1xima 40 a\u00f1os',
                    max: 'Edad m\u00ednima 18 a\u00f1os'
                },
                DUI: { required: 'DUI requerido' },
                email: { required: 'Email requerido' },
                telefono: 'Tel\u00f3fono requerido',
                nombre_usuario: 'Usuario requerido',
                password: 'Contrase\u00f1a requerida',
                re_password: 'Repetir contrase\u00f1a requerido'
            },
            invalidHandler: function (error, element) {
                // console.log(error, element);
                modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Formulario inválido!</p>`);
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: `${url}usuario/create`,
                    data: $(form).serialize(),
                    type: 'POST',
                    async: false,
                    dataType: 'json',
                    success: function (jsonResponse) {
                        console.log(jsonResponse);
                        toastSuccesMessageShort(`<p style="color: white; font-size: 1.06em; font-weight: 100;">${jsonResponse.message}</p>`);
                        // form.submit();
                        $("#modal").hide(300);
                        $(form)[0].reset();
                        $('#usuarios').DataTable().ajax.reload(null, false);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                        // Limpia las clases de error previas
                        $(form).find('.invalid-feedback').remove();
                        $(form).find('.error').removeClass('error');

                        // Mensaje amigable para el usuario basado en el mensaje del servidor
                        let errorMessage = errorMsgEstandar;
                        let jsonResponse = jqXHR.responseJSON;
                        if (jsonResponse) {
                            errorMessage = ''

                            if (Array.isArray(jsonResponse.message)) { // ErrorMessage es un array
                                jsonResponse.message.forEach(function (item) {
                                    errorMessage += item + '\n';
                                });

                            } else if (typeof jsonResponse.message === 'object') { // ErrorMessage es un objeto.
                                $.each(jsonResponse.message, function (campo, mensaje) {
                                    // Encuentra el campo correspondiente y agrega una clase de error
                                    let input = $(form).find(`[name="${campo}"]`);
                                    if (input.length) {
                                        input.addClass('error');
                                        input.after(`<label id="${campo}-error" class="error" for="${campo}">${mensaje}</label>`);
                                    }

                                    errorMessage += mensaje + '\n';
                                });

                            } else if (typeof jsonResponse.message === 'string') { // ErrorMessage es un string
                                errorMessage = jsonResponse.message;

                            } else { // ErrorMessage es otro tipo de dato
                                console.log('Otro tipo de dato:', typeof jsonResponse.message);
                            }

                        }

                        if (jsonResponse.code !== 500) {
                            modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Formulario inválido!</p>`);
                        } else {
                            toastErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                        }
                    }
                });
                return false;
            }
        });
    });

    /****************************************************************************
                                CAMBIAR IMAGEN
    ****************************************************************************/
    $('#form-galeri').validate({
        rules: {
            nombre: { required: true },
            password: { required: true }
        },
        messages: {
            nombre: { required: 'Nombre de usuario requerido' },
            password: { required: 'Contraseña requerida' }
        },
        highlight: function (element) {
            $(element).closest('.input-div').addClass('error'); // Agrega la clase 'error' al div padre
        },
        unhighlight: function (element) {
            $(element).closest('.input-div').removeClass('error'); // Quita la clase 'error' del div padre
        },
        invalidHandler: function (event, validator) {
            event.preventDefault(); // Evitar recarga de página
        },
        submitHandler: function(form, ev) {

            ev.preventDefault(); // Evitar la acción predeterminada del formulario
            let validator = $(form).validate();
            $.ajax({
                url: `${url}login/verifica`,
                type: 'POST',
                data: $(form).serialize(),
                success: function(jsonResponse) {
                    location.reload(); //devuelve una url con json
                },
                error: function (jqXHR, textStatus, errorThrown) {

                    let errorMessage = 'Ocurrió un problema al procesar su solicitud. Por favor, inténtelo de nuevo más tarde';
                    let jsonResponse = jqXHR.responseJSON;
                    if (jsonResponse) {
                        if (typeof jsonResponse.message === 'object') {

                            $.each(jsonResponse.message, function (campo, mensaje) {
                                let input = $(form).find(`[name="${campo}"]`);
                                if (input.length) {
                                    validator.showErrors({
                                        [campo]: mensaje
                                    });
                                }
                                errorMessage += mensaje + '\n';
                            });

                        } else {
                            errorMessage = jsonResponse.message;
                        }
                    }

                    if (typeof jsonResponse.message === 'object') {
                        toastErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Error al procesar la solicitud.</p>`);
                    } else {
                        toastErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${errorMessage}</p>`);
                    }
                }
            });

            return false; // Evitar que el formulario se envíe dos veces
        }
    });
    // $(function () {
    //     $('#form-galeria').submit(function (event) {
    //         var forms = $('#form-galeria');
    //         // let modal = $("#modal");
    //         var validation = Array.prototype.filter.call(forms, function (form) {
    //             if (!form.checkValidity()) {
    //                 event.preventDefault();
    //                 event.stopPropagation();
    //                 // modal.hide(300);
    //                 $('#form-galeria')[0].reset();
    //                 toastInfoMessage(`<p style="color:white; font-size: 1.3em; font-weight: 100;">Elije una nueva imagen!</p>`);
    //             }
    //         });
    //     });
    // });

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
                nombre_producto: 'Nombre requerido',
                descripcion: 'Descripcion requerida',
                precio: { required: 'Precio requerido' },
                nombre_imagen: 'Imagen requerida'
            },
            invalidHandler: function (error, element) {
                modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Formulario inv\u00e1lido!</p>`);
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

});