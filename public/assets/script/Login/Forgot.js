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

$('#email').change(function() {
    if (!/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test($(this).val())) {
        Swal.fire({
            toast: true,
            icon: 'error',
            iconColor: '#fff',
            background: '#f00',
            title: '<p style="color: #fff; font-size: 1.12em;">Ingrese un correo v\u00e1lido.</p>',
            confirmButtonColor: "#343a40"
        });
        $(this).val('');
    }
});

$(document).ready(function() {
    $('.input').val('');
    $("#forgot").submit(function(ev) { //id de formulario 
        $.ajax({
            url: url + 'login/forgotPassword',
            type: 'POST',
            data: $(this).serialize(),
            success: function(jsonResponse) { //
                Swal.fire({
                    title: 'Enviando correo de recuperación...',
                    text: 'Por favor, espere...',
                    timer: 5000,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Para asegurar que el valor se borre antes de la redirección
                $('#email').val('');
                
                // Redirigir después de un breve retraso para asegurarse de que el usuario vea el mensaje
                setTimeout(function() {
                    location.href = url + 'login';
                }, 5000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    // toast: true,
                    icon: 'error',
                    iconColor: '#fff',
                    background: '#f00',
                    title: `<p style="color: #fff; font-size: 1.18em;">${jqXHR.responseJSON.message}</p>`,
                    confirmButtonColor: "#343a40"
                });
            }
        });
        ev.preventDefault();
    });
});