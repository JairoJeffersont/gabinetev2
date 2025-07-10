<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$tipoOrgaoController = new \App\Controllers\OrgaoTipoController();
$usuarioController = new \App\Controllers\UsuarioController();
$orgaoController = new \App\Controllers\OrgaoController();
$gabineteController = new \App\Controllers\GabineteController();

$estadogabinete = $gabineteController->buscar($_SESSION['gabinete'])['data']['estado'];

$orgaoGet = isset($_GET['id']) ? $_GET['id'] : '';

$buscaOrgao = $orgaoController->buscar($orgaoGet);

if ($buscaOrgao['status'] != 'success') {
    header('Location: ?secao=orgaos');
}
?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary custom-card-body btn-sm" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
                    <a class="btn btn-success custom-card-body btn-sm" href="?secao=orgaos" role="button"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header"><i class="bi bi-building"></i> Editar órgão/entidade</div>
                <div class="card-body custom-card-body p-2">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                        $dados = [
                            'nome' => $_POST['nome'] ?? '',
                            'email' => $_POST['email'] ?? '',
                            'telefone' => $_POST['telefone'] ?? '',
                            'endereco' => $_POST['endereco'] ?? '',
                            'cep' => $_POST['cep'] ?? '',
                            'estado' => $_POST['estado'] ?? '',
                            'municipio' => $_POST['municipio'] ?? '',
                            'tipo_id' => $_POST['tipo_id'] ?? '',
                            'site' => $_POST['site'] ?? '',
                            'instagram' => $_POST['instagram'] ?? '',
                            'twitter' => $_POST['twitter'] ?? '',
                            'informacoes' => $_POST['informacoes'] ?? ''
                        ];

                        $result = $orgaoController->atualizar($orgaoGet, $dados);

                        if ($result['status'] == 'success') {
                            $buscaOrgao = $orgaoController->buscar($orgaoGet);
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . ' - ' . $result['error_id'] . '</div>';
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                        $result = $orgaoController->apagar($orgaoGet);

                        if ($result['status'] == 'success') {
                            header('Location: ?secao=orgaos');
                        } else if ($result['status'] == 'server_error' || $result['status'] == 'forbidden') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                        }
                    }

                    if ($_SESSION['tipo'] != '1' && $_SESSION['tipo'] != '3') {
                        echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">Você não tem autorização para apagar órgãos/entidades.</div>';
                    }
                    ?>


                    <form class="row g-2 form_custom " id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                        <div class="col-md-5 col-12">
                            <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" value="<?= $buscaOrgao['data']['nome'] ?>" required>
                        </div>
                        <div class="col-md-4 col-6">
                            <input type="text" class="form-control form-control-sm" name="email" placeholder="Email" value="<?= $buscaOrgao['data']['email'] ?>">
                        </div>
                        <div class="col-md-3 col-6">
                            <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone (somente números)" data-mask="(00) 00000-0000" maxlength="15" value="<?= $buscaOrgao['data']['telefone'] ?>">
                        </div>
                        <div class="col-md-6 col-12">
                            <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço" value="<?= $buscaOrgao['data']['endereco'] ?>">
                        </div>

                        <div class="col-md-2 col-12">
                            <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (somente números)" data-mask="00000-000" maxlength="8" value="<?= $buscaOrgao['data']['cep'] ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="estado" name="estado" required data-selected="<?= $buscaOrgao['data']['estado'] ?>">
                                <option value="" selected>UF</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="municipio" name="municipio" required data-selected="<?= $buscaOrgao['data']['municipio'] ?>">
                                <option value="" selected>Município</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" id="tipo_id" name="tipo_id" required>
                                    <?php
                                    $buscaTipos = $tipoOrgaoController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaTipos['status'] == 'success') {
                                        foreach ($buscaTipos['data'] as $tipos) {
                                            if ($tipos['id'] == $buscaOrgao['data']['tipo_id']) {
                                                echo '<option value="' . $tipos['id'] . '" selected>' . $tipos['nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $tipos['id'] . '">' . $tipos['nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <a href="?secao=tipos-orgaos" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo tipo" onclick="abrirModalNovoTipo()">
                                    <i class="bi bi-plus"></i> novo tipo
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3 col-4">
                            <input type="text" class="form-control form-control-sm" name="site" placeholder="Site" value="<?= $buscaOrgao['data']['site'] ?>">
                        </div>
                        <div class="col-md-3 col-4">
                            <input type="text" class="form-control form-control-sm" name="instagram" placeholder="Instagram" value="<?= $buscaOrgao['data']['instagram'] ?>">
                        </div>
                        <div class="col-md-3 col-4">
                            <input type="text" class="form-control form-control-sm" name="twitter" placeholder="X (Twitter)" value="<?= $buscaOrgao['data']['twitter'] ?>">
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes desse órgão"><?= $buscaOrgao['data']['informacoes'] ?></textarea>
                        </div>
                        <div class="col-md-4 col-6">
                            <button type="submit" class="btn btn-success confirm-action  btn-sm" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Atualizar</button>
                            <?php
                            if ($_SESSION['tipo'] == '1' || $_SESSION['tipo'] == '3') {
                                echo '<button type="submit" class="btn btn-danger btn-sm confirm-action" name="btn_apagar"><i class="bi bi-trash"></i> Apagar</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-danger btn-sm confirm-action" disabled name="btn_apagar"><i class="bi bi-trash"></i> Apagar</button>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>