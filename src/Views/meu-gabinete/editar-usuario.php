<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

use App\Controllers\UsuarioController;
use App\Controllers\UsuarioTipoController;

$tipoUsuarioController = new UsuarioTipoController();
$usuarioController = new UsuarioController();
$usuarioIdGet = $_GET['id'] ?? null;

$gabineteSessao = $_SESSION['gabinete'];
$usuarioSessao = $_SESSION['id'];
$usuarioTipo = $_SESSION['tipo'];
$buscaUsuario = $usuarioController->buscar($usuarioIdGet, 'id');

if ($buscaUsuario['status'] != 'success') {
    header('Location: ?secao=meu-gabinete');
}

$buscaTipo = $tipoUsuarioController->listar();

?>


<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">

            <div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary custom-card-body btn-sm link_loading" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
                    <a class="btn btn-success custom-card-body btn-sm link_loading" href="?secao=meu-gabinete" role="button"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-header custom-card-header px-2 py-1">
                    <i class="bi bi-person-gear"></i> Gerenciar Usuário
                </div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-2">Nesta seção, você pode alterar o tipo de usuário ou removê-lo do sistema.</p>
                    <p class="card-text mb-0"><i class="bi bi-exclamation-triangle-fill"></i> <b>Se o usuário possuir conteúdo associado, a exclusão não será possível. Nesse caso, desative-o.</b></p>
                </div>
            </div>


            <div class="card card-perfil w-100 p-2 d-flex flex-row align-items-center mb-2 text-white">
                <img src="public/<?= ($buscaUsuario['data']['foto']) ? $buscaUsuario['data']['foto'] : 'img/images.png' ?>" alt="Foto do usuário" class="foto-perfil me-3">
                <div>
                    <h5 class="mb-1"><?= $buscaUsuario['data']['nome'] ?></h5>
                    <p class="mb-0"><?= ($buscaUsuario['data']['email']) ? $buscaUsuario['data']['email'] : 'Email não informado' ?></p>
                    <p class="mb-0"><?= ($buscaUsuario['data']['telefone']) ? $buscaUsuario['data']['telefone'] : 'Whatsapp não informado' ?></p>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body custom-card-body p-2">

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar_usuario'])) {
                        $dadosUsuario = [
                            'tipo_id' => $_POST['tipo_id'],
                            'ativo' => $_POST['ativo']
                        ];

                        $resultUsuarios = $usuarioController->atualizarUsuario($usuarioIdGet, $dadosUsuario, 'id');

                        if ($resultUsuarios['status'] == 'success') {
                            $buscaUsuario = $usuarioController->buscar($usuarioIdGet, 'id');
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="3">Usuário atualizado com sucesso!</div>';
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar_usuario'])) {
                        $resultApagar = $usuarioController->apagarUsuario($usuarioIdGet, 'id');
                        if ($resultApagar['status'] == 'success') {
                            header('Location: ?secao=meu-gabinete');
                        } else {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert" data-timeout="3">' . $resultApagar['message'] . '</div>';
                        }
                    }
                    ?>

                    <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">

                        <div class="col-md-3 col-6">
                            <select class="form-select form-select-sm" name="tipo_id" required>
                                <?php

                                if ($buscaTipo['status'] == 'success') {
                                    foreach ($buscaTipo['data'] as $tipo) {
                                        if ($tipo['id'] == $buscaUsuario['data']['tipo_id']) {
                                            echo '<option value="' . $tipo['id'] . '" selected>' . $tipo['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $tipo['id'] . '">' . $tipo['nome'] . '</option>';
                                        }
                                    }
                                }

                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <select class="form-select form-select-sm" name="ativo" required>
                                <option value="1" <?php echo ($buscaUsuario['data']['ativo']) ? 'selected' : ''; ?>>Ativo</option>
                                <option value="0" <?php echo (!$buscaUsuario['data']['ativo']) ? 'selected' : ''; ?>>Desativado</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-6">
                            <button type="submit" class="btn btn-primary btn-sm confirm-action" name="btn_atualizar_usuario"><i class="bi bi-floppy-fill"></i> Atualizar</button>
                            <button type="submit" class="btn btn-danger btn-sm confirm-action" name="btn_apagar_usuario"><i class="bi bi-trash-fill"></i> Apagar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>