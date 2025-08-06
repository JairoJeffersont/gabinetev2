<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$gabineteController = new \App\Controllers\GabineteController();
$usuarioController = new \App\Controllers\UsuarioController();
$pessoaController = new \App\Controllers\PessoaController();

$buscaGabinete = $gabineteController->buscar($_SESSION['gabinete']);
$buscaUsuario = $usuarioController->buscar($_SESSION['id']);

$buscaUsuarios = $usuarioController->listar('nome', 'asc', 1000, 1, ['gabinete' => $_SESSION['gabinete'], 'ativo' => 0], 'and');

?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>

        <div class="container-fluid p-2">

            <div class="card mb-2 border-0">
                <div class="card-body custom-card-body p-3">
                    <h6 class="card-title mb-0 mt-0"> Bem-vindo(a) <?= $buscaUsuario['data']['nome'] ?></h6>
                </div>
            </div>

            <?php
            $alerts = [];

            if ($buscaGabinete['data']['partido'] == null || $buscaGabinete['data']['cidade'] == null) {
                $alerts[] = '<div class="alert alert-info custom-alert px-2 py-1" role="alert"><a href="?secao=meu-gabinete" class="alert-link"><i class="bi bi-info-circle-fill"></i> Complete o cadastro do gabinete.</a></div>';
            }

            if ($buscaUsuario['data']['aniversario'] == null) {
                $alerts[] = '<div class="alert alert-info custom-alert px-2 py-1" role="alert"><a href="?secao=meu-gabinete" class="alert-link"><i class="bi bi-info-circle-fill"></i> Atualize seus dados pessoais.</a></div>';
            }

            if ($buscaUsuarios['status'] == 'success') {
                $alerts[] = '<div class="alert alert-info custom-alert px-2 py-1" role="alert"><a href="?secao=meu-gabinete" class="alert-link"><i class="bi bi-info-circle-fill"></i> Você tem novos usuários aguardando ativação.</a></div>';
            }

            if ($buscaUsuario['status'] === 'success' && $buscaUsuario['data']['aniversario'] === date('d/m')) {
                $alerts[] = '<div class="alert alert-warning custom-alert px-2 py-1" role="alert"><i class="bi bi-cake"></i> Parabéns pelo seu aniversário!</div>';
            }

            if (!empty($alerts)) {
                echo '<div class="card mb-2" style="background: linear-gradient(45deg, rgba(2, 122, 131, 0.7), rgba(255,41,209,0.7));"><div class="card-body custom-card-body p-2">';
                $total = count($alerts);
                foreach ($alerts as $index => $alert) {
                    $mbClass = ($index == $total - 1) ? 'mb-0' : 'mb-2';
                    echo str_replace('custom-alert', 'custom-alert ' . $mbClass, $alert);
                }
                echo '</div></div>';
            }
            ?>
        </div>
    </div>
</div>