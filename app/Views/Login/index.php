<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Thinking SV - Iniciar Sesion</title>
    <link href="<?= base_url('assets/img/favicon.png'); ?>" rel="shortcut icon" type="image/x-icon">
    <link href="<?= base_url('assets/css/login.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/css/sweetalert2.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <script src="https://kit.fontawesome.com/a81368914c.js"></script> -->
</head>

<body>
    <img class="wave" src="<?= base_url('assets/img/wave.png'); ?>">
    <div class="container">
        <div class="img">
            <img src="<?= base_url('assets/img/hojinobg.png'); ?>">
        </div>
        <div class="login-content">
            <form action="<?= site_url('login/verifica'); ?>" id="Login" method="POST">
                <img src="<?= base_url('assets/img/avatar.svg'); ?>">
                <h2 class="title">Bienvenido</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input type="text" name="nombre" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" name="password" class="input">
                    </div>
                </div>
                <a href="<?= site_url('login/forgotPassword'); ?>">¿Olvidaste tu contraseña?</a>
                <input type="submit" class="btn" value="Entrar">
            </form>
        </div>
    </div>

    <script type="text/javascript">
        var url = '<?= site_url(); ?>';
    </script>

    <script src="<?= base_url('assets/js/sweetalert2.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/script/Login/login.js'); ?>" type="text/javascript"></script>

    <!--Mensaje de error-->
    <?php if (session()->getFlashdata('message') != null) : ?>
        <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                iconColor: '#fff',
                background: '#f00',
                title: '<p style="color: #fff; font-size: 1.18em;">' +
                    '<?= session()->getFlashdata('message'); ?>' + '</p>',
                confirmButtonColor: "#343a40"
            });
        </script>
    <?php endif; ?>

</body>

</html>