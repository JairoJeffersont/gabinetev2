<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$EmendaController = new \App\Controllers\EmendaController();
$EmendaObjetivoController = new \App\Controllers\ObjetivoEmendaController();
$EmendaSituacaoController = new \App\Controllers\SituacaoEmendaController();
$usuarioController = new \App\Controllers\UsuarioController();
$gabineteController = new \App\Controllers\GabineteController();
$estadogabinete = $gabineteController->buscar($_SESSION['gabinete'])['data']['estado'];

$ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y');
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'Parlamentar';
$objetivoGet = isset($_GET['objetivo']) ? $_GET['objetivo'] : '0';
$situacaoGet = isset($_GET['situacao']) ? $_GET['situacao'] : '0';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '0';
$municipio = isset($_GET['municipio']) && !empty($_GET['municipio']) ? $_GET['municipio'] : '0';
$ordenarPor = isset($_GET['ordenarPor']) ? $_GET['ordenarPor'] : 'numero';
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'desc';

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
                    <i class="bi bi-cash-stack"></i> Controle de Emendas Parlamentares
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
                            'informacoes'  => $_POST['informacoes'] ?? '',
                            'gabinete' => $_SESSION['gabinete'],
                            'criado_por' => $_SESSION['id']
                        ];

                        $result = $EmendaController->inserir($dadosEmenda);

                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        } else {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" data-timeout="2">' . $result['message'] . '</div>';
                        }
                    }

                    if ($_SESSION['tipo'] != '1' && $_SESSION['tipo'] != '5') {
                        echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2">Você não tem autorização para inserir ou editar emendas.</div>';
                    }
                    ?>

                    <form class="row g-2 form_custom" method="POST">
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" name="ano" placeholder="Ano" value="<?= date('Y') ?>" required>
                        </div>
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" id="emenda_numero" name="numero" placeholder="Número da Emenda" maxlength="10" required>
                        </div>

                        <div class="col-md-2 col-12">
                            <input type="text" class="form-control form-control-sm" name="valor" id="emenda_valor" placeholder="Valor da Emenda (R$)" required>
                        </div>
                        <div class="col-md-4 col-6">
                            <select class="form-select form-select-sm" id="estado" name="estado" required>
                                <option value="" selected>UF</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                            <select class="form-select form-select-sm" id="municipio" name="municipio" required>
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
                                            if ($situacao['id'] == '1') {
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
                                            if ($objetivo['id'] == '1') {
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
                                <option value="Parlamentar">Emenda parlamentar</option>
                                <option value="Bancada">Emenda de bancada</option>
                                <option value="Extra">Emenda extra</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" name="objeto" placeholder="Objeto da emenda. Ex. Compra de insumos hospitalares..." rows="3" required></textarea>
                        </div>
                        <div class="col-md-12 col-12">
                            <style>
                                .tox {
                                    font-size: 12px !important;
                                }
                            </style>
                            <script>
                                tinymce.init({
                                    selector: '#informacoes',
                                    language: 'pt_BR',
                                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    setup: function(editor) {
                                        editor.on('change', function() {
                                            tinymce.triggerSave();
                                        });
                                    }
                                });
                            </script>
                            <textarea class="form-control form-control-sm" id="informacoes" name="informacoes" placeholder="Informações Adicionais. Ex. Ordem de pagamento, códigos gerais..." rows="5" required></textarea>
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
            <div class="card mb-2 ">
                <div class="card-body custom-card-body p-2">
                    <form class="row g-2 form_custom mb-0" action="" method="GET" enctype="application/x-www-form-urlencoded">
                        <input type="hidden" name="secao" value="emendas" />
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" name="ano" value="<?php echo $ano; ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="tipo" required>
                                <option value="Parlamentar" <?php echo ($tipo == 'Parlamentar') ? 'selected' : ''; ?>>Emenda Parlamentar</option>
                                <option value="Bancada" <?php echo ($tipo == 'Bancada') ? 'selected' : ''; ?>>Emenda de Bancada</option>
                                <option value="Extra" <?php echo ($tipo == 'Extra') ? 'selected' : ''; ?>>Emenda Extra</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="situacao" required>
                                <option value="0" <?php echo ($situacaoGet == '0') ? 'selected' : ''; ?>>Todas as situações</option>
                                <?php
                                $buscaSituacao = $EmendaSituacaoController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                if ($buscaSituacao['status'] == 'success') {
                                    foreach ($buscaSituacao['data'] as $situacao) {
                                        if ($situacao['id'] == $situacaoGet) {
                                            echo '<option value="' . $situacao['id'] . '" selected>' . $situacao['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $situacao['id'] . '">' . $situacao['nome'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="objetivo" required>
                                <option value="0" <?php echo ($situacaoGet == '0') ? 'selected' : ''; ?>>Todos os objetivos</option>
                                <?php
                                $buscaObjetivo = $EmendaObjetivoController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                if ($buscaObjetivo['status'] == 'success') {
                                    foreach ($buscaObjetivo['data'] as $objetivo) {
                                        if ($objetivo['id'] == $objetivoGet) {
                                            echo '<option value="' . $objetivo['id'] . '" selected>' . $objetivo['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $objetivo['id'] . '">' . $objetivo['nome'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-1 col-6">
                            <select class="form-select form-select-sm" name="ordenarPor">
                                <option value="numero" <?php echo ($ordenarPor == 'Número') ? 'selected' : ''; ?>>Número</option>
                                <option value="valor" <?php echo ($ordenarPor == 'valor') ? 'selected' : ''; ?>>Valor</option>
                                <option value="municipio" <?php echo ($ordenarPor == 'municipio') ? 'selected' : ''; ?>>Município</option>
                            </select>
                        </div>
                        <div class="col-md-1 col-6">
                            <select class="form-select form-select-sm" name="ordem">
                                <option value="asc" <?php echo ($ordem == 'asc') ? 'selected' : ''; ?>>Crescente</option>
                                <option value="desc" <?php echo ($ordem == 'desc') ? 'selected' : ''; ?>>Decrescente</option>
                            </select>
                        </div>

                        <div class="col-md-1 col-12">
                            <button type="submit" class="btn btn-primary btn-sm w-100 w-md-auto">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body custom-card-body p-2">
                    <div class="table-responsive">
                        <?php
                        if ($estado == $estadogabinete) {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="0">Mostrando somente emendas para o estado (' . $estadogabinete . ').</div>';
                        }
                        ?>
                        <table class="table table-hover table-bordered table-striped mb-0">
                            <tbody>
                                <table class="table table-hover table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Número</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Objeto</th>
                                            <th scope="col">Objetivo</th>
                                            <th scope="col">Situação</th>
                                            <th scope="col">Município/UF</th>
                                            <th scope="col">Criado por | Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $condicoes = [
                                            'gabinete' => $_SESSION['gabinete'],
                                            'ano' => $ano,
                                            'tipo' => $tipo
                                        ];

                                        if ($objetivoGet != '0') {
                                            $condicoes['objetivo_id'] = $objetivoGet;
                                        }

                                        if ($situacaoGet !== '0') {
                                            $condicoes['situacao_id'] = $situacaoGet;
                                        }

                                        if ($municipio != '0') {
                                            $condicoes['municipio'] = $municipio;
                                        }

                                        if ($estado != '0') {
                                            $condicoes['estado'] = $estado;
                                        }


                                        $lista = $EmendaController->listar($ordenarPor, $ordem, 1000, 1, $condicoes, 'AND');

                                        $totalGeral = 0;

                                        if ($lista['status'] == 'success') {
                                            // Agrupa por número
                                            $agrupado = [];
                                            foreach ($lista['data'] as $item) {
                                                $agrupado[$item['numero']][] = $item;
                                            }

                                            // Gera linhas
                                            foreach ($agrupado as $numero => $itens) {

                                                usort($itens, function ($a, $b) {
                                                    return $b['valor'] <=> $a['valor'];
                                                });

                                                $rowspan = count($itens);
                                                $primeira = true;

                                                foreach ($itens as $item) {
                                                    $usuario = $usuarioController->buscar($item['criado_por'])['data']['nome'];
                                                    $objetivo = $EmendaObjetivoController->buscar($item['objetivo_id'])['data']['nome'];
                                                    $situacao = $EmendaSituacaoController->buscar($item['situacao_id'])['data']['nome'];

                                                    $totalGeral += $item['valor'];

                                                    echo '<tr>';

                                                    if ($primeira) {
                                                        echo '<td rowspan="' . $rowspan . '" style="text-align:start; vertical-align:top; font-size:1.2em">
                                                                <b>' . $numero . '/' . $item['ano'] . '</b>
                                                            </td>';
                                                        $primeira = false;
                                                    }

                                                    echo '  <td style="white-space: nowrap;"><a href="?secao=emenda&id=' . $item['id'] . '" class="link_loading">R$ ' . number_format($item['valor'], 2, ',', '.') . '</a></td>
                                                            <td style="white-space: nowrap;">' . $item['objeto'] . '</td>
                                                            <td style="white-space: nowrap;">' . $objetivo . '</td>
                                                            <td style="white-space: nowrap;"><b>' . $situacao . '</b></td>
                                                            <td style="white-space: nowrap;">' . $item['municipio'] . '/' . $item['estado'] . '</td>
                                                            <td style="white-space: nowrap;">' . $usuario . ' | ' . date('d/m', strtotime($item['criado_em'])) . '</td>
                                                            </tr>';
                                                }
                                            }
                                        } else if ($lista['status'] == 'empty') {
                                            echo '<tr><td colspan="7">' . $lista['message'] . '</td></tr>';
                                        } else if ($lista['status'] == 'server_error') {
                                            echo '<tr><td colspan="7">' . $lista['message'] . ' | ' . $lista['error_id'] . '</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body p-1">
                    <h6 class="card-title px-2 mb-0">R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>