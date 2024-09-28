/********************************************************************************************************************************************************
*!*     ESTILOS DE LOS INPUTS:
********************************************************************************************************************************************************/
const inputs = document.querySelectorAll(".input");

function addcl() {
    let parent = this.parentNode.parentNode;
    parent.classList.add("focus");
}

function remcl() {
    let parent = this.parentNode.parentNode;
    if (this.value == "") {
        parent.classList.remove("focus");
    }
}

inputs.forEach(input => {
    input.addEventListener("focus", addcl);
    input.addEventListener("blur", remcl);
});

/********************************************************************************************************************************************************
*!*     MENSAJES:
********************************************************************************************************************************************************/
function modalChargeMessage(titulo, mensaje) {
    Swal.fire({
        title: `${titulo}`,
        text: `${mensaje}`,
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function toastSuccesMessageLong(mensaje) {
    Swal.fire({
        toast: true,
        icon: 'success',
        iconColor: 'white',
        position: 'top-end',
        background: '#3ca230',
        title: `${mensaje}`,
        showConfirmButton: false,
        timerProgressBar: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        timer: 4000
    });
}

function toastSuccesMessageShort(mensaje) {
    Swal.fire({
        toast: true,
        icon: 'success',
        iconColor: 'white',
        position: 'top-end',
        background: '#3ca230',
        title: `${mensaje}`,
        showConfirmButton: false,
        timerProgressBar: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        timer: 2500
    });
}

function toastErrorMessage(mensaje) {
    Swal.fire({
        toast: true,
        icon: 'error',
        iconColor: '#fff',
        background: '#f00',
        position: 'top-end',
        title: `${mensaje}`,
        confirmButtonColor: "#343a40"
    });
}

function modalErrorMessage(mensaje) {
    Swal.fire({
        icon: 'error',
        iconColor: '#fff',
        background: '#f00',
        title: `${mensaje}`,
        confirmButtonColor: "#343a40"
    });
}

function toastInfoMessage(mensaje) {
    Swal.fire({
        toast: true,
        icon: 'info',
        background: '#f00',
        iconColor: 'white',
        title: `${mensaje}`,
        confirmButtonColor: "#343a40"
    });
}


/********************************************************************************************************************************************************
*!*     VALIDAR FORMATO DE CORREO:
********************************************************************************************************************************************************/
$.validator.addMethod('correo', function (value, element) {
    return this.optional(element) || /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value);
}, `Ingrese un email v\u00e1lido.`);

// $.validator.addMethod('correo', function (value, element) {
//     return this.optional(element) || /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(value);
// }, 'Ingrese un email v\u00e1lido');

/********************************************************************************************************************************************************
*!*     VALIDAR COMPARAR CONTRASEÑAS:
********************************************************************************************************************************************************/
$.validator.addMethod('equalPassword', function (value, element, param) {
    return $(param).val() !== '' ? value === $(param).val() : true;
}, 'Las contrase\u00f1as no coinciden');

/********************************************************************************************************************************************************
*!*     VALIDAR CORREO EXISTENTE EN BASE DE DATOS:
********************************************************************************************************************************************************/
let response_msg = '';
$.validator.addMethod("inEmailUsuario", function(value) {
    let response = false;
    $.ajax({
        type: 'POST',
        url: `${url}login/validarEmail`,
        data: { 'email': value },
        async: false,
        success: function(jsonResponse) {
            response     = jsonResponse.data;
            response_msg = !response ? jsonResponse.message : '';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) errorMessage = jsonResponse.message;
            response_msg = 'Hubo un error al validar este campo';
            console.log(errorMessage);
        }
    });
    return response;
}, function() {
    return response_msg;
});

/********************************************************************************************************************************************************
*!*     FORMULARIOS:
********************************************************************************************************************************************************/
$(document).ready(function() {

    // PARA QUE LOS INPUTS SIEMPRE ESTEN VACIOS AL MOMENTO DE MOSTRAR LA VISTA
    $('.input').val('');

    /********************************************************************************************************************************************************
    *!*     FORMULARIO LOGIN:
    ********************************************************************************************************************************************************/
    $('#form-login').validate({
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

    /********************************************************************************************************************************************************
    *!*     FORMULARIO ENVIO DE CORREO:
    ********************************************************************************************************************************************************/
    $('#form-reset').validate({
        rules: {
            email: { required: true, correo: true, inEmailUsuario: true }
        },
        messages: {
            email: { required: 'Email requerido' }
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
            modalChargeMessage('Enviando correo de recuperación...', 'Por favor, espere...');
            $.ajax({
                url: `${url}sendPasswordResetEmail`,
                type: 'POST',
                data: $(form).serialize(),
                success: function(jsonResponse) {

                    Swal.close();
                    $(form).remove();
                    $('#login-content').html(
                        `<div id="result" class="form-reset">
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

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    let jsonResponse = jqXHR.responseJSON;
                    if (jsonResponse.code !== 500) {
                        validator.showErrors({
                            [ $('#email').attr('name') ]: jsonResponse.message
                        });
                    } else {
                        toastErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jqXHR.responseJSON.message}</p>`);
                    }
                }
            });
            // if (validator && correoValido) { // Si el formulario es válido y el correo es válido


            // } else {
            //     // Si el correo no ha sido validado, no permitir el envío
            //     validator.showErrors({
            //         email: 'Email no existe en la base de datos'
            //     });
            // }

            return false; // Evitar que el formulario se envíe dos veces
        }
    });

    /********************************************************************************************************************************************************
    *!*     FORMULARIO CAMBIAR CONTRASEÑA:
    ********************************************************************************************************************************************************/
    if (window.location.href.includes('changePassword')) {
        $('#form-update').validate({
            rules: {
                password: { required: true },
                re_password: { required: true, equalPassword: password },
            },
            messages: {
                password: { required: 'Contrase\u00f1a requerida' },
                re_password: { required: 'Confirmar contrase\u00f1a requerido' }
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
            submitHandler: function (form, ev) {
    
                ev.preventDefault(); // Evitar la acción predeterminada del formulario
                let validator = $(form).validate();
                modalChargeMessage('Actualizando Contraseña...', 'Por favor, espere...');
                $.ajax({
                    url: `${url}updatePassword`,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function (jsonResponse) {
    
                        Swal.close();
                        toastSuccesMessageShort(`<p style="color: white; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                        setTimeout(function () {
                            location.href = `${url}login`;
                        }, 2500)
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
    
                        // console.error(`Error en la solicitud AJAX:\nStatus: ${textStatus}\nError Thrown: ${errorThrown}\nMessage: ${jqXHR.responseJSON.message}`);
                        console.error('Error details:');
                        console.error('Text Status (textStatus): ', textStatus); // Texto del estado (error, timeout, parsererror, abort)
                        console.error('Error Thrown (errorThrown): ', errorThrown); // Detalle del error arrojado (opcional)
                        console.error('State (jqXHR.readyState): ', jqXHR.readyState); // Estado de la solicitud AJAX (por ejemplo, 0 para no iniciada, 4 para completada).
                        console.error('Status Code (jqXHR.status): ', jqXHR.status); // Código de estado HTTP (por ejemplo, 404 para no encontrado, 500 para error interno del servidor)
                        console.error('Status (jqXHR.statusText): ', jqXHR.statusText); // Texto del estado HTTP (por ejemplo, "Not Found" o "Internal Server Error")
                        console.error('Headers (jqXHR.getAllResponseHeaders()): ', jqXHR.getAllResponseHeaders()); // Devuelve todas las cabeceras de respuesta HTTP en forma de una cadena (string)
                        console.error('Header name (jqXHR.getResponseHeader(Content-Type)): ', jqXHR.getResponseHeader('Content-Type')); // Devuelve el valor de una cabecera específica de la respuesta
                        console.error('Response Text (jqXHR.responseText): ', jqXHR.responseText); // Muestra la respuesta como texto
                        console.error('Response XML(jqXHR.responseXML): ', jqXHR.responseXML); // Muestra la respuesta en formato XML.
                        console.error('Response Json (jqXHR.responseJson): ', jqXHR.responseJSON); // Muestra la respuesta en formato JSON
    
                        // Limpia las clases de error previas
                        $(form).find('.invalid-feedback').remove();
                        $(form).find('.error').removeClass('error');
    
                        // Mensaje amigable para el usuario basado en el mensaje del servidor
                        let errorMessage = 'Ocurrió un problema al procesar su solicitud. Por favor, inténtelo de nuevo más tarde';
                        let jsonResponse = jqXHR.responseJSON;
                        if (jsonResponse) {
    
                            errorMessage = ''
                            if (Array.isArray(jsonResponse.message)) { // ErrorMessage es un array
    
                                jsonResponse.message.forEach(function (item) {
                                    errorMessage += item + '\n';
                                });
    
                            } else if (typeof jsonResponse.message === 'object') { // ErrorMessage es un objeto.
    
                                $.each(jsonResponse.message, function (campo, mensaje) {
                                    let input = $(form).find(`[name="${campo}"]`);
                                    if (input.length) {
                                        validator.showErrors({
                                            [campo]: mensaje
                                        });
                                    }
    
                                    errorMessage += mensaje + '\n';
                                });
    
                            } else if (typeof jsonResponse.message === 'string') { // ErrorMessage es un string
                                errorMessage = jsonResponse.message;
    
                            } else { // ErrorMessage es otro tipo de dato
                                console.log('Otro tipo de dato:', typeof jsonResponse.message);
                            }
    
                        }
    
                        if (Array.isArray(jsonResponse.message) || typeof jsonResponse.message === 'object') {
                            toastErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Error al procesar la solicitud.</p>`);
                        } else {
                            toastErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${errorMessage}</p>`);
                        }
                    }
    
                });
    
                return false; // Evitar que el formulario se envíe dos veces
            }
        });
    }

});