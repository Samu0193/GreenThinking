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

$(document).ready(function() {
    // Limpiar todos los campos de entrada
    $('.input').val('');

    // Manejar el envío del formulario
    $("#resetForgot").submit(function(ev) {
        ev.preventDefault(); // Evitar el envío por defecto del formulario

        // Realizar la solicitud AJAX
        $.ajax({
            url: url + 'login/password?hash=' + $('#hash').val(),
            type: 'POST',
            data: $(this).serialize(),
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

    });

});