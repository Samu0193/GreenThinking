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
function valDui(input) {
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarDUI`,
        data: { 'dui': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                // input.value = '';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) {
                errorMessage = jsonResponse.message;
            }
            console.log(errorMessage);
        }
    });
}

/********************************************************************************************************************************************************
*!*     VALIDAR TELEFONO EXISTENTE:
********************************************************************************************************************************************************/
function valTel(input) {
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarTel`,        
        data: { 'telefono': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                // input.value = '';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) {
                errorMessage = jsonResponse.message;
            }
            console.log(errorMessage);
        }
    });
}

/********************************************************************************************************************************************************
*!*     VALIDAR CORREO EXISTENTE (VOLUNTARIO):
********************************************************************************************************************************************************/
function valEmail(input) {
    $.ajax({
        type: 'POST',
        url: `${url}inicio/validarEmail`,
        data: { 'email': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                // input.value = '';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) {
                errorMessage = jsonResponse.message;
            }
            console.log(errorMessage);
        }
    });
}

/********************************************************************************************************************************************************
*!*     VALIDAR CORREO EXISTENTE (USUARIO):
********************************************************************************************************************************************************/
function valEmailUser(input) {
    $.ajax({
        type: 'POST',
        url: `${url}usuario/validarEmail`,
        data: { 'email': input.value },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                // input.value = '';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) {
                errorMessage = jsonResponse.message;
            }
            console.log(errorMessage);
        }
    });
}

/********************************************************************************************************************************************************
*!*     VALIDAR USUARIO EXISTENTE:
********************************************************************************************************************************************************/
$('[name="nombre_usuario"]').change(function () {
    $.ajax({
        type: 'POST',
        url: `${url}usuario/validarUser`,
        data: { 'nombre_usuario': $(this).val() },
        success: function (jsonResponse) {
            if (jsonResponse.data === false) {
                // toastInfoMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                // $('[name="nombre_usuario"]').val('');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            let errorMessage = errorMsgEstandar;
            let jsonResponse = jqXHR.responseJSON;
            if (jsonResponse) {
                errorMessage = jsonResponse.message;
                if (jsonResponse.code === 400) {
                    toastInfoMessage(`<p style="color: #fff; font-size: 1.18em; font-weight: 100;">${jsonResponse.message}</p>`);
                    // $(this).val('');
                }
            }
            console.log(errorMessage);
        }
    });
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
jQuery.validator.addMethod('minEdadMay', function (value, element) {
    return this.optional(element) || value >= f_MinEdadMayor(1);
}, `Edad m\u00e1xima 40 a\u00f1os`);

jQuery.validator.addMethod('maxEdadMay', function (value, element) {
    return this.optional(element) || value <= f_MaxEdadMayor(1);
}, `Edad m\u00ednima 18 a\u00f1os`);

jQuery.validator.addMethod('minEdadRes', function (value, element) {
    return this.optional(element) || value >= f_MinEdadResponsable(1);
}, `Edad m\u00e1xima 70 a\u00f1os`);

jQuery.validator.addMethod('maxEdadRes', function (value, element) {
    return this.optional(element) || value <= f_MaxEdadResponsable(1);
}, `Edad m\u00ednima 20 a\u00f1os`);

jQuery.validator.addMethod('minEdadMen', function (value, element) {
    return this.optional(element) || value >= f_MinEdadMenor(1);
}, `Edad m\u00e1xima 17 a\u00f1os`);

jQuery.validator.addMethod('maxEdadMen', function (value, element) {
    return this.optional(element) || value <= f_MaxEdadMenor(1);
}, `Edad m\u00ednima 12 a\u00f1os`);

jQuery.validator.addMethod('minFin', function (value, element) {
    return this.optional(element) || value >= f_MinFin(1);
}, `Debe ser mayor o igual a: <br>${f_MinFin(0)}`);

jQuery.validator.addMethod('maxFin', function (value, element) {
    return this.optional(element) || value <= f_MaxFin(1);
}, `Debe ser menor o igual a: <br>${f_MaxFin(0)}`);

jQuery.validator.addMethod('decimal', function (value, element) {
    return this.optional(element) || /^\d{1,2}(\.\d{1,2})?$/i.test(value);
}, `Precio debe ser un número con hasta dos decimales`);

/********************************************************************************************************************************************************
*!*     VALIDAR LETRAS Y ESPACIOS:
********************************************************************************************************************************************************/
jQuery.validator.addMethod('alfaOespacio', function (value, element) {
    return this.optional(element) || /^[ a-záéíóúüñ]*$/i.test(value);
}, `S\u00f3lo letras o espacios`);

/********************************************************************************************************************************************************
*!*     VALIDAR FORMATO CORREO:
********************************************************************************************************************************************************/
jQuery.validator.addMethod('correo', function (value, element) {
    return (
        this.optional(element) ||
        /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value)
    );
}, `Ingrese un email v\u00e1lido`);

/********************************************************************************************************************************************************
*!*     VALIDAR FORMATO DUI:
********************************************************************************************************************************************************/
jQuery.validator.addMethod('isDUI', function (value) {
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

// jQuery.validator.addMethod('isDUI', function (value) {
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
jQuery.validator.addMethod('equalPassword', function (value, element, param) {
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
