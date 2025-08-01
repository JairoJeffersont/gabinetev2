<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

use App\Controllers\CompromissoController;
use App\Controllers\SituacaoCompromissoController;
use App\Controllers\TipoCompromissoController;

$controllerTipoCompromisso = new TipoCompromissoController();
$controllerSituacaoCompromisso = new SituacaoCompromissoController();
$compromissoController = new CompromissoController();

$id = $_GET['id'];

$buscaCompromisso = $compromissoController->buscar($id);

if ($buscaCompromisso['status'] != 'success') {
    header('Location: ?secao=compromissos');
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
                    <a class="btn btn-success custom-card-body btn-sm link_loading" href="?secao=compromissos" role="button"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header">
                    <i class="bi bi-calendar"></i> Editar compromisso
                </div>
                <div class="card-body custom-card-body p-2">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                        $dataInput = $_POST['data'] ?? '';
                        $horaInput = $_POST['hora'] ?? '';

                        $dataFormatada = null;
                        if (!empty($dataInput)) {
                            $partesData = explode('/', $dataInput);

                            if (count($partesData) >= 2) {
                                $dia = str_pad($partesData[0], 2, '0', STR_PAD_LEFT);
                                $mes = str_pad($partesData[1], 2, '0', STR_PAD_LEFT);
                                $ano = count($partesData) === 3 ? $partesData[2] : date('Y');
                                $dataFormatada = $ano . '-' . $mes . '-' . $dia;
                            }
                        }

                        $horaFormatada = !empty($horaInput) ? $horaInput . ':00' : '00:00:00';

                        $data_hora = $dataFormatada . ' ' . $horaFormatada;

                        $dadosForm = [
                            'data'        => date('Y-d-m', strtotime($_POST['data'])) ?? '',
                            'hora'        => $_POST['hora'] ?? '',
                            'titulo'      => $_POST['titulo'] ?? '',
                            'endereco'    => $_POST['endereco'] ?? '',
                            'estado'      => $_POST['estado'] ?? '',
                            'municipio'   => $_POST['municipio'] ?? '',
                            'tipo_id'     => $_POST['tipo'] ?? '',
                            'situacao_id' => $_POST['situacao'] ?? '',
                            'categoria_agenda' => $_POST['categoria_agenda'] ?? '',
                            'descricao'   => $_POST['informacoes'] ?? '',
                            'gabinete'    => $_SESSION['gabinete'],
                            'criado_por'  => $_SESSION['id']
                        ];

                        $result = $compromissoController->atualizar($id, $dadosForm);

                        if ($result['status'] == 'success') {
                            $buscaCompromisso = $compromissoController->buscar($id);
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="5">Já existe um compromisso para essa hora/data.</div>';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . ' - ' . $result['error_id'] . '</div>';
                        }
                    }


                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                        if ($buscaTipo['data']['gabinete'] == '1') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">Você não pode apagar um item padrão do sistema.</div>';
                        } else {
                            $result = $compromissoController->apagar($id);
                            if ($result['status'] == 'success') {
                                header('Location: ?secao=compromissos');
                            } else if ($result['status'] == 'server_error' || $result['status'] == 'forbidden') {
                                echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                    }

                    if ($_SESSION['tipo'] != '1' && $_SESSION['tipo'] != '3') {
                        echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2">Você não tem autorização para inserir ou editar compromissos.</div>';
                    }

                    ?>
                    <form class="row g-2 form_custom" id="form_novo" method="POST">
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" data-mask="00/00/0000" name="data" placeholder="Data (dd/mm/aaaa)" value="<?php echo date('d/m/Y', strtotime($buscaCompromisso['data']['data'])) ?>" required>
                        </div>
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" data-mask="00:00" name="hora" placeholder="Horário (h:m)" value="<?php echo date('H:i', strtotime($buscaCompromisso['data']['hora'])) ?>" required>
                        </div>
                        <div class="col-md-3 col-12">
                            <input type="text" class="form-control form-control-sm" name="titulo" placeholder="Titulo" value="<?php echo $buscaCompromisso['data']['titulo'] ?>" required>
                        </div>
                        <div class="col-md-7 col-12">
                            <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Local" value="<?php echo $buscaCompromisso['data']['endereco'] ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="estado" name="estado" data-selected="<?php echo $buscaCompromisso['data']['estado'] ?>" required>
                                <option value="" selected>UF</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="municipio" name="municipio" data-selected="<?php echo $buscaCompromisso['data']['municipio'] ?>" required>
                                <option value="" selected>Município</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-4">
                            <select class="form-select form-select-sm" name="tipo" required>
                                <?php
                                $buscaTipo = $controllerTipoCompromisso->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                if ($buscaTipo['status'] == 'success') {
                                    foreach ($buscaTipo['data'] as $tipo) {
                                        if ($tipo['id'] == $buscaCompromisso['data']['tipo_id']) {
                                            echo '<option value="' . $tipo['id'] . '" selected>' . $tipo['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $tipo['id'] . '">' . $tipo['nome'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-4">
                            <select class="form-select form-select-sm" name="situacao" required>
                                <?php
                                $buscaSituacao = $controllerSituacaoCompromisso->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                if ($buscaSituacao['status'] == 'success') {
                                    foreach ($buscaSituacao['data'] as $situacao) {
                                        if ($situacao['id'] == $buscaCompromisso['data']['situacao_id']) {
                                            echo '<option value="' . $situacao['id'] . '" selected>' . $situacao['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $situacao['id'] . '">' . $situacao['nome'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-4">
                            <select class="form-select form-select-sm" name="categoria_agenda" required>
                                <option value="Gabinete" <?php echo ($buscaCompromisso['data']['categoria_agenda'] == 'Gabinete') ? 'selected' : ''; ?>>Parlamentar</option>
                                <option value="Particular" <?php echo ($buscaCompromisso['data']['categoria_agenda'] == 'Particular') ? 'selected' : ''; ?>>Particular</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" id="informacoes" name="informacoes" placeholder="Informações Adicionais." rows="5"><?php echo $buscaCompromisso['data']['descricao'] ?></textarea>
                        </div>
                        <div class="col-md-3 col-12">
                            <?php
                            if ($_SESSION['tipo'] == '1' || $_SESSION['tipo'] == '3') {
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