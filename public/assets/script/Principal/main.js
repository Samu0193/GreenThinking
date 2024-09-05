/****************************************************************************
                                NAVEGACION
****************************************************************************/
$(document).ready(function() {
    let arriba = $(window).scrollTop();
    if (arriba >= 250) {
        navbar.addClass('bg-white');
        logo.attr("src", url + "assets/img/logoletranegra.png");
        linksNavbar.css('color', '#000');
        hamburguesa.addClass('bg-black');
    } else {
        navbar.removeClass('bg-white');
        logo.attr("src", url + "assets/img/logoletrablanca.png");
        linksNavbar.css('color', '#fff');
        hamburguesa.removeClass('bg-black');
        if (sidebar.hasClass('sidebar-active') && $(window).width() < 1200) {
            navbar.addClass('bg-white');
            logo.attr("src", url + "assets/img/logoletranegra.png");
            linksNavbar.css('color', '#000');
            hamburguesa.addClass('bg-black');
        }
    }
});

$(window).on('scroll', function() {
    let top = $(window).scrollTop();
    if (top >= 250) {
        navbar.addClass('bg-white');
        logo.attr("src", url + "assets/img/logoletranegra.png");
        linksNavbar.css('color', '#000');
        hamburguesa.addClass('bg-black');
    } else {
        navbar.removeClass('bg-white');
        logo.attr("src", url + "assets/img/logoletrablanca.png");
        linksNavbar.css('color', '#fff');
        hamburguesa.removeClass('bg-black');
        if (sidebar.hasClass('sidebar-active') && $(window).width() < 1200) {
            navbar.addClass('bg-white');
            logo.attr("src", url + "assets/img/logoletranegra.png");
            linksNavbar.css('color', '#000');
            hamburguesa.addClass('bg-black');
        }
    }
});


/****************************************************************************
                                    REGISTRO
****************************************************************************/

// LLENAR SELECT DEPARTAMENTOS
function loadDepartamentos() {
    $.ajax({
        url: url + "inicio/setDepartamentos",
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data) {
            var options = "<option selected disabled value=''>Seleccionar... </option>";
            $.each(data, function(index, object) {
                options += '<option value="' + object.id_departamento + '">' + object.nombre_departamento + '</option>';
            });
            $("[name='departamento_residencia']").html(options);
        }
    });
}

// LLENAR SELECT MUNICIPIOS
jQuery(document).ready(function() {
    $("[name='departamento_residencia']").on('change', function() {
        event.preventDefault();
        var id_departamento = $(this).val();
        if (id_departamento == '') {
            $("[name='municipio_residencia']").prop('disabled', true);
        }
        
        $("[name='municipio_residencia']").prop('disabled', false);
        $.ajax({
            url: url + "inicio/setMunicipios",
            type: 'POST',
            data: { id_departamento: id_departamento },
            success: function(data) {
                $("[name='municipio_residencia']").html(data);
            },
            error: function() {
                alert('error ocurio..!');
            }
        });
    });
});

// Ventana Modal
let modal = $("#modal");
let opciones = $('#modal-options');
let form_mayores = $('#form-mayores');
let form_menores = $('#form-menores');
let title_form = $('#title-form');
form_mayores.hide();
form_menores.hide();
$(".mostrar-modal").on('click', function() {
    loadDepartamentos();
    $("[name='municipio_residencia']").html("<option selected disabled value=''>Seleccionar... </option>");
    modal.fadeIn();
    opciones.fadeIn();
    title_form.html('Únete a nosotros');
});

$("#btn-mayores").on('click', function() {
    form_mayores.fadeIn();
    opciones.hide();
    title_form.html('Voluntario Mayor');
    $('#form-mayores')[0].reset();
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    $('label[class="error"]').css('display', 'none');
});

$("#btn-menores").on('click', function() {
    form_menores.fadeIn();
    opciones.hide();
    title_form.html('Voluntario Menor');
    $('#form-menores')[0].reset();
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    $('label[class="error"]').css('display', 'none');
});

$('#form-mayores .input-field .info-fecha').hide()
$('#info-fecha1').on('click', function() {
    $('#form-mayores .input-field .info-fecha').slideToggle(300);
});
$('#form-menores .form-section-content .input-field .info-fecha').hide()
$('#info-fecha2').on('click', function() {
    $('#form-menores .form-section-content .input-field .info-fecha').slideToggle(300);
});

