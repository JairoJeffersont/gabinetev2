<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$situacaoEmendaController = new \App\Controllers\SituacaoEmendaController();

$situacaoGet = (isset($_GET['id'])) ? $_GET['id'] : null;

$buscaSituacao = $situacaoEmendaController->buscar($situacaoGet);

if ($buscaSituacao['status'] != 'success') {
    header('Location: ?secao=emendas-status');
}

?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary custom-card-body btn-sm link_loading" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
                    <a class="btn btn-success custom-card-body btn-sm link_loading" href="?secao=emendas-status" role="button"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header"><i class="bi bi-list-check"></i> Editar Situação de Emenda</div>
                <div class="card-body custom-card-body p-2">

                    <?php

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                        $dados = [
                            'nome' => $_POST['situacao_nome']
                        ];


                        if ($buscaSituacao['data']['gabinete'] == '1') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">Você não pode editar um item padrão do sistema.</div>';
                        } else {
                            $result = $situacaoEmendaController->atualizar($situacaoGet, $dados);

                            if ($result['status'] == 'success') {
                                $buscaSituacao = $objetivoEmendaController->buscar($objetivoGet);
                                echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                            } else if ($result['status'] == 'duplicated') {
                                echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                            } else if ($result['status'] == 'server_error') {
                                echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {

                        if ($buscaSituacao['data']['gabinete'] == '1') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">Você não pode apagar um item padrão do sistema.</div>';
                        } else {
                            $result = $situacaoEmendaController->apagar($situacaoGet);

                            if ($result['status'] == 'success') {
                                header('Location: ?secao=emendas-status');
                            } else if ($result['status'] == 'server_error' || $result['status'] == 'forbidden') {
                                echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                    }

                    if ($_SESSION['tipo'] != '1' && $_SESSION['tipo'] != '5') {
                        echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">Você não tem autorização para inserir ou editar situações.</div>';
                    }

                    ?>
                    <form class="row g-2 form_custom" id="form_editar" method="POST">
                        <div class="col-md-4 col-12">
                            <input type="text" class="form-control form-control-sm" name="situacao_nome" placeholder="Nome da Situação" value="<?= $buscaSituacao['data']['nome'] ?>" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <?php
                            if ($_SESSION['tipo'] == '1' || $_SESSION['tipo'] == '5') {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Atualizar</button>&nbsp;';
                                echo '<button type="submit" class="btn btn-danger btn-sm confirm-action" name="btn_apagar"><i class="bi bi-trash"></i> Apagar</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" disabled name="btn_salvar"><i class="bi bi-floppy-fill"></i> Atualizar</button>&nbsp;';
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