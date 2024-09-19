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

/********************************************************************************************************************************************************
*!*     SWEET ALERT:
********************************************************************************************************************************************************/

// Swal.fire('¡Hola!', 'Este es un mensaje básico.', 'success');

// Swal.fire({
//     title: '¡Éxito!',
//     text: 'Tu operación se realizó correctamente.',
//     icon: 'success',
//     confirmButtonText: 'Aceptar'
// });

// Swal.fire({
//     title: '¿Estás seguro?',
//     text: "¡No podrás revertir esto!",
//     icon: 'warning',
//     showCancelButton: true,
//     confirmButtonColor: '#3085d6',
//     cancelButtonColor: '#d33',
//     confirmButtonText: 'Sí, eliminarlo!',
//     cancelButtonText: 'Cancelar'
// }).then((result) => {
//     if (result.isConfirmed) {
//         Swal.fire(
//             '¡Eliminado!',
//             'Tu archivo ha sido eliminado.',
//             'success'
//         )
//     }
// });

// Swal.fire({
//     title: 'Introduce tu nombre',
//     input: 'text',
//     inputPlaceholder: 'Escribe tu nombre aquí',
//     showCancelButton: true,
//     confirmButtonText: 'Enviar',
//     cancelButtonText: 'Cancelar',
//     inputValidator: (value) => {
//         if (!value) {
//             return 'Debes escribir algo!';
//         }
//     }
// }).then((result) => {
//     if (result.isConfirmed) {
//         Swal.fire(`Tu nombre es: ${result.value}`);
//     }
// });

// Swal.fire({
//     title: 'Cerrando en 3 segundos...',
//     timer: 3000,
//     timerProgressBar: true,
//     showConfirmButton: false,
//     onBeforeOpen: () => {
//         Swal.showLoading()
//     },
//     onClose: () => {
//         console.log('Alerta cerrada');
//     }
// });

// Swal.fire({
//     toast: true,
//     position: 'top-end',  // Posición en la esquina superior derecha
//     icon: 'success',
//     title: 'Guardado exitosamente',
//     showConfirmButton: false,  // Sin botón de confirmación
//     timer: 3000,  // Se cierra automáticamente en 3 segundos
//     timerProgressBar: true  // Barra de progreso
// });


/********************************************************************************************************************************************************
*!*     VALIDAR COMPARAR CONTRASEÑAS:
********************************************************************************************************************************************************/
jQuery.validator.addMethod('equalPassword', function (value, element, param) {
    return $(param).val() !== '' ? value === $(param).val() : true;
}, 'Las contrase\u00f1as no coinciden.');

$(document).ready(function () {
    // Limpiar todos los campos de entrada
    $('.input').val('');

    // Configuración de la validación del formulario
    $('#form-reset').validate({
        rules: {
            password: { required: false },
            re_password: { required: false },
        },
        messages: {
            password: { required: 'Contrase\u00f1a requerida.' },
            re_password: { required: 'Confirmar contrase\u00f1a requerido.' }
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
            Swal.fire({
                title: 'Actualizando Contraseña...',
                text: 'Por favor, espere...',
                // timer: 3000,
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `${url}updatePassword`,
                type: 'POST',
                data: $(form).serialize(),
                success: function (jsonResponse) {

                    Swal.close();
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        iconColor: 'white',
                        position: 'top-end',
                        background: 'dodgerblue',
                        title: `<p style="color: white; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        timer: 2000
                    });

                    setTimeout(function () {
                        location.href = `${url}login`;
                    }, 2000)

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
                    let errorMessage = 'Ocurrió un problema al procesar su solicitud. Por favor, inténtelo de nuevo más tarde.';
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
                                    validator.showErrors({
                                        [campo]: mensaje
                                    });
                                    // input.addClass('error');
                                    // input.after(`<label id="${campo}-error" class="error" for="${campo}">${mensaje}</label>`);
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
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            iconColor: '#fff',
                            background: '#f00',
                            position: 'top-end',
                            title: `<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Error al procesar la solicitud.</p>`,
                            confirmButtonColor: "#343a40"
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            iconColor: '#fff',
                            background: '#f00',
                            position: 'top-end',
                            title: `<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${errorMessage}</p>`,
                            confirmButtonColor: "#343a40"
                        });
                    }
                }

            });

            return false; // Evitar que el formulario se envíe dos veces
        }
    });




    // Manejar el envío del formulario
    // $('#form-reset').submit(function(ev) {
    //     ev.preventDefault(); // Evitar el envío por defecto del formulario

    //     // Realizar la solicitud AJAX
    //     $.ajax({
    //         url: url + 'login/password?hash=' + $('#hash').val(),
    //         type: 'POST',
    //         data: $(this).serialize(),
    //         success: function(response) {

    //             switch(response) {

    //                 case 0:
    //                     Swal.fire({
    //                         toast: true,
    //                         icon: 'error',
    //                         iconColor: '#fff',
    //                         background: '#f00',
    //                         title: '<p style="color: #fff; font-size: 1.18em;">Contraseña y confirmar contraseña son requeridos...</p>',
    //                         confirmButtonColor: "#343a40"
    //                     });
    //                     return; // Salir de la función para evitar ejecutar el resto del código

    //                     case 1:
    //                         Swal.fire({
    //                             toast: true,
    //                             icon: 'error',
    //                             iconColor: '#fff',
    //                             background: '#f00',
    //                             title: '<p style="color: #fff; font-size: 1.18em;">Las contraseñas no coinciden...</p>',
    //                             confirmButtonColor: "#343a40"
    //                         });
    //                         return;

    //                     case 2:
    //                         Swal.fire({
    //                             title: 'Actualizando Contraseña...',
    //                             text: 'Por favor, espere...',
    //                             timer: 3000,
    //                             allowEscapeKey: false,
    //                             allowOutsideClick: false,
    //                             didOpen: () => {
    //                                 Swal.showLoading();
    //                             }
    //                         });

    //                         setTimeout(function() {
    //                             Swal.fire({
    //                                 icon: 'success',
    //                                 iconColor: 'white',
    //                                 background: 'dodgerblue',
    //                                 title: '<p style="color: white; font-size: 1.18em;">Contraseña actualizada!</p>',
    //                                 showConfirmButton: false,
    //                                 timerProgressBar: true,
    //                                 allowEscapeKey: false,
    //                                 allowOutsideClick: false,
    //                                 timer: 2000
    //                             });

    //                             setTimeout(function() {
    //                                 location.href = url + 'login';
    //                             }, 2000)

    //                         }, 3000);
    //                         return;
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             // Manejar errores de la solicitud AJAX
    //             console.error('Error en la solicitud AJAX:', status, error);
    //             Swal.fire({
    //                 icon: 'error',
    //                 iconColor: '#fff',
    //                 background: '#f00',
    //                 title: '<p style="color: #fff; font-size: 1.18em;">Error al procesar la solicitud...</p>',
    //                 confirmButtonColor: "#343a40"
    //             });
    //         }

    //     });

    // });

});