/********************************************************************************************************************************************************
*!*     MENSAJES:
********************************************************************************************************************************************************/
function toastSuccesMessage(mensaje) {
    Swal.fire({
        toast: true,
        icon: 'success',
        iconColor: 'white',
        position: 'top-end',
        background: '#3ca230',
        title: `${mensaje}`,
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2500
    });
    // POR EL PDF
    // Swal.fire({
    //     toast: true,
    //     icon: 'success',
    //     iconColor: 'white',
    //     background: 'dodgerblue',
    //     position: 'top-end',
    //     title: `${mensaje}`,
    //     showConfirmButton: false,
    //     timerProgressBar: true,
    //     allowEscapeKey: false,
    //     allowOutsideClick: false,
    //     timer: 5000
    // });
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

/****************************************************************************
                                NAVEGACION
****************************************************************************/

if (navigator.appVersion.indexOf("Chrome/") != -1) {
    $(".navbar ul li a").css("font-weight", "600");
}

let navbar = $("#navbar");
let logo = $("#logo");
let linksNavbar = $(".navbar ul li a");
let menu = $("#menu-mobile");
let hamburguesa = $(".hamburger");
let sidebar = $("#sidebar");
let linksSidebar = $(".sidebar ul li a");
let bgSidebar = $("#bg-sidebar");

$(window).resize(function () {
    if ($(window).width() < 1200 && sidebar.hasClass("sidebar-active")) {
        navbar.addClass("bg-white");
        logo.attr("src", url + "assets/img/logoletranegra.png");
        hamburguesa.addClass("bg-black");
    } else if (
        sidebar.hasClass("sidebar-active") &&
        $(window).scrollTop() < 250
    ) {
        navbar.removeClass("bg-white");
        logo.attr("src", url + "assets/img/logoletrablanca.png");
        hamburguesa.removeClass("bg-black");
    }
});

menu.click(function () {
    hamburguesa.eq(0).toggleClass("uno");
    hamburguesa.eq(1).toggleClass("dos");
    hamburguesa.eq(2).toggleClass("tres");
    sidebar.toggleClass("sidebar-active");
    bgSidebar.toggleClass("bg-sidebar-active");
    if (sidebar.hasClass("sidebar-active")) {
        gsap.from(".sidebar ul li a", {
            duration: 0.2,
            x: "100%",
            stagger: 0.08,
        });
    } else {
        gsap.from(".sidebar ul li a", {
            duration: 0,
            x: "0%",
        });
    }
    if ($(window).scrollTop() < 250) {
        navbar.toggleClass("bg-white");
        hamburguesa.toggleClass("bg-black");
        if (sidebar.hasClass("sidebar-active")) {
            logo.attr("src", "assets/img/logoletranegra.png");
        } else {
            logo.attr("src", "assets/img/logoletrablanca.png");
        }
    }
});

linksSidebar.click(function () {
    hamburguesa.eq(0).toggleClass("uno");
    hamburguesa.eq(1).toggleClass("dos");
    hamburguesa.eq(2).toggleClass("tres");
    sidebar.toggleClass("sidebar-active");
    bgSidebar.toggleClass("bg-sidebar-active");
    if ($(window).scrollTop() < 250) {
        navbar.toggleClass("bg-white");
        hamburguesa.toggleClass("bg-black");
        if (sidebar.hasClass("sidebar-active")) {
            logo.attr("src", url + "assets/img/logoletranegra.png");
        } else {
            logo.attr("src", url + "assets/img/logoletrablanca.png");
        }
    }
});

$(".user-info").click(function () {
    $(".user-info p").toggleClass("dos");
});

/****************************************************************************
                        VALIDAR CAMPOS PARA INSERTAR
****************************************************************************/
// MASCARAS DE CAMPOS
$("[name='DUI']").mask("99999999-9");
$("[name='DUI_ref']").mask("99999999-9");
$("[name='telefono']").mask("9999-9999");
$("[name='telefono_ref']").mask("9999-9999");
$("[name='telefono_menor']").mask("9999-9999");

// VALIDAR DUI
function valDui(input) {
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarDUI`,
        data: { 'DUI': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em;">${jsonResponse.message}</p>`);
                // input.value = '';
            }
        },
    });
}

