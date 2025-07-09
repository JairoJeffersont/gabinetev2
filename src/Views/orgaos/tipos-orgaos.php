<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$tipoOrgaoController = new \App\Controllers\OrgaoTipoController();

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
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header"><i class="bi bi-building"></i> Adicionar tipo de Órgão/Entidade</div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-2">Nesta seção, é possível adicionar e editar os tipos de órgãos e entidades, garantindo a organização correta dessas informações no sistema.</p>
                    <p class="card-text mb-0">Todos os campos são obrigatórios</p>
                </div>
            </div>
            <div class="card shadow-sm mb-2">
                <div class="card-body custom-card-body p-2">
                    <form class="row g-2 form_custom" id="form_novo" method="POST">
                        <div class="col-md-2 col-12">
                            <input type="text" class="form-control form-control-sm" name="orgao_tipo_nome" placeholder="Nome do Tipo" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <input type="text" class="form-control form-control-sm" name="orgao_tipo_descricao" placeholder="Descrição" required>
                        </div>
                        <div class="col-md-1 col-12">
                            <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm mb-2">
                <div class="card-body custom-card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $buscaTipos = $tipoOrgaoController->listar('nome', 'asc', 1000, 1, ['gabinete' => $_SESSION['gabinete']]);

                                if ($buscaTipos['status'] == 'success') {
                                    foreach ($buscaTipos['data'] as $tipos) {
                                        echo '<tr><td><a href="?secao=tipo-orgao?tipo=' . $tipos['id'] . '">' . $tipos['nome'] . '</a></td></tr>';
                                    }
                                } else if ($buscaTipos['status'] == 'empty' || $buscaTipos['status'] == 'server_error') {
                                    echo '<tr><td colspan="1">' . $buscaTipos['message'] . '</td></tr>';
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>