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
*!*     VALIDAR COMPARAR CONTRASEÑAS:
********************************************************************************************************************************************************/
jQuery.validator.addMethod('equalPassword', function(value, element, param) {
    return value === $(param).val();
}, 'Las contrase\u00f1as no coinciden.');

$(document).ready(function() {
    // Limpiar todos los campos de entrada
    $('.input').val('');

    // Configuración de la validación del formulario
    $('#form-reset').validate({
        rules: {
            password: { required: true },
            re_password: { required: true, equalPassword: password },
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
        submitHandler: function(form, ev) {

            ev.preventDefault(); // Evitar la acción predeterminada del formulario
            $.ajax({
                url: `${url}login/password`,
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {

                    switch(response) {

                        case 0:
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                iconColor: '#fff',
                                background: '#f00',
                                title: '<p style="color: #fff; font-size: 1.18em;">Contraseña y confirmar contraseña son requeridos...</p>',
                                confirmButtonColor: "#343a40"
                            });
                            return; // Salir de la función para evitar ejecutar el resto del código
                        
                            case 1:
                                Swal.fire({
                                    toast: true,
                                    icon: 'error',
                                    iconColor: '#fff',
                                    background: '#f00',
                                    title: '<p style="color: #fff; font-size: 1.18em;">Las contraseñas no coinciden...</p>',
                                    confirmButtonColor: "#343a40"
                                });
                                return;
                            
                            case 2:
                                Swal.fire({
                                    title: 'Actualizando Contraseña...',
                                    text: 'Por favor, espere...',
                                    timer: 3000,
                                    allowEscapeKey: false,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
            
                                setTimeout(function() {
                                    Swal.fire({
                                        icon: 'success',
                                        iconColor: 'white',
                                        background: 'dodgerblue',
                                        title: '<p style="color: white; font-size: 1.18em;">Contraseña actualizada!</p>',
                                        showConfirmButton: false,
                                        timerProgressBar: true,
                                        allowEscapeKey: false,
                                        allowOutsideClick: false,
                                        timer: 2000
                                    });
            
                                    setTimeout(function() {
                                        location.href = url + 'login';
                                    }, 2000)
                                    
                                }, 3000);
                                return;
                    }
                },
                error: function(xhr, status, error) { 
                    // Manejar errores de la solicitud AJAX
                    console.error('Error en la solicitud AJAX:', status, error);
                    Swal.fire({
                        icon: 'error',
                        iconColor: '#fff',
                        background: '#f00',
                        title: '<p style="color: #fff; font-size: 1.18em;">Error al procesar la solicitud...</p>',
                        confirmButtonColor: "#343a40"
                    });
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