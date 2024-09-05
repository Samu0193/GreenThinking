<!DOCTYPE html>
<html>

<head>

</head>

<body>

    <img src="<?= base_url('assets/img/logoGT.jpeg'); ?>" style="float: left;" width="150px" hidden="150px">
    <h1 style="position: absolute;margin-top: 25px; left: 275px;">Green Thinking SV</h1>

    <p style="position: absolute;margin-top: 75px; left: 310px;"><b>Solicitud de Voluntariado</b></p>
    <?php
        $fechaIngreso = strtotime($b['fecha_ingreso']);
        $fechaFin =  strtotime($b['fecha_finalizacion']);

        $diaI = date('d', $fechaIngreso);
        $mesI = date('m', $fechaIngreso);
        $anioI = date('y', $fechaIngreso);

        $diaF = date('d', $fechaFin);
        $mesF = date('m', $fechaFin);
        $anioF = date('y', $fechaFin);
    ?>
    <p></p><br><br><br><br>
    <p>Yo <b><?= $b['nombres'] ?> <?= $b['apellidos'] ?> </b>Estoy dispuest@ a formar parte del voluntariado de Green Thinking
        desde la fecha de <b><?= $diaI ?></b> de <b> <?= $mesI; ?> </b> del <b><?= $anioI; ?> </b> hasta la fecha de
        <b><?= $diaF ?></b> de <b> <?= $mesF; ?> </b> del <b><?= $anioF; ?> </b>, el cual me ayudara para el desarrollo de
        habilidades para mi persona como conciencia ambiental, empatia con los demas y el medio ambiente y muchos otros
        valores a traves de las diversas actividades que se desarrollan dentro de Green Thinking por lo cual firmo la
        presente Carta para hacer constar que estoy de acuerdo en formar parte del voluntariado y dar autorizacion para
        que pueda aparecer mis fotos tomadas en el proyecto en la pagina oficial de Green Thinking.
    </p><br><br>
    <p>Firma voluntari@: _________________________</p>
    <br><br>
    <p>Firma de los fundadores de Green Thinking</p>
    <br><br>
    <p>_______________________</p>
    <p style="margin-left: 32px;">Gisela Ramos</p>
    <p style="position: absolute; margin-top: -69px; left: 400px;">_______________________</p>
    <p style="position: absolute; margin-top: -35px; left: 429px;">Julio Grande</p>
</body>

</html>