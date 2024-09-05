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

// $(document).ready(function() {
//     // $('.input').val('');
//     $('#Login').submit(function(e) {
//         e.preventDefault(); // Evitar el envío del formulario

//         $.ajax({
//             url: url + 'login/verifica',
//             type: 'POST',
//             data: $(this).serialize(),
//             success: function(response) {
//                 if (response.status === 0) {
//                     alert('Por favor, completa todos los campos.');
//                 } else if (response.status === 1) {
//                     alert('Usuario o contraseña incorrectos.');
//                 } else if (response.status === 2) {
//                     // window.location.href = response.url; // Redirigir a la URL devuelta
//                     location.reload(); //devuelve una url con json  
//                 }
//             },
//             error: function(xhr, status, error) {
//                 console.error('Error:', xhr, status, error);
//             }
//         });
//     });
// });


$(document).ready(function() {
    $('.input').val('');
    $("#Login").submit(function(e) { //id de formulario 
        $.ajax({
            url: url + 'login/verifica',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // console.log(response);
                if (response.status == 0) {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        iconColor: '#fff',
                        background: '#f00',
                        title: '<p style="color: #fff; font-size: 1.18em;">' + response.message + '</p>',
                        confirmButtonColor: "#343a40"
                    });
                } else if (response.status == 1) {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        iconColor: '#fff',
                        background: '#f00',
                        title: '<p style="color: #fff; font-size: 1.18em;">' + response.message + '</p>',
                        confirmButtonColor: "#343a40"
                    });
                } else {
                    location.reload(); //devuelve una url con json  
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
            }
        });
        e.preventDefault();
    });
});