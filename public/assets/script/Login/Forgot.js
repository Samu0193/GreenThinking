const inputs = document.querySelectorAll('.input');

function addcl() {
    let parent = this.parentNode.parentNode;
    parent.classList.add('focus');
}

function remcl() {
    let parent = this.parentNode.parentNode;
    if (this.value == '') {
        parent.classList.remove('focus');
    }
}

inputs.forEach(input => {
    input.addEventListener('focus', addcl);
    input.addEventListener('blur', remcl);
});

// VALIDAR FORMATO DE CORREO
jQuery.validator.addMethod('correo', function (value, element) {
    return this.optional(element) || /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value);
}, `Ingrese un email v\u00e1lido.`);

// jQuery.validator.addMethod('correo', function (value, element) {
//     return this.optional(element) || /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(value);
// }, 'Ingrese un email v\u00e1lido.');


$(document).ready(function() {

    var correoValido = false; // Variable para controlar si el correo existe o no
    var correo = $('#email');
    correo.val(''); // Limpiar el campo de email

    // Manejar los eventos change, keyup, blur
    correo.on('change keyup blur', function() { // VALIDAR CORREO EXISTENTE
        if (/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(correo.val())) {

            $.ajax({
                url: `${url}login/validarEmail`,
                data: { 'email': correo.val() },
                type: 'POST',
                dataType: 'json',
                success: function (jsonResponse) {
                    var validator = $('#form-forgot').validate();
                    if (jsonResponse.data === false) {

                        // correo.addClass('error');
                        // correo.after(`<label id="${correo.attr('name')}-error" class="error" for="${correo.attr('name')}">${jsonResponse.message}</label>`);
                        // Agregar clase de error al input
                        correo.addClass('error');   // Correo no existe o no es válido
                        correoValido = false;       // Marcar el correo como no válido
                        validator.showErrors({
                            [ correo.attr('name') ]: jsonResponse.message
                        });

                    } else {

                        // $(`#${correo.attr('name')}-error`).remove();
                        // Si es válido, eliminar clase de error
                        correo.removeClass('error');    // Correo válido
                        correoValido = true;            // Marcar el correo como válido
                        validator.element(correo);      // Validar el campo con el validador
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = 'Ocurrió un problema al procesar su solicitud. Por favor, inténtelo de nuevo más tarde.';
                    let jsonResponse = jqXHR.responseJSON;
                    if (jsonResponse) {
                        errorMessage = jsonResponse.message;
                    }
                    console.log(errorMessage);
                }
            });
        }
    });

    // Configuración de la validación del formulario
    $('#form-forgot').validate({
        rules: {
            email: { required: true, correo: true,
                /*remote: {
                    url: `${url}usuario/validarEmail`, // URL del endpoint de validación
                    type: 'POST',
                    data: {
                        email: function () {
                            return $('#email').val();
                        }
                    },
                    dataType: 'json',
                    dataFilter: function (response) {
                        let jsonResponse = JSON.parse(response);
                        return jsonResponse.data ? "false" : "true"; // Retorna "false" si ya existe
                    }
                }*/
            }
        },
        messages: {
            email: { required: 'Email requerido.',
                //remote: 'Email no existe en la base de datos.'
            }
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
            var validator = $(form).validate();

            if (validator && correoValido) { // Si el formulario es válido y el correo es válido
                Swal.fire({
                    title: 'Enviando correo de recuperación...',
                    text: 'Por favor, espere...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `${url}sendPasswordResetEmail`,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(jsonResponse) {

                        // location.href = `${url}login`;
                        Swal.close();
                        $(form).remove();
                        $('#login-content').html(
                            `<div id="result" class="form-forgot">
                                <p style="text-align: center; color: #333;">
                                    Su contraseña fue restablecida,
                                    le enviamos un mensaje a su correo para restablecer.
                                </p>
                                <br>
                                <div class="container-buttons">
                                    <a href="${url}login" class="btn" style="text-align: center; line-height: 50px; color: #fff !important;">
                                        Iniciar Sesión
                                    </a>
                                </div>
                            </div>
                            `
                        );

                        // Redirigir después de un breve retraso para asegurarse de que el usuario vea el mensaje
                        // setTimeout(function() {
                        //     location.href = `${url}login`;
                        // }, 5000);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
    
                        let jsonResponse = jqXHR.responseJSON;
                        if (jsonResponse.code !== 500) {
                            validator.showErrors({
                                [ correo.attr('name') ]: jsonResponse.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                iconColor: '#fff',
                                background: '#f00',
                                title: `<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jqXHR.responseJSON.message}</p>`,
                                confirmButtonColor: "#343a40"
                            });
                        }
                    }
                });
            } else {
                // Si el correo no ha sido validado, no permitir el envío
                validator.showErrors({
                    email: 'Email no existe en la base de datos.'
                });
            }

            return false; // Evitar que el formulario se envíe dos veces
        }
    });

});

// $(document).ready(function() {
//     $('.input').val('');
//     $("#form-forgot").submit(function(ev) { //id de formulario 
//         Swal.fire({
//             title: 'Enviando correo de recuperación...',
//             text: 'Por favor, espere...',
//             // timer: 5000,
//             allowEscapeKey: false,
//             allowOutsideClick: false,
//             didOpen: () => {
//                 Swal.showLoading();
//             }
//         });
//         $.ajax({
//             url: url + 'login/forgotPassword',
//             type: 'POST',
//             data: $(this).serialize(),
//             success: function(jsonResponse) {

//                 // Para asegurar que el valor se borre antes de la redirección
//                 $('#email').val('');
                
//                 // Redirigir después de un breve retraso para asegurarse de que el usuario vea el mensaje
//                 setTimeout(function() {
//                     location.href = url + 'login';
//                 }, 5000);
//             },
//             error: function (jqXHR, textStatus, errorThrown) {
//                 Swal.fire({
//                     // toast: true,
//                     icon: 'error',
//                     iconColor: '#fff',
//                     background: '#f00',
//                     title: `<p style="color: #fff; font-size: 1.18em;">${jqXHR.responseJSON.message}</p>`,
//                     confirmButtonColor: "#343a40"
//                 });
//             }
//         });
//         ev.preventDefault();
//     });
// });