let input_form = $('.form-section-content');
let form_acordeon = $('.form-section');
// input_form.eq(0).fadeIn();
var contador = 0;
input_form.eq(1).hide();
form_acordeon.eq(1).on('click', function() {
    contador++;
    input_form.eq(1).slideToggle(300);
    if (contador % 2 == 0) {
        $('#insert-menores').attr('disabled', 'disabled');
    } else {
        $('#insert-menores').removeAttr('disabled');
    }
});

$("#close").on('click', function() {
    modal.hide(300);
    form_mayores.hide(300);
    form_menores.hide(300);
    input_form.eq(0).fadeIn();
    input_form.eq(1).hide();
});

$(window).on('click', function(e) {
    if (e.target === $("#modal-flex")[0]) {
        modal.hide(300);
        form_mayores.hide(300);
        form_menores.hide(300);
        input_form.eq(0).fadeIn();
        input_form.eq(1).hide();
    }
});

$(window).on('keyup', function(e) {
    if (e.key === 'Escape') {
        modal.hide(300);
        form_mayores.hide(300);
        form_menores.hide(300);
        input_form.eq(0).fadeIn();
        input_form.eq(1).hide();
    }
});

/****************************************************************************
                        INSERTAR VOLUNTARIO MAYOR
****************************************************************************/
$(function() {
    $('#form-mayores').validate({
        rules: {
            nombres: { required: true, alfaOespacio: true },
            apellidos: { required: true, alfaOespacio: true },
            f_nacimiento_mayor: { required: true, min: false, max: false, minEdadMay: true, maxEdadMay: true },
            DUI: { required: true, isDUI: true },
            email: { required: true, correo: true },
            departamento_residencia: { required: true },
            municipio_residencia: { required: true },
            direccion: { required: true },
            telefono: { required: true },
            fecha_finalizacion: { required: true, min: false, max: false, minFin: true, maxFin: true }
        },
        messages: {
            nombres: { required: 'Nombres requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            apellidos: { required: 'Apellidos requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            f_nacimiento_mayor: {
                required: 'Fechade nacimiento requerida.',
                minEdadMay: 'Edad m\u00e1xima 40 a\u00f1os',
                maxEdadMay: 'Edad m\u00ednima 18 a\u00f1os'
            },
            DUI: { required: 'DUI requerido.', isDUI: 'DUI inv\u00e1lido.' },
            email: { required: 'Email requerido.', correo: 'Ingrese un email v\u00e1lido.' },
            departamento_residencia: 'Departamento requerido.',
            municipio_residencia: 'Municipio requerido.',
            direccion: 'Direcci\u00f3n requerida.',
            telefono: 'Tel\u00f3fono requerido.',
            fecha_finalizacion: {
                required: 'Fecha finalizaci\u00f3n requerida.',
                minFin: 'Debe ser mayor o igual a: ' + f_MinFin(0),
                maxFin: 'Debe ser menor o igual a: ' + f_MaxFin(0)
            }
        },
        invalidHandler: function(error, element) {
            Swal.fire({
                icon: 'error',
                iconColor: '#fff',
                background: '#f00',
                title: '<p style="color: #fff; font-size: 1.18em;">Campos vac\u00edos o inv\u00e1lidos!</p>',
                confirmButtonColor: "#343a40"
            });
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $.ajax({
                url: url + 'inicio/guardar1',
                data: $(form).serialize(),
                type: 'POST',
                async: false,
                dataType: 'json',
                success: function(msg) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        iconColor: 'white',
                        background: 'dodgerblue',
                        position: 'top-end',
                        title: '<p style="color: white; font-size: 1.18em;">Espere unos segundos mientras se genera el pdf!</p>',
                        showConfirmButton: false,
                        timerProgressBar: true,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        timer: 5000
                    });

                    form.submit();
                    modal.hide(300);
                    $(form)[0].reset();
                    form_mayores.hide(300);
                }
            });
            return false;
        }
    });
});