// VALIDAR TELEFONO
function valTel(input) {
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarTel`,        
        data: { 'telefono': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em;">${jsonResponse.message}</p>`);
                // input.value = '';
            }
        },
    });
}

// VALIDAR CORREO
function valEmail(input) {
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarEmail`,
        data: { 'email': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                toastInfoMessage(`<p style="color: #fff; font-size: 1.18em;">${jsonResponse.message}</p>`);
                input.value = '';
            }
        },
    });
}

// VALIDAR CORREO
function valEmailUser(input) {
    $.ajax({
        type: 'POST',
        url: `${url}usuario/validarEmail`,
        data: { 'email': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em;">${jsonResponse.message}</p>`);
                // input.value = '';
            }
        },
    });
}

// VALIDAR USUARIO
$("[name='nombre_usuario']").change(function () {
    $.ajax({
        type: 'POST',
        url: `${url}usuario/validarUser`,
        data: { 'nombre_usuario': $(this).val() },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em;">${jsonResponse.message}</p>`);
                // $("[name='nombre_usuario']").val('');
            }
        },
    });
});

// FECHA DE EDAD MAYORES
function f_MinEdadMayor(value) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() - 40)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

function f_MaxEdadMayor(value) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() - 18)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

// FECHA DE EDAD MENORES
function f_MinEdadMenor(value) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() - 17)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

function f_MaxEdadMenor(value) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() - 12)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

// FECHA DE EDAD RESPONSABLE
function f_MinEdadResponsable(value) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() - 70)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

function f_MaxEdadResponsable(value) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() - 20)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

// FECHA FINALIZACION DE VOLUNTARIADO
function f_MinFin(value) {
    var now = new Date(),
        fecha = new Date(now.setMonth(now.getMonth() + 3)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

function f_MaxFin(value) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() + 1)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = "0" + mes.toString();
    if (dia < 10) dia = "0" + dia.toString();

    if (value == 1) {
        return anio + "-" + mes + "-" + dia;
    } else {
        return dia + "-" + mes + "-" + anio;
    }
}

$("#f_nacimiento_mayor").prop("min", f_MinEdadMayor(1));
$("#f_nacimiento_mayor").prop("max", f_MaxEdadMayor(1));

$("#f_nacimiento_ref").prop("min", f_MinEdadResponsable(1));
$("#f_nacimiento_ref").prop("max", f_MaxEdadResponsable(1));

$("[name='f_nacimiento_menor'1515-1155]").prop("min", f_MinEdadMenor(1));
$("[name='f_nacimiento_menor']").prop("max", f_MaxEdadMenor(1));

$("[name='fecha_finalizacion']").prop("min", f_MinFin(1));
$("[name='fecha_finalizacion']").prop("max", f_MaxFin(1));

jQuery.validator.addMethod("minEdadMay", function (value, element) {
    return this.optional(element) || value >= f_MinEdadMayor(1);
}, `Edad m\u00e1xima 40 a\u00f1os.`);

jQuery.validator.addMethod("maxEdadMay", function (value, element) {
    return this.optional(element) || value <= f_MaxEdadMayor(1);
}, `Edad m\u00ednima 18 a\u00f1os.`);

jQuery.validator.addMethod("minEdadRes", function (value, element) {
    return this.optional(element) || value >= f_MinEdadResponsable(1);
}, `Edad m\u00e1xima 70 a\u00f1os.`);

jQuery.validator.addMethod("maxEdadRes", function (value, element) {
    return this.optional(element) || value <= f_MaxEdadResponsable(1);
}, `Edad m\u00ednima 20 a\u00f1os.`);

