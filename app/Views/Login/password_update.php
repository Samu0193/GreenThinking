<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Thinking SV - Actualizar Contraseña</title>
    <link href="<?= base_url('assets/img/favicon.png'); ?>" rel="shortcut icon" type="image/x-icon">
    <link href="<?= base_url('assets/css/sweetalert2.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/css/login.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="main-container">

        <div class="form-content">

            <form id="form-reset" novalidate>
                <h2 class="forgot-title">Actualizar Contraseña</h2>
                <div class="input-div one" style="text-align: left;">
                    <input type="hidden" name="hash" id="hash" value="<?= $hash; ?>">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Nueva Contraseña</h5>
                        <input type="password" name="password" id="password" class="input" required>
                    </div>
                </div>
                <div class="input-div pass" style="text-align: left;">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Confirmar Contraseña</h5>
                        <input type="password" name="re_password" id="re_password" class="input" required>
                    </div>
                </div>
                <input type="submit" class="btn" value="Guardar">
            </form>

        </div>

    </div>

    <svg style="position:absolute; z-index: -1; bottom:0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#40A933" fill-opacity="1"
            d="M0,256L48,245.3C96,235,192,213,288,197.3C384,181,480,171,576,181.3C672,192,768,224,864,240C960,256,1056,256,1152,224C1248,192,1344,128,1392,96L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
        </path>
    </svg>

    </div>

    <script type="text/javascript">
        var url = '<?= site_url(); ?>';
    </script>

    <!-- ALERTAS JS -->
    <script src="<?= base_url('assets/js/sweetalert2.js'); ?>" type="text/javascript"></script>

    <!-- VALIDACION DE CAMPOS-->
    <script src="<?= base_url('assets/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/additional-methods.min.js'); ?>" type="text/javascript"></script>

    <!-- MAIN JS -->
    <script src="<?= base_url('assets/script/Login/ResetPass.js'); ?>" type="text/javascript"></script>

</body>

</html>