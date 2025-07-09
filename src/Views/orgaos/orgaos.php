<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$tipoOrgaoController = new \App\Controllers\OrgaoTipoController();
$usuarioController = new \App\Controllers\UsuarioController();
$orgaoController = new \App\Controllers\OrgaoController();

?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary custom-card-body btn-sm" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header"><i class="bi bi-building"></i> Órgãos e Entidades</div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-0">Nesta seção, é possível gerenciar órgãos ou entidades, garantindo a organização correta dessas informações no sistema.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-2">
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
                            'informacoes' => $_POST['informacoes'] ?? '',
                            'criado_por' => $_SESSION['id'],
                            'gabinete' => $_SESSION['gabinete']
                        ];

                        $result = $orgaoController->inserir($dados);

                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . ' - ' . $result['error_id'] . '</div>';
                        }
                    }

                    ?>


                    <form class="row g-2 form_custom " id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                        <div class="col-md-5 col-12">
                            <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" required>
                        </div>
                        <div class="col-md-4 col-6">
                            <input type="text" class="form-control form-control-sm" name="email" placeholder="Email">
                        </div>
                        <div class="col-md-3 col-6">
                            <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone (somente números)" data-mask="(00) 00000-0000" maxlength="15">
                        </div>
                        <div class="col-md-6 col-12">
                            <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço ">
                        </div>

                        <div class="col-md-2 col-12">
                            <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (somente números)" data-mask="00000-000" maxlength="8">
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="estado" name="estado" required>
                                <option value="" selected>UF</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="municipio" name="municipio" required>
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
                                            if ($tipos['id'] == 1) {
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
                            <input type="text" class="form-control form-control-sm" name="site" placeholder="Site">
                        </div>
                        <div class="col-md-3 col-4">
                            <input type="text" class="form-control form-control-sm" name="instagram" placeholder="Instagram">
                        </div>
                        <div class="col-md-3 col-4">
                            <input type="text" class="form-control form-control-sm" name="twitter" placeholder="X (Twitter)">
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes desse órgão"></textarea>
                        </div>
                        <div class="col-md-4 col-6">
                            <button type="submit" class="btn btn-success confirm-action  btn-sm" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>