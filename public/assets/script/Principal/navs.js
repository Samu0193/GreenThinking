/********************************************************************************************************************************************************
*!*     CONFIGURAR TOKEN:
********************************************************************************************************************************************************/
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN-GREEN-THINKING': $('meta[name="csrf-token"]').attr('content') // Obtener el token desde la meta etiqueta
    }
});

function updateCsrfToken(token) {
    $('meta[name="csrf-token"]').attr('content', token);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN-GREEN-THINKING': token
        }
    });
}

/********************************************************************************************************************************************************
*!*     MENSAJES:
********************************************************************************************************************************************************/
let errorMsgEstandar = 'Ocurrió un problema al procesar su solicitud. Por favor, inténtelo de nuevo más tarde';

function modalSuccessChargeMessage(titulo, mensaje) {
    Swal.fire({
        // toast: true,
        // position: 'top-end',
        background: '#3ca230',
        title: `<p style="color: white;">${titulo}</p>`,
        text: mensaje,
        allowEscapeKey: false,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    $('.swal2-html-container').css('color', '#fff');
    $('.swal2-loader').css('border-color', '#fff transparent #fff transparent');
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
        timer: 4000
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
        confirmButtonColor: '#343a40'
    });
}

function modalErrorMessage(mensaje) {
    Swal.fire({
        icon: 'error',
        iconColor: '#fff',
        background: '#f00',
        title: `${mensaje}`,
        confirmButtonColor: '#343a40'
    });
}

function toastInfoMessage(mensaje) {
    Swal.fire({
        toast: true,
        icon: 'info',
        background: '#f00',
        iconColor: 'white',
        title: `${mensaje}`,
        confirmButtonColor: '#343a40'
    });
}

/********************************************************************************************************************************************************
*!*     NAVEGACION:
********************************************************************************************************************************************************/
if (navigator.appVersion.indexOf('Chrome/') != -1) {
    $('.navbar ul li a').css('font-weight', '600');
}

let logo         = $('#logo');
let navbar       = $('#navbar');
let sidebar      = $('#sidebar');
let hamburguesa  = $('.hamburger');
let bgSidebar    = $('#bg-sidebar');
let menu         = $('#menu-mobile');
let linksNavbar  = $('.navbar ul li a');
let linksSidebar = $('.sidebar ul li a');

$(window).resize(function () {
    if ($(window).width() < 1200 && sidebar.hasClass('sidebar-active')) {
        navbar.addClass('bg-white');
        logo.attr('src', `${url}assets/img/logoletranegra.png`);
        hamburguesa.addClass('bg-black');
    } else if (sidebar.hasClass('sidebar-active') && $(window).scrollTop() < 250) {
        navbar.removeClass('bg-white');
        logo.attr('src', `${url}assets/img/logoletrablanca.png`);
        hamburguesa.removeClass('bg-black');
    }
});

menu.click(function () {
    hamburguesa.eq(0).toggleClass('uno');
    hamburguesa.eq(1).toggleClass('dos');
    hamburguesa.eq(2).toggleClass('tres');
    sidebar.toggleClass('sidebar-active');
    bgSidebar.toggleClass('bg-sidebar-active');
    if (sidebar.hasClass('sidebar-active')) {
        gsap.from('.sidebar ul li a', {
            duration: 0.2,
            x: '100%',
            stagger: 0.08,
        });
    } else {
        gsap.from('.sidebar ul li a', {
            duration: 0,
            x: '0%',
        });
    }
    if ($(window).scrollTop() < 250) {
        navbar.toggleClass('bg-white');
        hamburguesa.toggleClass('bg-black');
        if (sidebar.hasClass('sidebar-active')) {
            logo.attr('src', `${url}assets/img/logoletranegra.png`);
        } else {
            logo.attr('src', `${url}assets/img/logoletrablanca.png`);
        }
    }
});

