<?php

use App\Controllers\GabineteController;
use App\Controllers\TipoGabineteController;
use App\Controllers\UsuarioController;
use App\Controllers\UsuarioTipoController;

$tipoGabineteController = new TipoGabineteController();
$gabineteController = new GabineteController();
$tipoUsuarioController = new UsuarioTipoController();
$usuarioController = new UsuarioController();

$gabineteSessao = $_SESSION['gabinete'];
$usuarioSessao = $_SESSION['id'];


$buscaGabinete = $gabineteController->buscar($gabineteSessao, 'id');
$buscaUsuarios = $usuarioController->listar('nome', 'asc', 100, 1, ['gabinete' => $gabineteSessao]);
$buscaTipo = $tipoGabineteController->buscar($buscaGabinete['data']['tipo'], 'id');


?>

<div class="card mb-2">
    <div class="card-header custom-card-header">
        Área do gestor
    </div>
    <div class="card-body custom-card-body p-2">
        <p class="card-text">Esta área é destinada à gestão do gabinete, incluindo o gerenciamento de usuários, níveis de acesso e dados do gabinete.</p>
    </div>
</div>

<div class="card mb-2">
    <div class="card-body custom-card-body p-2">
        <h5 class="card-title mb-2"><?= $buscaGabinete['data']['nome'] ?> - <?= $buscaGabinete['data']['estado'] ?></h5>
        <p class="card-text">Total de usuários no gabinete: <b><?= count($buscaUsuarios['data']) ?></b></p>
    </div>
</div>

<div class="card mb-2">
    <div class="card-body custom-card-body p-2">
        <p class="card-text mb-2">Dados do gabinete: </p>

        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar_gabinete'])) {

            $dadosGabinete = [
                'cidade' => $_POST['gabinete_municipio']
            ];

            $resultGabinete = $gabineteController->atualizar($gabineteSessao, $dadosGabinete, 'id');

            if ($resultGabinete['status'] == 'success') {
                $buscaGabinete = $gabineteController->buscar($gabineteSessao, 'id');
                echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="3">Gabinete atualizado com sucesso!</div>';
            }
        }

        ?>

        <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
            <div class="col-md-4 col-12">
                <input type="text" class="form-control form-control-sm" name="gabinete_nome" value="<?= $buscaGabinete['data']['nome'] ?>" placeholder="E-mail" readonly required>
            </div>

            <div class="col-md-2 col-12">
                <select class="form-select form-select-sm" name="gabinete_tipo" readonly disabled>
                    <option value="<?= $buscaGabinete['data']['tipo'] ?>" selected><?= $buscaTipo['data']['nome'] ?></option>
                </select>
            </div>

            <div class="col-md-1 col-6">
                <select class="form-select form-select-sm" id="estado" name="gabinete_estado" data-selected="<?= isset($buscaGabinete['data']['estado']) ? $buscaGabinete['data']['estado'] : '' ?>">
                    <option value=" " selected>UF</option>
                </select>
            </div>

            <div class="col-md-2 col-6">
                <select class="form-select form-select-sm" id="municipio" name="gabinete_municipio" data-selected="<?= isset($buscaGabinete['data']['estado']) ? $buscaGabinete['data']['cidade'] : '' ?>">
                    <option value=" " selected>Município</option>
                </select>
            </div>

            <div class="col-md-2 col-12">
                <button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_atualizar_gabinete"><i class="bi bi-floppy-fill"></i> Atualizar</button>
            </div>
        </form>
    </div>
</div>