<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>Green Thinking SV</title>
    <link href="<?= base_url('public/assets/img/favicon.png'); ?>" rel="shortcut icon" type="image/x-icon">
    <link href="<?= base_url('public/assets/css/sweetalert2.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('public/assets/css/style.css'); ?>" rel="stylesheet" type="text/css">
    <!-- <link href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>
    <script src="<?= base_url('public/assets/js/jquery.maskedinput.js'); ?>" type="text/javascript"></script>
</head>

<body>

    <!-- Navegacion -->
    <header>
        <!-- NavBar Desktop -->
        <nav class="navbar" id="navbar">
            <a href="<?= site_url(); ?>">
                <img src="<?= base_url('public/assets/img/logoletrablanca.png'); ?>" class="logo-green-thinking" alt="" id="logo">
            </a>
            <ul>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-leaf"></i> Inicio
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-images"></i> Galería
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-money-bill-wave"></i> Productos
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-donate"></i> Donaciones
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-address-card"></i> Acerca de
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-mobile-alt"></i> Contacto
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="btn btn-mostrar-modal mostrar-modal">
                        <i class="fas fa-sign-in-alt"></i> Registro
                    </a>
                </li>
            </ul>

            <!-- Icono Menu Mobile -->
            <div class="menu-mobile" id="menu-mobile">
                <span class="hamburger"></span>
                <span class="hamburger"></span>
                <span class="hamburger"></span>
            </div>
        </nav>

        <!-- SideBar Mobile -->
        <div class="sidebar" id="sidebar">
            <ul>
                <hr>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-leaf"></i> Inicio
                    </a>
                </li>
                <hr>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-images"></i> Galería
                    </a>
                </li>
                <hr>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-money-bill-wave"></i> Productos
                    </a>
                </li>
                <hr>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-donate"></i> Donaciones
                    </a>
                </li>
                <hr>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-address-card"></i> Acerca de
                    </a>
                </li>
                <hr>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fas fa-mobile-alt"></i> Contacto
                    </a>
                </li>
                <hr>
                <li>
                    <a href="javascript:void(0)" class="mostrar-modal">
                        <i class="fas fa-sign-in-alt"></i> Registro
                    </a>
                </li>
                <hr>
            </ul>
        </div>
        <div class="bg-sidebar" id="bg-sidebar">
        </div>
    </header>

    <!-- Ventana Modal -->
    <div class="modal" id="modal">
        <div id="modal-flex" class="modal-flex">
            <div class="content-modal">

                <div class="header-modal">
                    <div class="logo">
                        <img src="<?= base_url('public/assets/img/hojinobg.png'); ?>" alt="Green-Thinking" />
                    </div>
                    <h2 class="title-form" id="title-form"></h2>
                    <i class="fa fa-times" id="close"></i>
                </div>

                <!-- FORMULARIOS -->
                <div class="modal-options" id="modal-options">
                    <p>Si quieres ayudarnos a mantener la naturaleza del país, puedes registrarte aquí para formar parte
                        de nosotros, si ya eres un adulto presiona <b>"Mayor de Edad"</b> sino presiona <b>"Menor de
                            Edad"</b> ya que necesitas a alguien que
                        se haga responsable de ti.<br><br>
                        <b>"Al registrarte se generará un documento PDF,
                            descargalo e imprímelo, comunicate con nosotros al siguiente número 7688-1951,
                            para saber a donde debes llevarlo".</b>
                    </p>
                    <div class="modal-buttons">
                        <button class="btn" id="btn-mayores"><i class="fas fa-greater-than"></i> Mayor de edad</button>
                        <button class="btn" id="btn-menores"><i class="fas fa-less-than"></i> Menor de edad</button>
                    </div>
                </div>

                <!-- FORMULARIO MAYORES -->
                <form autocomplete="off" class="form-modal" id="form-mayores" novalidate>

                    <div class="input-field">
                        <label for="nombres">Nombres: </label>
                        <input type="text" name="nombres" id="" placeholder="Ingrese Nombres" required>
                        <label for="apellidos">Apellidos: </label>
                        <input type="text" name="apellidos" id="" placeholder="Ingrese Apellidos" required>
                        <label for="f_nacimiento_mayor">Fecha de Nacimiento: </label>
                        <input type="date" name="f_nacimiento_mayor" id="f_nacimiento_mayor" required>
                        <label for="dui">DUI: </label>
                        <input type="text" name="dui" id="" placeholder="99999999-9" required>
                        <label for="email">Email: </label>
                        <input type="text" name="email" id="" placeholder="Ingrese Email" required>
                        <label for="departamento_residencia">Departamento Residencia: </label>
                        <select name="departamento_residencia" id="" required>

                        </select>
                    </div>
                    <div class="input-field">
                        <label for="municipio_residencia">Municipio Residencia: </label>
                        <select name="municipio_residencia" id="" required>

                        </select>
                        <label for="direccion">Dirección: </label>
                        <textarea name="direccion" id="" rows="5" cols="" placeholder="Ingrese Dirección"
                            required></textarea>
                        <label for="telefono">Teléfono: </label>
                        <input type="text" name="telefono" id="" placeholder="9999-9999" required>
                        <label for="fecha_finalizacion">Fecha de Finalización: <i class="fas fa-info-circle"
                                id="info-fecha1" style="color: #17a2b8; cursor: pointer; font-size: 1.5em;"></i></label>
                        <div class="info-fecha" style="background-color: #dfe0e0;">
                            <h4>
                                "Fecha en la que estimas hasta cuándo estarás con nosotros, mínimo 3 meses, máximo 1 año"
                            </h4>
                        </div>
                        <input type="date" name="fecha_finalizacion" id="" required>
                        <input type="hidden" name="id_voluntario">
                    </div>
                    <button class="btn" type="submit" id="insert-mayor">
                        <i class="fas fa-user-plus"></i>Registrar
                    </button>
                </form>

                <!-- FORMULARIO MENORES -->
                <form autocomplete="off" class="form-modal" id="form-menores" novalidate>

                    <h3 class="form-section" id="form-section">Referencia Personal</h3>
                    <div class="form-section-content">
                        <div class="input-field">
                            <label for="parentesco">Parentezco </label>
                            <select name="parentesco" id="" required>
                                <option selected disabled value=''>Seleccionar... </option>
                                <option value="Padre">Padre</option>
                                <option value="Madre">Madre</option>
                                <option value="Abuelo">Abuelo</option>
                                <option value="Abuela">Abuela</option>
                                <option value="T&iacute;o">T&iacute;o</option>
                                <option value="T&iacute;a">T&iacute;a</option>
                                <option value="Tutor legal">Tutor legal</option>
                            </select>
                            <label for="nombres_ref">Nombres: </label>
                            <input type="text" name="nombres_ref" id="" placeholder="Ingrese Nombres" required>
                            <label for="apellidos_ref">Apellidos: </label>
                            <input type="text" name="apellidos_ref" id="" placeholder="Ingrese Apellidos" required>
                        </div>
                        <div class="input-field">
                            <label for="f_nacimiento_ref">Fecha de Nacimiento: </label>
                            <input type="date" name="f_nacimiento_ref" id="f_nacimiento_ref" required>
                            <label for="dui_ref">DUI: </label>
                            <input type="text" name="dui_ref" id="" placeholder="99999999-9" required>
                            <label for="telefono_ref">Teléfono: </label>
                            <input type="text" name="telefono_ref" id="" placeholder="9999-9999" required>
                        </div>
                    </div>

                    <h3 class="form-section">Datos Voluntario</h3>
                    <div class="form-section-content">
                        <div class="input-field">
                            <label for="nombres_menor">Nombres: </label>
                            <input type="text" name="nombres_menor" id="" placeholder="Ingrese Nombres" required>
                            <label for="apellidos_menor">Apellidos: </label>
                            <input type="text" name="apellidos_menor" id="" placeholder="Ingrese Apellidos" required>
                            <label for="f_nacimiento_menor">Fecha de Nacimiento: </label>
                            <input type="date" name="f_nacimiento_menor" id="" required>
                            <label for="telefono_menor">Teléfono: </label>
                            <input type="text" name="telefono_menor" id="" placeholder="9999-9999" required>
                            <label for="email">Email: </label>
                            <input type="text" name="email" id="" placeholder="Ingrese Email" required>
                        </div>
                        <div class="input-field">
                            <label for="departamento_residencia">Departamento Residencia: </label>
                            <select name="departamento_residencia" id="" required>

                            </select>
                            <label for="municipio_residencia">Municipio Residencia: </label>
                            <select name="municipio_residencia" id="" required>

                            </select>
                            <label for="direccion">Dirección: </label>
                            <textarea name="direccion" id="" rows="5" cols="" placeholder="Ingrese Dirección"
                                required></textarea>
                            
                            <label for="fecha_finalizacion">Fecha de Finalización: <i class="fas fa-info-circle"
                                    id="info-fecha2" style="color: #17a2b8; cursor: pointer; font-size: 1.5em;"></i></label>
                            <div class="info-fecha" style="background-color: #888; color: #fff;">
                                <p>
                                    "Fecha en la que estimas hasta cuándo estarás con nosotros, mínimo 3 meses, máximo 1 año"
                                </p>
                            </div>
                            <input type="date" name="fecha_finalizacion" id="" required>
                        </div>
                    </div>
                    <button class="btn" type="submit" id="insert-menores" disabled>
                        <i class="fas fa-user-plus"></i>Registrar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Seccion Inicio -->
    <section class="begin-section" id="begin">
        <video autoplay muted loop>
            <source src="<?= base_url('public/assets/img/Black_Lava_Field.mp4'); ?>" type="video/mp4">
        </video>

        <article class="banner">
            <div class="box-banner">
                <div class="banner-content">
                    <h1 class="title">
                        Green Thinking
                        <i class="fas fa-seedling" style="color: #3ca230"></i>
                    </h1>
                    <p class="sub-title">
                        <i>Cuidando El Salvador</i>
                    </p>
                </div>
            </div>
        </article>

        <div class="bottom-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>
    </section>
    <!-- Fin Seccion Inicio -->

    <!-- Seccion Galería -->
    <section class="galery-section" id="galery">
        <!-- <h1>Galería</h1> -->

        <div class="top-wave" style="height: 102px; top: -2px;">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>

        <div class="main">
            <div class="mainBoxes fs">

            </div>
            <div class="mainClose">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                    height="100%" fill="none">
                    <circle cx="30" cy="30" r="30" fill="#000" opacity="0.4" />
                    <path d="M15,16L45,46 M45,16L15,46" stroke="#000" stroke-width="3.5" opacity="0.5" />
                    <path d="M15,15L45,45 M45,15L15,45" stroke="#ededed" stroke-width="2" />
                </svg>
            </div>
        </div>

        <div class="bottom-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>
    </section>
    <!-- Fin Seccion Galería -->

    <!-- Seccion Productos -->
    <section class="products-section" id="products">
        <div class="top-wave" style="height: 102px; top: -2px;">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>

        <div class="title-section">
            <h2>Productos</h2>
        </div>

        <div class="info-section" style="background-color: #dfe0e0;">
            <p>
                Para adquirir cualquiera de nuestros productos
                dirigete a la sección de "Contáctanos", puedes hacer tu pedido desde Whatsapp o Facebook.
            </p>
        </div>

        <div class="products-container" id="products-container">

        </div>

        <div class="bottom-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>
    </section>
    <!-- Fin Seccion Productos -->

    <!-- Seccion Donaciones  -->
    <section class="donations-section" id="donations">
        <div class="top-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>

        <div class="title-section">
            <h2>Donaciones</h2>
        </div>

        <div class="info-section">
            <p>
                Ayúdanos a marcar la diferencia o
                sé parte de la solución,
                ayúdanos a salvar el planeta y la juventud hoy,
                sumate a la solución,
                se parte de la solución
                (los iconos sólo son ejemplos).
            </p>
        </div>

        <div class="icons-section">
            <i class="fab fa-cc-visa"></i>
            <i class="fab fa-bitcoin"></i>
            <i class="fab fa-cc-paypal"></i>
        </div>

        <div class="bottom-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>
    </section>
    <!-- Fin Seccion Donaciones  -->

    <!-- Seccion Acerca de Nosotros -->
    <section class="about-section" id="about">
        <div class="top-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>

        <div class="title-section">
            <h2>Acerca de Nosotros</h2>
        </div>

        <div class="info-container" id="info-container">
            <article class="info-section">
                <h3 class="acordeon">Misión</h3>
                <p class="acordeon-content">Somos un movimiento de jóvenes, que ejerce un impacto positivo en la
                    sociedad, por medio de proyectos ambientalistas que involucran a la juventud como agentes de cambio.
                </p>
                <h3 class="acordeon">Objetivos</h3>
                <div class="acordeon-content">
                    <div style="display: flex;">
                        <div>
                            <b>1.</b>
                        </div>
                        &nbsp;
                        <p> Crear conciencia ambiental en la sociedad y buscar alianzas estratégicas dispuestas a ser
                            agentes de cambio. </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>2.</b>
                        </div>
                        &nbsp;
                        <p> Recuperación de diferentes ecosistemas (reforestación, etc.) </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>3.</b>
                        </div>
                        &nbsp;
                        <p> Promover el reciclaje y el consumismo responsable. </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>4.</b>
                        </div>
                        &nbsp;
                        <p> Generación de fondos (venta de productos elaborados a base de reciclaje y naturales). </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>5.</b>
                        </div>
                        &nbsp;
                        <p> Donaciones (arboles, plantas y semillas). </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>6.</b>
                        </div>
                        &nbsp;
                        <p> Creacion de proyectos ambientales (construcción de eco-parques, etc.) </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>7.</b>
                        </div>
                        &nbsp;
                        <p> Tomar en cuentas los ODS de las Naciones Unidas. </p>
                    </div>
                </div>
            </article>
            <article class="info-section">
                <h3 class="acordeon">Visión</h3>
                <p class="acordeon-content">Ser una asociación o fundación auto-sostenible, con alianzas estratégicas
                    que den un impacto positivo en la sociedad, creando conciencia ambiental y procurando el desarrollo
                    de un mundo sostenible.
                </p>
                <h3 class="acordeon">¿Que hacémos?</h3>
                <div class="acordeon-content">
                    <div style="display: flex;">
                        <div>
                            <b>•</b>
                        </div>
                        &nbsp;
                        <p> Donaciones de arboles, plantas y semillas. </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>•</b>
                        </div>
                        &nbsp;
                        <p> Creando imágenes, videos, retos, para concientizar a las personas sobre el cuido del medio
                            ambiente. </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>•</b>
                        </div>
                        &nbsp;
                        <p> Promoviendo el reciclaje con ventas de productos reciclados. </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>•</b>
                        </div>
                        &nbsp;
                        <p> Creando alianzas con negocios, instituciones y empresas para que tengan responsabilidad
                            social. </p>
                    </div>
                    <br>
                    <div style="display: flex;">
                        <div>
                            <b>•</b>
                        </div>
                        &nbsp;
                        <p> Creación de espacios paar la buena convivencia como los eco-parques. </p>
                    </div>
                </div>
            </article>

        </div>

        <div class="bottom-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>
    </section>
    <!-- Fin Seccion Acerca de Nosotros -->

    <!-- Seccion Contactanos -->
    <footer class="contact-section" id="contact">
        <div class="top-wave">
            <svg viewbox="0 0 500 150" preserveaspectratio="none" style="height: 100%; width: 100%">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98
                        L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ededed"></path>
            </svg>
        </div>

        <div class="title-section">
            <h2>Contáctanos</h2>
        </div>

        <div class="contact-container" id="contact-container">
            <div class="footer-card">
                <figure>
                    <img src="<?= base_url('public/assets/img/fundador1.jpeg'); ?>">
                </figure>
                <div class="footer-card-content">
                    <h2>Fundador</h2>
                    <h3>Cesar Grande</h3>
                </div>
            </div>

            <div class="footer-card">
                <figure>
                    <img src="<?= base_url('public/assets/img/fundador2.jpeg'); ?>">
                </figure>
                <div class="footer-card-content">
                    <h2>Fundadora</h2>
                    <h3>Gissel Ramos</h3>
                </div>
            </div>
        </div>

        <div class="icons-section">
            <a href="https://wa.me/50376881951" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="https://www.facebook.com/Hoji.la.hoja" target="_blank">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCj0p9iY_3rT-D8ybD_KxGnA" target="_blank">
                <i class="fab fa-youtube"></i>
            </a>
            <a href="https://instagram.com/greenthinking.sv" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://mobile.twitter.com/greenthinking19" target="_blank">
                <!-- <i class="fab fa-twitter"></i> -->
                <i class="fa-brands fa-x-twitter"></i>
            </a>
        </div>

        <div class="footer-section">
            <h2>Green Thinking &#169; El Salvador <?= date('Y'); ?></h2>
        </div>

    </footer>
    <!-- Fin Seccion Contactanos -->

    <!-- URL PARA METODOS -->
    <script type="text/javascript">

        let url      = '<?= site_url(); ?>';
        let files    = <?= json_encode($files); ?>; // Convierte el array PHP $dataFiles a un JSON válido para usar en JavaScript
        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        // $.each(files.galeria, function(index, imagen) {
        //     console.log(index, imagen);
        // });

        // //Usamos un bucle para objetos, ya que files.galeria es un objeto
        // if (files.galeria) {
        //     // Usamos Object.keys para obtener las claves (1, 2, 3, etc.)
        //     Object.keys(files.galeria).forEach(function(key, index) {
        //         console.log((index + 1) + ": " + files.galeria[key]);
        //     });
        // } else {
        //     console.log("No hay imágenes en la galería.");
        // }
    </script>

    <!-- ALERTAS JS -->
    <script src="<?= base_url('public/assets/js/sweetalert2.js'); ?>" type="text/javascript"></script>

    <!-- VALIDACION DE CAMPOS-->
    <script src="<?= base_url('public/assets/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('public/assets/js/additional-methods.min.js'); ?>" type="text/javascript"></script>

    <!-- MAIN JS -->
    <script src="<?= base_url('public/assets/script/Principal/navs.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('public/assets/script/Principal/main.js'); ?>" type="text/javascript"></script>

</body>

</html>