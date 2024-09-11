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
    $('.input').val(''); // PARA QUE LOS INPUTS SIEMPRE ESTEN VACIOS AL MOMENTO DE MOSTRAR LA VISTA
    $("#Login").submit(function(e) { //id de formulario 
        $.ajax({
            url: `${url}login/verifica`,
            type: 'POST',
            data: $(this).serialize(),
            success: function(jsonResponse) {
                location.reload(); //devuelve una url con json
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#fff',
                    background: '#f00',
                    title: `<p style="color: #fff; font-size: 1.18em;">${jqXHR.responseJSON.message}</p>`,
                    confirmButtonColor: "#343a40"
                });
            }
        });
        e.preventDefault();
    });
});