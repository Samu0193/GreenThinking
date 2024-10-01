<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Thinking SV</title>
    <link href="<?= base_url('assets/img/favicon.png'); ?>" rel="shortcut icon" type="image/x-icon">
    <link href="<?= base_url('assets/css/login.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/css/sweetalert2.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
</head>

<body>

    <img class="wave" src="<?= base_url('assets/img/wave.png'); ?>">

    <div class="container">
        <div class="img">
            <img src="<?= base_url('assets/img/hojinobg.png'); ?>">
        </div>

        <div class="login-content">
            <!--Mensaje de error-->
            <?php if (session()->getFlashdata('message') != null) : ?>
                <h2><br><?= session()->getFlashdata('message'); ?></h2>
                <?= session()->setFlashdata('message', ''); ?>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>