<?php


ob_start();

include '../src/Views/includes/verificaLogado.php';



$gabineteController = new \App\Controllers\GabineteController();
$tipogabinete = $gabineteController->buscar($_SESSION['gabinete'])['data']['tipo'];
$nomeGabinete = $gabineteController->buscar($_SESSION['gabinete'])['data']['nome'];




?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
           

            <?php
            if ($tipogabinete == '1') {
                include '../src/Views/proposicoes/CD.php';
            } 
            ?>
        </div>
    </div>
</div>