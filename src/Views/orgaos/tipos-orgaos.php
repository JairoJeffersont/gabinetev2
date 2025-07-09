<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$tipoOrgaoController = new \App\Controllers\OrgaoTipoController();
$usuarioController = new \App\Controllers\UsuarioController()

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
                    <p class="card-text mb-0">Nesta seção, é possível adicionar e editar os tipos de órgãos e entidades, garantindo a organização correta dessas informações no sistema.</p>
                </div>
            </div>
            <div class="card shadow-sm mb-2">
                <div class="card-body custom-card-body p-2">

                    <?php

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                        $dados = [
                            'nome' => $_POST['orgao_tipo_nome'],
                            'gabinete' => $_SESSION['gabinete'],
                            'criado_por' => $_SESSION['id']
                        ];

                        $result = $tipoOrgaoController->inserir($dados);

                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                        }
                    }

                    ?>
                    <form class="row g-2 form_custom" id="form_novo" method="POST">
                        <div class="col-md-2 col-12">
                            <input type="text" class="form-control form-control-sm" name="orgao_tipo_nome" placeholder="Nome do Tipo">
                        </div>
                        <div class="col-md-5 col-12">
                            <button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>
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
                                    <th scope="col">Tipo </th>
                                    <th scope="col">Criado por | Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $buscaTipos = $tipoOrgaoController->listar('nome', 'asc', 1000, 1, ['gabinete' => $_SESSION['gabinete']]);

                                if ($buscaTipos['status'] == 'success') {
                                    foreach ($buscaTipos['data'] as $tipos) {
                                        $usuario = $usuarioController->buscar($tipos['criado_por'])['data']['nome'];
                                        echo '<tr>
                                                <td><a href="?secao=tipo-orgao&tipo=' . $tipos['id'] . '">' . $tipos['nome'] . '</a></td>
                                                <td>' . $usuario . ' | ' . date('d/m', strtotime($tipos['criado_em'])) . '</td>

                                              </tr>';
                                    }
                                } else if ($buscaTipos['status'] == 'empty' || $buscaTipos['status'] == 'server_error') {
                                    echo '<tr><td colspan="2">' . $buscaTipos['message'] . '</td></tr>';
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