/****************************************************************************
                        INSERTAR VOLUNTARIO MENOR
****************************************************************************/
$(function() {
    $("#form-menores").validate({
        rules: {
            parentesco: { required: true, alfaOespacio: true },
            nombres_ref: { required: true, alfaOespacio: true },
            apellidos_ref: { required: true, alfaOespacio: true },
            f_nacimiento_ref: { required: true, min: false, max: false, minEdadRes: true, maxEdadRes: true },
            DUI_ref: { required: true, isDUI: true },
            telefono_ref: { required: true },
            nombres_menor: { required: true, alfaOespacio: true },
            apellidos_menor: { required: true, alfaOespacio: true },
            f_nacimiento_menor: { required: true, min: false, max: false, minEdadMen: true, maxEdadMen: true },
            email: { required: true, correo: true },
            departamento_residencia: { required: true },
            municipio_residencia: { required: true },
            direccion: { required: true },
            telefono_menor: { required: true },
            fecha_finalizacion: { required: true, min: false, max: false, minFin: true, maxFin: true }
        },
        messages: {
            parentesco: { required: 'Parentezco requerido.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            nombres_ref: { required: 'Nombres requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            apellidos_ref: { required: 'Apellidos requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            f_nacimiento_ref: {
                required: 'Fecha nacimiento requerida.',
                minEdadRes: 'Edad m\u00e1xima 70 a\u00f1os',
                maxEdadRes: 'Edad m\u00ednima 20 a\u00f1os'
            },
            DUI_ref: { required: 'DUI requerido.', isDUI: 'DUI inv\u00e1lido.' },
            telefono_ref: 'Tel\u00f3fono requerido.',
            nombres_menor: { required: 'Nombres requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            apellidos_menor: { required: 'Apellidos requeridos.', alfaOespacio: 'S\u00f3lo letras o espacios.' },
            f_nacimiento_menor: {
                required: 'Fecha nacimiento requerida.',
                minEdadMen: 'Edad m\u00e1xima 17 a\u00f1os',
                maxEdadMen: 'Edad m\u00ednima 12 a\u00f1os'
            },
            email: { required: 'Email requerido.', correo: 'Ingrese un email v\u00e1lido.' },
            departamento_residencia: { required: 'Departamento requerido.' },
            municipio_residencia: 'Municipio requerido.',
            direccion: 'Direcci\u00f3n requerida.',
            telefono_menor: 'Tel\u00f3fono requerido.',
            fecha_finalizacion: {
                required: 'Fecha finalizaci\u00f3n requerida.',
                minFin: 'Debe ser mayor o igual a: ' + f_MinFin(0),
                maxFin: 'Debe ser menor o igual a: ' + f_MaxFin(0)
            }
        },
        invalidHandler: function(error, element) {
            Swal.fire({
                icon: 'error',
                iconColor: '#fff',
                background: '#f00',
                title: '<p style="color: #fff; font-size: 1.18em;">Campos vac\u00edos o inv\u00e1lidos!</p>',
                confirmButtonColor: "#343a40"
            });
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $.ajax({
                url: url + 'inicio/guardar2',
                data: $(form).serialize(),
                type: 'POST',
                async: false,
                dataType: 'json',
                success: function(msg) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        iconColor: 'white',
                        background: 'dodgerblue',
                        position: 'top-end',
                        title: '<p style="color: white; font-size: 1.18em;">Espere unos segundos mientras se genera el pdf!</p>',
                        showConfirmButton: false,
                        timerProgressBar: true,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        timer: 5000
                    });

                    form.submit();
                    modal.hide(300);
                    $(form)[0].reset();
                    form_menores.hide(300);
                    input_form.eq(0).fadeIn();
                    input_form.eq(1).hide();
                }
            });
            return false;
        }
    });
});