linksSidebar.click(function () {
    hamburguesa.eq(0).toggleClass('uno');
    hamburguesa.eq(1).toggleClass('dos');
    hamburguesa.eq(2).toggleClass('tres');
    sidebar.toggleClass('sidebar-active');
    bgSidebar.toggleClass('bg-sidebar-active');
    if ($(window).scrollTop() < 250) {
        navbar.toggleClass('bg-white');
        hamburguesa.toggleClass('bg-black');
        if (sidebar.hasClass('sidebar-active')) {
            logo.attr('src', `${url}assets/img/logoletranegra.png`);
        } else {
            logo.attr('src', `${url}assets/img/logoletrablanca.png`);
        }
    }
});

$('.user-info').click(function () {
    $('.user-info p').toggleClass('dos');
});


/********************************************************************************************************************************************************
*!*     MASCARAS DE CAMPOS:
********************************************************************************************************************************************************/
$('[name="dui"]').mask('99999999-9');
$('[name="dui_ref"]').mask('99999999-9');
$('[name="telefono"]').mask('9999-9999');
$('[name="telefono_ref"]').mask('9999-9999');
$('[name="telefono_menor"]').mask('9999-9999');

/********************************************************************************************************************************************************
*!*     VALIDAR DUI EXISTENTE:
********************************************************************************************************************************************************/
let msgResponse = '';
$.validator.addMethod("inDUI", function(value) {
    let response = false;
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarDUI`,
        data: { 'dui': value },
        async: false,
        success: function(jsonResponse) {
            response    = jsonResponse.data;
            msgResponse = !response ? jsonResponse.message : '';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) errorMessage = jsonResponse.message;
            msgResponse = 'Hubo un error al validar este campo';
            console.log(errorMessage);
        }
    });
    return response;
}, function() {
    return msgResponse; // Esta función devuelve el valor actualizado de msgResponse
});

/********************************************************************************************************************************************************
*!*     VALIDAR CORREO EXISTENTE (VOLUNTARIO):
********************************************************************************************************************************************************/
msgResponse = '';
$.validator.addMethod("inEmailVoluntario", function(value) {
    let response = false;
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarEmail`,
        data: { 'email': value },
        async: false,
        success: function(jsonResponse) {
            response    = jsonResponse.data;
            msgResponse = !response ? jsonResponse.message : '';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) errorMessage = jsonResponse.message;
            msgResponse = 'Hubo un error al validar este campo';
            console.log(errorMessage);
        }
    });
    return response;
}, function() {
    return msgResponse;
});

/********************************************************************************************************************************************************
*!*     VALIDAR COMPARAR TELEFONOS:
********************************************************************************************************************************************************/
msgResponse = '';
$.validator.addMethod('distinctTelefono', function (value, element, param) {
    msgResponse = 'Los telefonos no pueden ser iguales';
    return $(param).val() !== '' ? value !== $(param).val() : true;
}, function() {
    return msgResponse;
});

