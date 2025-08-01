<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

use App\Controllers\CompromissoController;
use App\Controllers\SituacaoCompromissoController;
use App\Controllers\TipoCompromissoController;

$controllerTipoCompromisso = new TipoCompromissoController();
$controllerSituacaoCompromisso = new SituacaoCompromissoController();
$compromissoController = new CompromissoController();

$data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
$tipoGet = isset($_GET['tipo']) ? $_GET['tipo'] : '0';
$situacaoGet = isset($_GET['situacao']) ? $_GET['situacao'] : '0';
$categoriaGet = isset($_GET['categoria']) ? $_GET['categoria'] : '0';

$dias = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
$indiceDia = date('w', strtotime($data));
$diaSemana = $dias[$indiceDia];

?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary custom-card-body btn-sm link_loading" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header">
                    <i class="bi bi-calendar"></i> Gerenciar compromissos
                </div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-0">
                        Nesta seção, é possível gerenciar os compromissos do gabinete, garantindo a organização e o acompanhamento adequado das agendas.
                    </p>
                </div>
            </div>

            <div class="card mb-2">
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
                            'data_hora'   => $data_hora,
                            'titulo'      => $_POST['titulo'] ?? '',
                            'endereco'    => $_POST['endereco'] ?? '',
                            'estado'      => $_POST['estado'] ?? '',
                            'municipio'   => $_POST['municipio'] ?? '',
                            'tipo_id'     => $_POST['tipo'] ?? '',
                            'situacao_id' => $_POST['situacao'] ?? '',
                            'informacoes' => $_POST['informacoes'] ?? '',
                            'gabinete'    => $_SESSION['gabinete'],
                            'criado_por'  => $_SESSION['id']
                        ];

                        $result = $compromissoController->inserir($dadosForm);

                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="5">Já existe um compromisso para essa hora/data.</div>';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . ' - ' . $result['error_id'] . '</div>';
                        }
                    }
                    ?>

                    <form class="row g-2 form_custom" id="form_novo" method="POST">
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" data-mask="00/00/0000" name="data" placeholder="Data (dd/mm/aaaa)" required>
                        </div>
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" data-mask="00:00" name="hora" placeholder="Horário (h:m)" required>
                        </div>
                        <div class="col-md-3 col-12">
                            <input type="text" class="form-control form-control-sm" name="titulo" placeholder="Titulo" required>
                        </div>
                        <div class="col-md-7 col-12">
                            <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Local">
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
                        <div class="col-md-2 col-4">
                            <select class="form-select form-select-sm" name="tipo" required>
                                <?php
                                $buscaTipo = $controllerTipoCompromisso->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                if ($buscaTipo['status'] == 'success') {
                                    foreach ($buscaTipo['data'] as $tipo) {
                                        if ($tipo['id'] == '1') {
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
                                        if ($situacao['id'] == '1') {
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
                                <option value="Parlamentar" selected>Parlamentar</option>
                                <option value="Pessoal">Pessoal</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" id="informacoes" name="informacoes" placeholder="Informações Adicionais." rows="5"></textarea>
                        </div>
                        <div class="col-md-3 col-12">
                            <?php
                            if ($_SESSION['tipo'] == '1' || $_SESSION['tipo'] == '3') {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-2 ">
                <div class="card-body custom-card-body p-2">
                    <form class="row g-2 form_custom mb-0" action="" method="GET" enctype="application/x-www-form-urlencoded">
                        <input type="hidden" name="secao" value="compromissos" />
                        <div class="col-md-1 col-6">
                            <input type="date" class="form-control form-control-sm" name="data" placeholder="Data (dd/mm/aaaa)" value="<?php echo $data ?>" required>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="categoria" required>
                                <option value="0" <?php echo ($categoriaGet == '0') ? 'selected' : ''; ?>>Todas</option>
                                <option value="Parlamentar" <?php echo ($categoriaGet == 'Parlamentar') ? 'selected' : ''; ?>>Parlamentar</option>
                                <option value="Pessoal" <?php echo ($categoriaGet == 'Pessoal') ? 'selected' : ''; ?>>Pessoal</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="tipo" required>
                                <option value="0" <?php echo ($tipoGet == '0') ? 'selected' : ''; ?>>Todas os tipos</option>
                                <?php
                                if ($buscaTipo['status'] == 'success') {
                                    foreach ($buscaTipo['data'] as $tipo) {
                                        if ($tipo['id'] == $tipoGet) {
                                            echo '<option value="' . $tipo['id'] . '" selected>' . $tipo['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $tipo['id'] . '">' . $tipo['nome'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="situacao" required>
                                <option value="0" <?php echo ($situacaoGet == '0') ? 'selected' : ''; ?>>Todas as situações</option>
                                <?php
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
                        <div class="col-md-1 col-12">
                            <button type="submit" class="btn btn-primary btn-sm w-100 w-md-auto">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-2 ">
                <div class="card-header bg-primary text-white px-2 py-1" style="font-size: 0.9em;">
                    <i class="bi bi-calendar"></i> &nbsp;<?php echo $diaSemana . ', ' . date('d/m/Y', strtotime($data)); ?>
                </div>
                <div class="card-body p-1">
                    <div class="list-group">

                        <?php

                        $condicoes = [
                            'gabinete'   => $_SESSION['gabinete'],
                            'data_hora'  => ['LIKE' => $data . '%']
                        ];

                        if ($tipoGet != '0') {
                            $condicoes['tipo_id'] = $tipoGet;
                        }

                        if ($situacaoGet != '0') {
                            $condicoes['situacao_id'] = $situacaoGet;
                        }

                        if ($categoriaGet != '0') {
                            $condicoes['categoria_agenda'] = $categoriaGet;
                        }

                        $buscaEvento = $compromissoController->listar('data_hora', 'DESC', 1000, 1, $condicoes, 'AND');

                        if ($buscaEvento['status'] == 'success') {
                            foreach ($buscaEvento['data'] as $evento) {
                                $situacao = $controllerSituacaoCompromisso->buscar($evento['situacao_id'], 'id')['data']['nome'];
                                echo '<a href="?secao=compromisso&id=' . $evento['id'] . '" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-2"><i class="bi bi-clock" style="font-size:0.8em"></i> ' . date('H:i', strtotime($evento['data_hora'])) . '</h6>                                        
                                        </div>
                                        <p class="mb-0" style="font-size:0.8em">' . $evento['titulo'] . '</p>
                                        <small class="text-body-secondary" style="font-size:0.8em">' . $evento['endereco'] . '</small>
                                    </a>';
                            }
                        } else if ($buscaEvento['status'] == 'empty') {
                            echo ' <li class="list-group-item"  style="font-size:0.8em">' . $buscaEvento['message'] . '</li>';
                        } else if ($buscaEvento['status'] == 'server_error') {
                            echo ' <li class="list-group-item">' . $buscaEvento['message'] . ' | ' . $buscaEvento['error_id'] . '</li>';
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>