/****************************************************************************
                        GALERÍA DE IMAGENES CON GSAP
****************************************************************************/
$(document).ready(function() {
    let mainBoxesAncho,
        mainBoxesTop = '50%',
        cantFotos,
        photoBoxMargin,
        photoBoxWidth,
        photoBoxHeight,
        photoBoxTranslateY1,
        photoBoxTranslateY2;

    function galeria() {
        if ($(window).width() <= 576) {
            mainBoxesAncho = 412;
            mainBoxesTop = '60%';
            cantFotos = 12;
            photoBoxWidth = 250;
            photoBoxHeight = 400;
            photoBoxMargin = [-63, 80, 223];
            photoBoxTranslateY1 = [-377.5, 490, 490];
            photoBoxTranslateY2 = [490, -377.5, -377.5];
        } else if ($(window).width() > 576 && $(window).width() <= 850) {
            mainBoxesAncho = 642;
            cantFotos = 12;
            photoBoxWidth = 400;
            photoBoxHeight = 640;
            photoBoxMargin = [-100, 120, 340];
            photoBoxTranslateY1 = [-575, 800, 800];
            photoBoxTranslateY2 = [800, -575, -575];
        } else if ($(window).width() > 850 && $(window).width() <= 1200) {
            mainBoxesAncho = 862;
            cantFotos = 16;
            photoBoxWidth = 400;
            photoBoxHeight = 640;
            photoBoxMargin = [-100, 120, 340, 560];
            photoBoxTranslateY1 = [-575, 800, -575, 800];
            photoBoxTranslateY2 = [800, -575, 800, -575];
        } else {
            mainBoxesAncho = 1082;
            cantFotos = 20;
            photoBoxWidth = 400;
            photoBoxHeight = 640;
            photoBoxMargin = [-100, 120, 340, 560, 780];
            photoBoxTranslateY1 = [-575, 800, -575, 800, -575];
            photoBoxTranslateY2 = [800, -575, 800, -575, 800];
        }
    }

    window.onresize = galeria();

    var currentImg = undefined,
        currentImgProps = {
            x: 0,
            y: 0
        },
        isZooming = false,
        column = -1,
        mouse = {
            x: 0,
            y: 0
        },
        delayedPlay;

    // $.ajax({
    //     url: url + 'Galeria/printImgGalery',
    //     type: 'get',
    //     dataType: 'json',
    //     cache: false,
    //     success: function(data) {
    //         $.each(data, function(index, object) {
    //             console.log(index, object.ruta_archivo);
    //             $('#galeria' + (index + 1)).val(object.ruta_archivo);
    //         });
    //     }
    // });
    // $.ajax({
    //     url: url + 'Galeria/printImgGalery/' + i,
    //     method: 'POST',
    //     data: { 'id_galeria': i },
    //     dataType: 'json',
    //     success: function(response) {
    //         // imgGaleria = response.ruta_archivo;
    //         // console.log(typeof response.ruta_archivo);
    //         // console.log(imgGaleria);
    //     }
    // });

    // var imgGaleria = '';

    // // var imgGaleria = undefined;
    // $.ajax({
    //     url: url + 'Galeria/cargarImg/' + i,
    //     method: 'get',
    //     data: { 'id_galeria': i },
    //     dataType: 'json',
    //     // async: false,
    //     success: function(response) {
    //         imgGaleria = response.ruta_archivo;
    //         return imgGaleria;
    //     }
    // });

    // async function fixCode() {
    //     var lastID = '';
    //     await $.ajax({
    //         url: url + 'Galeria/cargarImg/' + i,
    //         type: "GET",
    //         data: { 'id_galeria': i },
    //         dataType: 'json',
    //         success: function(response) {
    //             lastID = response.ruta_archivo;
    //         }
    //     });
    //     return lastID;
    // }

    // // console.log(fixCode());

    // fixCode().then(function(val) {
    //     // val is now promiseA's result + 1
    //     console.log(val);
    // });


    // gsap.set(b, {
    //     attr: {
    //         id: 'b' + i,
    //         class: 'photoBox pb-col' + column
    //     },
    //     backgroundImage: 'url(' + url + 'assets/img/galery/galeria1.jpeg)',
    //     backgroundSize: 'cover',
    //     backgroundPosition: 'center',
    //     overflow: 'hidden',
    //     x: photoBoxMargin[column],
    //     width: photoBoxWidth,
    //     height: photoBoxHeight,
    //     borderRadius: 20,
    //     scale: 0.5,
    //     zIndex: 1
    // });
    for (var i = 1; i <= cantFotos; i++) {
        if ((i - 1) % 4 == 0) column++;
        var b = document.createElement('div');
        $('.mainBoxes').append(b);

        gsap.set(b, {
            attr: {
                id: 'b' + i,
                class: 'photoBox pb-col' + column
            },
            backgroundImage: 'url(' + url + 'assets/img/galery/galeria' + i + '.jpg)',
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            overflow: 'hidden',
            x: photoBoxMargin[column],
            width: photoBoxWidth,
            height: photoBoxHeight,
            borderRadius: 20,
            scale: 0.5,
            zIndex: 1
        });

        b.tl = gsap.timeline({
                paused: true,
                repeat: -1
            })
            .fromTo(b, {
                y: photoBoxTranslateY1[column],
                rotation: -0.05
            }, {
                duration: [40, 35, 30, 25, 20][column],
                y: photoBoxTranslateY2[column],
                rotation: 0.05,
                ease: 'none'
            })
            .progress(i % 4 / 4)

    }

    function pauseBoxes(b) {
        var classStr = 'pb-col0';
        if ($(b).hasClass('pb-col1')) classStr = 'pb-col1';
        if ($(b).hasClass('pb-col2')) classStr = 'pb-col2';
        if ($(b).hasClass('pb-col3')) classStr = 'pb-col3';
        if ($(b).hasClass('pb-col4')) classStr = 'pb-col4';
        for (var i = 0; i < $('.mainBoxes').children().length; i++) {
            var b = $('.mainBoxes').children()[i];
            if ($(b).hasClass(classStr)) gsap.to(b.tl, {
                timeScale: 0,
                ease: 'sine'
            });
        }
    }

    function playBoxes() {
        for (var i = 0; i < $('.mainBoxes').children().length; i++) {
            var tl = $('.mainBoxes').children()[i].tl;
            tl.play();
            gsap.to(tl, {
                duration: 0.4,
                timeScale: 1,
                ease: 'sine.in',
                overwrite: true
            });
        }
    }

    window.onload = function() {
        var _tl = gsap.timeline({
                onStart: playBoxes
            })
            .set('.main', {
                perspective: 800
            })
            .set('.photoBox', {
                opacity: 1,
                cursor: 'pointer',
                boxShadow: '0 4px 10px rgb(40 45 49 / 40%)'
            })
            .set('.mainBoxes', {
                top: mainBoxesTop,
                left: '45%',
                xPercent: -50,
                yPercent: -50,
                width: mainBoxesAncho,
                rotationX: 14,
                rotationY: -15,
                rotationZ: 10,
            })
            .set('.mainClose', {
                autoAlpha: 0,
                width: 60,
                height: 60,
                right: 10,
                top: '110px',
                pointerEvents: 'none'
            })
            .fromTo('.main', {
                autoAlpha: 0
            }, {
                duration: 0.6,
                ease: 'power2.inOut',
                autoAlpha: 1
            }, 0.2)

        $('.photoBox').on('mouseenter', function(e) {
            //console.log($(e.currentTarget).hasClass('pb-col1'));
            if (currentImg) return;
            if (delayedPlay) delayedPlay.kill();
            pauseBoxes(e.currentTarget);
            var _t = e.currentTarget;
            gsap.to('.photoBox', {
                duration: 0.2,
                overwrite: 'auto',
                opacity: function(i, t) {
                    return (t == _t) ? 1 : 0.33
                }
            });
            gsap.fromTo(_t, {
                zIndex: 100
            }, {
                duration: 0.2,
                scale: 0.62,
                overwrite: 'auto',
                ease: 'power3'
            });
        });

        $('.photoBox').on('mouseleave', function(e) {
            if (currentImg) return;
            var _t = e.currentTarget;

            if (gsap.getProperty(_t, 'scale') > 0.62) {
                delayedPlay = gsap.delayedCall(0.3, playBoxes);
            } else {
                playBoxes();
            }

            gsap.timeline()
                .set(_t, {
                    zIndex: 1
                })
                .to(_t, {
                    duration: 0.3,
                    scale: 0.5,
                    overwrite: 'auto',
                    ease: 'expo'
                }, 0)
                .to('.photoBox', {
                    duration: 0.5,
                    opacity: 1,
                    ease: 'power2.inOut'
                }, 0);
        });

        $('.photoBox').on('click', function(e) {
            if (!isZooming) {
                isZooming = true;
                gsap.timeline({
                    defaults: {
                        ease: 'expo.inOut'
                    }
                }).to('.mainBoxes', {
                    duration: 0.5,
                    yPercent: 0,
                    overwrite: true
                }, 0)
                gsap.delayedCall(0.8, function() {
                    isZooming = false
                });

                if (currentImg) {
                    playBoxes();
                    gsap.timeline({
                            defaults: {
                                ease: 'expo.inOut'
                            }
                        })
                        .to('.mainClose', {
                            duration: 0.1,
                            autoAlpha: 0,
                            overwrite: true
                        }, 0)
                        .to('.mainBoxes', {
                            duration: 0.5,
                            scale: 1,
                            top: mainBoxesTop,
                            left: '45%',
                            yPercent: -50,
                            width: mainBoxesAncho,
                            rotationX: 14,
                            rotationY: -15,
                            rotationZ: 10,
                            overwrite: true
                        }, 0)
                        .to('.photoBox', {
                            duration: 0.6,
                            top: 0,
                            opacity: 1,
                            ease: 'power4.inOut'
                        }, 0)
                        .to(currentImg, {
                            duration: 0.6,
                            width: photoBoxWidth,
                            height: photoBoxHeight,
                            borderRadius: 20,
                            x: currentImgProps.x,
                            y: currentImgProps.y,
                            scale: 0.5,
                            rotation: 0,
                            zIndex: 1
                        }, 0)
                    currentImg = undefined;
                } else {
                    pauseBoxes(e.currentTarget)

                    currentImg = e.currentTarget;
                    currentImgProps.x = gsap.getProperty(currentImg, 'x');
                    currentImgProps.y = gsap.getProperty(currentImg, 'y');

                    gsap.timeline({
                            defaults: {
                                duration: 0.6,
                                ease: 'expo.inOut'
                            }
                        })
                        .set(currentImg, {
                            zIndex: 100
                        })
                        .fromTo('.mainClose', {
                            x: mouse.x,
                            y: mouse.y,
                            background: 'rgba(0,0,0,0)'
                        }, {
                            autoAlpha: 1,
                            duration: 0.3,
                            ease: 'power3.inOut'
                        }, 0)
                        .to('.photoBox', {
                            opacity: 0
                        }, 0)
                        .to(currentImg, {
                            width: '100%',
                            height: '100%',
                            borderRadius: 0,
                            x: 0,
                            top: '-50%',
                            y: 0,
                            scale: 1,
                            opacity: 1
                        }, 0)
                        .to('.mainBoxes', {
                            duration: 0.5,
                            top: '50%',
                            left: '50%',
                            width: '100%',
                            rotationX: 0,
                            rotationY: 0,
                            rotationZ: 0
                        }, 0.15)
                        .to('.mainBoxes', {
                            duration: 5,
                            scale: 1.06,
                            rotation: 0.05,
                            ease: 'none'
                        }, 0.65)
                }
            }
        });

    }
});