jQuery.validator.addMethod("minEdadMen", function (value, element) {
    return this.optional(element) || value >= f_MinEdadMenor(1);
}, `Edad m\u00e1xima 17 a\u00f1os.`);

jQuery.validator.addMethod("maxEdadMen", function (value, element) {
    return this.optional(element) || value <= f_MaxEdadMenor(1);
}, `Edad m\u00ednima 12 a\u00f1os.`);

jQuery.validator.addMethod("minFin", function (value, element) {
    return this.optional(element) || value >= f_MinFin(1);
}, `Debe ser mayor o igual a: ${f_MinFin(0)}`);

jQuery.validator.addMethod("maxFin", function (value, element) {
    return this.optional(element) || value <= f_MaxFin(1);
}, `Debe ser menor o igual a: ${f_MaxFin(0)}`);

jQuery.validator.addMethod("decimal", function (value, element) {
    return this.optional(element) || /^\d{1,2}(\.\d{1,2})?$/i.test(value);
}, `Precio inv\u00e1lido.`);

// LETRAS Y ESPACIOS
jQuery.validator.addMethod("alfaOespacio", function (value, element) {
    return this.optional(element) || /^[ a-záéíóúüñ]*$/i.test(value);
}, `S\u00f3lo letras o espacios.`);

// CORREO
jQuery.validator.addMethod("correo", function (value, element) {
    return (
        this.optional(element) ||
        /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value)
    );
}, `Ingrese un email v\u00e1lido.`);

// DUI
jQuery.validator.addMethod("isDUI", function (value) {
    var regex = /(^\d{8})-(\d$)/,
        parts = value.match(regex);
    if (parts !== null) {
        var digits = parts[1],
            dig_ve = parseInt(parts[2], 10),
            sum = 0;
        for (var i = 0, l = digits.length; i < l; i++) {
            var d = parseInt(digits[i], 10);
            sum += (9 - i) * d;
        }
        return dig_ve === (10 - (sum % 10)) % 10;
    } else {
        return false;
    }
}, `DUI inv\u00e1lido`);

// jQuery.validator.addMethod("isDUI", function (value) {
//     // Verificar si el valor es '00000000-0'
//     if (value === '00000000-0') {
//         return false; // Invalida si es '00000000-0'
//     }

//     // Verificar el formato del DUI
//     var regex = /(^\d{8})-(\d$)/,
//         parts = value.match(regex);
//     if (parts !== null) {
//         var digits = parts[1],  // Los primeros 8 dígitos
//             dig_ve = parseInt(parts[2], 10), // El dígito verificador
//             sum = 0;

//         // Calcular el dígito verificador
//         for (var i = 0, l = digits.length; i < l; i++) {
//             var d = parseInt(digits[i], 10);
//             sum += (9 - i) * d;
//         }

//         // Verificar si el dígito verificador es correcto
//         return dig_ve === (10 - (sum % 10)) % 10;
//     } else {
//         return false; // Formato inválido
//     }
// }, "DUI inválido");

function valSize(input) {
    const fileSize = input.files[0].size / 1024 / 1024; // in MiB
    if (fileSize < 5) {
        return true;
    } else {
        return false;
    }
}

function valDimensions(imagen) {
    var _URL = window.URL || window.webkitURL;
    var img = new Image();
    img.src = _URL.createObjectURL(imagen);
    img.onload = function () {
        var ancho = img.width;
        var alto = img.height;
        console.log(ancho + " " + alto);
        if (ancho >= 1000 && alto >= 900) {
            document.getElementById("upload").value = '';
            document.getElementById("nombre_imagen").value = '';
            toastInfoMessage(`<p style="color: #fff; font-size: 1.18em;">Tama\u00f1o m\u00e1ximo 2000x2000 elija una imagen adecuada...</p>`);
        }
    };
}
