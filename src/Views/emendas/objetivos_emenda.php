<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$objetivoEmendaController = new \App\Controllers\ObjetivoEmendaController();
$usuarioController = new \App\Controllers\UsuarioController();

?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <div class="card mb-2">
                <div class="card-body p-1">
                    <a class="btn btn-primary custom-card-body btn-sm link_loading" href="?secao=home" role="button">
                        <i class="bi bi-house-door-fill"></i> Início
                    </a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header">
                    <i class="bi bi-bullseye"></i> Adicionar Objetivo de Emenda
                </div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-0">
                        Aqui você pode adicionar e gerenciar os objetivos de emendas cadastradas no sistema.
                    </p>
                </div>
            </div>
            <div class="card shadow-sm mb-2">
                <div class="card-body custom-card-body p-2">

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                        $dados = [
                            'nome' => $_POST['objetivo_nome'],
                            'descricao' => $_POST['objetivo_descricao'],
                            'gabinete' => $_SESSION['gabinete'],
                            'criado_por' => $_SESSION['id']
                        ];

                        $result = $objetivoEmendaController->inserir($dados);

                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        } else {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        }
                    }

                    if ($_SESSION['tipo'] != '1' && $_SESSION['tipo'] != '5') {
                        echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2">Você não tem autorização para inserir ou editar objetivos.</div>';
                    }
                    ?>

                    <form class="row g-2 form_custom" method="POST">
                        <div class="col-md-3 col-12">
                            <input type="text" class="form-control form-control-sm" name="objetivo_nome" placeholder="Nome do Objetivo" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <input type="text" class="form-control form-control-sm" name="objetivo_descricao" placeholder="Descrição">
                        </div>
                        <div class="col-md-3 col-12">
                            <?php
                            if ($_SESSION['tipo'] == '1' || $_SESSION['tipo'] == '5') {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" disabled><i class="bi bi-floppy-fill"></i> Salvar</button>';
                            }
                            ?>
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
                                    <th scope="col">Objetivo</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Criado por | Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista = $objetivoEmendaController->listar('nome', 'asc', 1000, 1, ['gabinete' => $_SESSION['gabinete']]);

                                if ($lista['status'] == 'success') {
                                    foreach ($lista['data'] as $item) {
                                        $usuario = $usuarioController->buscar($item['criado_por'])['data']['nome'];
                                        echo '<tr>
                                                <td><a href="?secao=emenda-objetivo&id=' . $item['id'] . '">' . $item['nome'] . '</a></td>
                                                <td>' . $item['descricao'] . '</td>
                                                <td>' . $usuario . ' | ' . date('d/m', strtotime($item['criado_em'])) . '</td>
                                              </tr>';
                                    }
                                } else if ($lista['status'] == 'empty') {
                                    echo '<tr><td colspan="3">' . $lista['message'] . '</td></tr>';
                                } else {
                                    echo '<tr><td colspan="3">' . $lista['message'] . '</td></tr>';
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