<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <?php
            if ($_SESSION['tipo'] == '1') {
                include '../src/Views/meu-gabinete/gestor.php';
            } else {
                include '../src/Views/meu-gabinete/usuario.php';
            }
            ?>
        </div>
    </div>
</div>