/********************************************************************************************************************************************************
*!*     VALIDAR TELEFONO EXISTENTE:
********************************************************************************************************************************************************/
msgResponse = '';
$.validator.addMethod("inTelefono", function(value) {
    let response = false;
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarTel`,
        data: { 'telefono': value },
        async: false,
        success: function(jsonResponse) {
            response    = jsonResponse.data;
            msgResponse = !response ? jsonResponse.message : '';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) errorMessage = jsonResponse.message;
            msgResponse = 'Hubo un error al validar este campo';
            console.log(errorMessage);
        }
    });
    return response;
}, function() {
    return msgResponse;
});

/********************************************************************************************************************************************************
*!*     VALIDAR CORREO EXISTENTE (USUARIO):
********************************************************************************************************************************************************/
msgResponse = '';
$.validator.addMethod("inEmailUsuario", function(value) {
    let response = false;
    $.ajax({
        type: 'POST',
        url: `${url}usuario/validarEmail`,
        data: { 'email': value },
        async: false,
        success: function(jsonResponse) {
            response    = jsonResponse.data;
            msgResponse = !response ? jsonResponse.message : '';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) errorMessage = jsonResponse.message;
            msgResponse = 'Hubo un error al validar este campo';
            console.log(errorMessage);
        }
    });
    return response;
}, function() {
    return msgResponse;
});

/********************************************************************************************************************************************************
*!*     VALIDAR USUARIO EXISTENTE:
********************************************************************************************************************************************************/
msgResponse = '';
$.validator.addMethod("inUsuario", function(value) {
    let response = false;
    $.ajax({
        type: 'POST',
        url: `${url}usuario/validarUser`,
        data: { 'nombre_usuario': value },
        async: false,
        success: function(jsonResponse) {
            response    = jsonResponse.data;
            msgResponse = !response ? jsonResponse.message : '';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) errorMessage = jsonResponse.message;
            msgResponse = 'Hubo un error al validar este campo';
            console.log(errorMessage);
        }
    });
    return response;
}, function() {
    return msgResponse;
});

/********************************************************************************************************************************************************
*!*     METODOS PARA VALIDACION DE FECHAS:
********************************************************************************************************************************************************/

// CALCULO DE AÑOS
function calculaAnios(anios, formato) {
    var now = new Date(),
        fecha = new Date(now.setFullYear(now.getFullYear() + anios)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = '0' + mes.toString();
    if (dia < 10) dia = '0' + dia.toString();

    if (formato === 1) {
        return anio + '-' + mes + '-' + dia;
    } else {
        return dia + '-' + mes + '-' + anio;
    }
}

// CALCULO DE MESES
function calculaMeses(meses, formato) {
    var now = new Date(),
        fecha = new Date(now.setMonth(now.getMonth() + meses)),
        anio = fecha.getFullYear(),
        mes = fecha.getMonth() + 1,
        dia = fecha.getDate();

    if (mes < 10) mes = '0' + mes.toString();
    if (dia < 10) dia = '0' + dia.toString();

    if (formato === 1) {
        return anio + '-' + mes + '-' + dia;
    } else {
        return dia + '-' + mes + '-' + anio;
    }
}

// FECHA DE EDAD MAYORES
function f_MinEdadMayor(formato) {
    return calculaAnios(-40, formato);
}

function f_MaxEdadMayor(formato) {
    return calculaAnios(-18, formato);
}

// FECHA DE EDAD MENORES
function f_MinEdadMenor(formato) {
    return calculaAnios(-17, formato);
}

function f_MaxEdadMenor(formato) {
    return calculaAnios(-12, formato);
}

// FECHA DE EDAD RESPONSABLE
function f_MinEdadResponsable(formato) {
    return calculaAnios(-70, formato);
}

function f_MaxEdadResponsable(formato) {
    return calculaAnios(-20, formato);
}

// FECHA FINALIZACION DE VOLUNTARIADO
function f_MinFin(formato) {
    return calculaMeses(+3, formato);
}

function f_MaxFin(formato) {
    return calculaAnios(+1, formato);
}

$('#f_nacimiento_mayor').prop('min', f_MinEdadMayor(1));
$('#f_nacimiento_mayor').prop('max', f_MaxEdadMayor(1));

$('#f_nacimiento_ref').prop('min', f_MinEdadResponsable(1));
$('#f_nacimiento_ref').prop('max', f_MaxEdadResponsable(1));

$('[name="f_nacimiento_menor"]').prop('min', f_MinEdadMenor(1));
$('[name="f_nacimiento_menor"]').prop('max', f_MaxEdadMenor(1));

$('[name="fecha_finalizacion"]').prop('min', f_MinFin(1));
$('[name="fecha_finalizacion"]').prop('max', f_MaxFin(1));

/********************************************************************************************************************************************************
*!*     VALIDAR FECHAS PARA MAYORES Y MENORES (CÁLCULO DE EDAD):
********************************************************************************************************************************************************/
$.validator.addMethod('minEdadMay', function (value, element) {
    return this.optional(element) || value >= f_MinEdadMayor(1);
}, `Edad m\u00e1xima 40 a\u00f1os`);

$.validator.addMethod('maxEdadMay', function (value, element) {
    return this.optional(element) || value <= f_MaxEdadMayor(1);
}, `Edad m\u00ednima 18 a\u00f1os`);

$.validator.addMethod('minEdadRes', function (value, element) {
    return this.optional(element) || value >= f_MinEdadResponsable(1);
}, `Edad m\u00e1xima 70 a\u00f1os`);

$.validator.addMethod('maxEdadRes', function (value, element) {
    return this.optional(element) || value <= f_MaxEdadResponsable(1);
}, `Edad m\u00ednima 20 a\u00f1os`);

$.validator.addMethod('minEdadMen', function (value, element) {
    return this.optional(element) || value >= f_MinEdadMenor(1);
}, `Edad m\u00e1xima 17 a\u00f1os`);

$.validator.addMethod('maxEdadMen', function (value, element) {
    return this.optional(element) || value <= f_MaxEdadMenor(1);
}, `Edad m\u00ednima 12 a\u00f1os`);

$.validator.addMethod('minFin', function (value, element) {
    return this.optional(element) || value >= f_MinFin(1);
}, `Debe ser mayor o igual a: <br>${f_MinFin(0)}`);

$.validator.addMethod('maxFin', function (value, element) {
    return this.optional(element) || value <= f_MaxFin(1);
}, `Debe ser menor o igual a: <br>${f_MaxFin(0)}`);

$.validator.addMethod('decimal', function (value, element) {
    return this.optional(element) || /^\d{1,2}(\.\d{1,2})?$/i.test(value);
}, `Precio debe ser un número con hasta dos decimales`);

/********************************************************************************************************************************************************
*!*     VALIDAR LETRAS Y ESPACIOS:
********************************************************************************************************************************************************/
$.validator.addMethod('alfaOespacio', function (value, element) {
    return this.optional(element) || /^[ a-záéíóúüñ]*$/i.test(value);
}, `S\u00f3lo letras o espacios`);

/********************************************************************************************************************************************************
*!*     VALIDAR FORMATO CORREO:
********************************************************************************************************************************************************/
$.validator.addMethod('correo', function (value, element) {
    return (
        this.optional(element) ||
        /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value)
    );
}, `Ingrese un email v\u00e1lido`);

/********************************************************************************************************************************************************
*!*     VALIDAR FORMATO DUI:
********************************************************************************************************************************************************/
$.validator.addMethod('isDUI', function (value) {
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

// $.validator.addMethod('isDUI', function (value) {
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
// }, 'DUI inválido');

/********************************************************************************************************************************************************
*!*     VALIDAR COMPARAR CONTRASEÑAS:
********************************************************************************************************************************************************/
$.validator.addMethod('equalPassword', function (value, element, param) {
    return $(param).val() !== '' ? value === $(param).val() : true;
}, 'Las contrase\u00f1as no coinciden');

/********************************************************************************************************************************************************
*!*     VALIDAR TIPOS DE ARCHIVOS:
********************************************************************************************************************************************************/
$.validator.addMethod('fileType', function (value, element, param) {
    // Si no hay archivos seleccionados, devolver true (sin error)
    if (element.files.length === 0) return true;

    const extension = value.split('.').pop().toLowerCase();
    return $.inArray(extension, param) !== -1;
}, 'Solo se permiten archivos JPG, JPEG o PNG');

/********************************************************************************************************************************************************
*!*     VALIDAR TAMAÑO MAXIMO DE ARCHIVOS:
********************************************************************************************************************************************************/
$.validator.addMethod('fileSize', function (value, element, param) {
    if (element.files.length === 0) return true;

    const file = element.files[0];
    return file.size <= param;
}, 'El tamaño máximo permitido es de 5MB');

// Método para validar las dimensiones máximas (2000x2000)
// $.validator.addMethod('imageDimensions', function (value, element, param) {
//     if (element.files.length === 0) return true;

//     const file = element.files[0];
//     const _URL = window.URL || window.webkitURL;
//     const imgFile = new Image();
//     let isValid = false;

//     // Manejo sincrónico de la imagen
//     imgFile.src = _URL.createObjectURL(file);
//     console.log(imgFile);
//     imgFile.onload = function () {
//         const { width: ancho, height: alto } = imgFile;
//         isValid = (ancho <= param.width && alto <= param.height);
//         // console.log(`Imagen: ${ancho} x ${alto}`);
//         console.log(isValid);
//         console.log(`Permitido: ${param.width} x ${param.height}`);
//     };
    
//     console.log(isValid);
//     return isValid; // Retorna si la imagen es válida o no
// }, 'Las dimensiones máximas permitidas son 2000 x 2000 píxeles');
