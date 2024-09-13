<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Thinking SV - Recuperar Contraseña</title>
    <link href="<?= base_url('assets/img/favicon.png'); ?>" rel="shortcut icon" type="image/x-icon">
    <link href="<?= base_url('assets/css/login.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/css/sweetalert2.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <img class="wave" src="<?= base_url('assets/img/wave.png'); ?>">
    <div class="container">
        <div class="img">
            <img src="<?= base_url('assets/img/hojinobg.png'); ?>">
        </div>
        <div class="login-content">

            <!-- COLOCAR MENSAJE DE ENVIO DE CORREO -->

            <form id="form-forgot" novalidate style="padding: 1rem; /*box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);*/">
                <h3 class="title">Actualizar Contraseña<br></h3>

                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="div">
                        <h5><br>Correo Electrónico</h5>
                        <input type="text" name="email" id="email" class="input" required>
                    </div>
                </div>
                <!-- <input type="submit" class="btn" value="Enviar Código"> -->
                <button type="submit" class="btn">Enviar Código</button>
            </form>
        </div>
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
    <script src="<?= base_url('assets/script/Login/Forgot.js'); ?>" type="text/javascript"></script>

</body>

</html>