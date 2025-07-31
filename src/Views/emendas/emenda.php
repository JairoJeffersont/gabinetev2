<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$EmendaController = new \App\Controllers\EmendaController();
$EmendaObjetivoController = new \App\Controllers\ObjetivoEmendaController();
$EmendaSituacaoController = new \App\Controllers\SituacaoEmendaController();
$usuarioController = new \App\Controllers\UsuarioController();



$id = isset($_GET['id']) ? $_GET['id'] : '0';

$buscaEmenda = $EmendaController->buscar($id, 'id');

if ($buscaEmenda['status'] != 'success') {
    header('Location: ?secao=emendas');
}

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
                    <a class="btn btn-success custom-card-body btn-sm link_loading" href="?secao=emendas" role="button">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header">
                    <i class="bi bi-cash-stack"></i> Editar Emenda
                </div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-0">
                        Nesta seção, você pode gerenciar e controlar os objetivos das emendas parlamentares cadastradas no sistema.
                    </p>
                </div>

            </div>
            <div class="card mb-2">
                <div class="card-body custom-card-body p-2">

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $dadosEmenda = [
                            'ano'          => $_POST['ano'] ?? '',
                            'numero'       => $_POST['numero'] ?? '',
                            'valor'        => str_replace(',', '.', str_replace('.', '', $_POST['valor'])) ?? '',
                            'estado'       => $_POST['estado'] ?? '',
                            'municipio'    => $_POST['municipio'] ?? '',
                            'situacao_id'  => $_POST['situacao_id'] ?? '',
                            'objetivo_id'  => $_POST['objetivo_id'] ?? '',
                            'tipo'         => $_POST['tipo'] ?? '',
                            'objeto'       => $_POST['objeto'] ?? '',
                            'informacoes'  => $_POST['informacoes'] ?? ''
                        ];

                        $result = $EmendaController->atualizar($id, $dadosEmenda, 'id');

                        if ($result['status'] == 'success') {
                            $buscaEmenda = $EmendaController->buscar($id, 'id');
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        } else {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        }
                    }


                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                        $result = $EmendaController->apagar($id);

                        if ($result['status'] == 'success') {
                            header('Location: ?secao=emendas');
                        } else if ($result['status'] == 'server_error' || $result['status'] == 'forbidden') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                        }
                    }

                    ?>

                    <form class="row g-2 form_custom" method="POST">
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" name="ano" placeholder="Ano" value="<?= $buscaEmenda['data']['ano'] ?>" required>
                        </div>
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" id="emenda_numero" name="numero" placeholder="Número da Emenda" value="<?= $buscaEmenda['data']['numero'] ?>" maxlength="10" required>
                        </div>

                        <div class="col-md-2 col-12">
                            <input type="text" class="form-control form-control-sm" name="valor" id="emenda_valor" placeholder="Valor da Emenda (R$)" value="<?= $buscaEmenda['data']['valor'] ?>" required>
                        </div>
                        <div class="col-md-4 col-6">
                            <select class="form-select form-select-sm" id="estado" name="estado" data-selected="<?= $buscaEmenda['data']['estado'] ?>" required>
                                <option value="" selected>UF</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                            <select class="form-select form-select-sm" id="municipio" name="municipio" data-selected="<?= $buscaEmenda['data']['municipio'] ?>" required>
                                <option value="" selected>Município</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="input-group input-group-sm">

                                <select class="form-select form-select-sm" name="situacao_id" required>
                                    <?php
                                    $buscaSituacao = $EmendaSituacaoController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaSituacao['status'] == 'success') {
                                        foreach ($buscaSituacao['data'] as $situacao) {
                                            if ($situacao['id'] == $buscaEmenda['data']['situacao_id']) {
                                                echo '<option value="' . $situacao['id'] . '" selected>' . $situacao['nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $situacao['id'] . '">' . $situacao['nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <a href="?secao=emendas-status" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo tipo">
                                    <i class="bi bi-plus"></i> novo tipo
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" name="objetivo_id" required>
                                    <?php
                                    $buscaObjetivo = $EmendaObjetivoController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaObjetivo['status'] == 'success') {
                                        foreach ($buscaObjetivo['data'] as $objetivo) {
                                            if ($objetivo['id'] == $buscaEmenda['data']['objetivo_id']) {
                                                echo '<option value="' . $objetivo['id'] . '" selected>' . $objetivo['nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $objetivo['id'] . '">' . $objetivo['nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <a href="?secao=emendas-objetivos" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo tipo">
                                    <i class="bi bi-plus"></i> novo objetivo
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <select class="form-select form-select-sm" name="tipo" required>
                                <option value="Parlamentar" <?php echo ($buscaEmenda['data']['tipo'] == 'Parlamentar') ? 'selected' : ''; ?>>Emenda Parlamentar</option>
                                <option value="Bancada" <?php echo ($buscaEmenda['data']['tipo'] == 'Bancada') ? 'selected' : ''; ?>>Emenda de Bancada</option>
                                <option value="Extra" <?php echo ($buscaEmenda['data']['tipo'] == 'Extra') ? 'selected' : ''; ?>>Emenda Extra</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" name="objeto" placeholder="Objeto da emenda. Ex. Compra de insumos hospitalares..." rows="3" required><?= $buscaEmenda['data']['objeto'] ?></textarea>
                        </div>
                        <div class="col-md-12 col-12">
                            <style>
                                .tox {
                                    font-size: 12px !important;
                                }
                            </style>
                            <script>
                                /*tinymce.init({
                                    selector: '#informacoes',
                                    language: 'pt_BR',
                                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    setup: function(editor) {
                                        editor.on('change', function() {
                                            tinymce.triggerSave();
                                        });
                                    }
                                });*/
                            </script>
                            <textarea class="form-control form-control-sm" id="informacoes" name="informacoes" placeholder="Informações Adicionais. Ex. Ordem de pagamento, códigos gerais..." rows="5" required><?= $buscaEmenda['data']['informacoes'] ?></textarea>
                        </div>
                        <div class="col-md-3 col-12">
                            <?php
                            if ($_SESSION['tipo'] == '1' || $_SESSION['tipo'] == '5') {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Atualizar</button>&nbsp;';
                                echo '<button type="submit" class="btn btn-danger btn-sm confirm-action" name="btn_apagar"><i class="bi bi-trash-fill"></i> Apagar</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar" disabled><i class="bi bi-floppy-fill"></i> Atualizar</button>&nbsp;';
                                echo '<button type="submit" class="btn btn-danger btn-sm confirm-action" name="btn_apagar" disabled><i class="bi bi-trash-fill"></i> Apagar</button>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>