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

// CORREO
jQuery.validator.addMethod("correo", function (value, element) {
    return (
        this.optional(element) ||
        /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value)
    );
}, `Ingrese un email v\u00e1lido.`);

// $('#email').change(function() {
//     if (!/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test($(this).val())) {
//         Swal.fire({
//             toast: true,
//             icon: 'error',
//             iconColor: '#fff',
//             background: '#f00',
//             title: '<p style="color: #fff; font-size: 1.12em;">Ingrese un correo v\u00e1lido.</p>',
//             confirmButtonColor: "#343a40"
//         });
//         $(this).val('');
//     }
// });

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

$(function () {
    $("#form-forgot").validate({
        rules: {
            email: { required: true, correo: true }
        },
        messages: {
            email: { required: 'Email requerido.' }
        },
        invalidHandler: function (error, element) {
            console.log(error, element);
            modalErrorMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">Formulario inv\u00e1lido!</p>`);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            Swal.fire({
                title: 'Enviando correo de recuperación...',
                text: 'Por favor, espere...',
                // timer: 5000,
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: url + 'login/forgotPassword',
                type: 'POST',
                data: $(this).serialize(),
                success: function(jsonResponse) {

                    // Para asegurar que el valor se borre antes de la redirección
                    $('#email').val('');

                    // Redirigir después de un breve retraso para asegurarse de que el usuario vea el mensaje
                    location.href = url + 'login';

                    // setTimeout(function() {
                    //     location.href = url + 'login';
                    // }, 5000);
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
            return false;
        }
    });
});