/****************************************************************************
                            TARJETAS DE PRODUCTOS
****************************************************************************/
$(document).ready(function() {
    let productos = document.getElementById('products-container');
    $.ajax({
        url: url + "productos/verProductos",
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (Array.isArray(data)) {
                $.each(data, function(index, object) {
                    // products desktop
                    productos.innerHTML +=
                        `
                            <div class="card">
                                <figure>
                                    <img src="${url}${object.ruta_archivo}">
                                </figure>
                                <div class="card-content">
                                    <h2>${object.nombre}</h2>
                                    <p>${object.descripcion}.</p>
                                    <h2>$${object.precio}</h2>
                                </div>
                            </div>
                        `;
                    
                    // products mobile
                    productos.innerHTML +=
                        `
                            <div class="card-wrap">
                                <div class="front-face" style="background-image: url(${url}${object.ruta_archivo});">
                                </div>
                                <div class="back-face">
                                    <h2>${object.nombre}</h2>
                                    <p>${object.descripcion}.</p>
                                    <h2>$${object.precio}</h2>
                                </div>
                            </div>
                        `;
                });
            } else {
                console.error("Error: Datos inesperados");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", status, error);
        }
    });
});

/****************************************************************************
                                ACERCA DE
****************************************************************************/

let p = $('.acordeon-content');
let acordeon = $('.acordeon');

acordeon.on('click', e => {
    let x = 0;
    while (x < acordeon.length) {
        if (e.target === acordeon[x]) {
            p.eq(x).slideToggle(300);
        }
        x++;
    }
});