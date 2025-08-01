<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$gabineteController = new \App\Controllers\GabineteController();
$usuarioController = new \App\Controllers\UsuarioController();
$compromissoController = new \App\Controllers\CompromissoController();
$controllerSituacaoCompromisso = new \App\Controllers\SituacaoCompromissoController();
$pessoaController = new \App\Controllers\PessoaController();

$EmendaController = new \App\Controllers\EmendaController();

$buscaGabinete = $gabineteController->buscar($_SESSION['gabinete']);
$buscaUsuario = $usuarioController->buscar($_SESSION['id']);

$buscaUsuarios = $usuarioController->listar('nome', 'asc', 1000, 1, ['gabinete' => $_SESSION['gabinete'], 'ativo' => 0], 'and');

$buscaEvento = $compromissoController->listar('hora', 'asc', 1000, 1, ['gabinete' => $_SESSION['gabinete'], 'data'  => date('Y-m-d')], 'AND');

?>

<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>

        <div class="container-fluid p-2">

            <div class="card mb-2 border-0" style="background: linear-gradient(45deg, rgba(2, 122, 131, 0.7), rgba(255, 169, 41, 0.7));">
                <div class="card-body custom-card-body p-3 text-white">
                    <h6 class="card-title mb-0 mt-0"> Bem-vindo(a) <?= $buscaUsuario['data']['nome'] ?></h6>
                </div>
            </div>

            <?php
            $alerts = [];

            if ($buscaGabinete['data']['partido'] == null || $buscaGabinete['data']['cidade'] == null) {
                $alerts[] = '<div class="alert alert-info custom-alert px-2 py-1" role="alert"><a href="?secao=meu-gabinete" class="alert-link"><i class="bi bi-info-circle-fill"></i> Complete o cadastro do gabinete.</a></div>';
            }

            if ($buscaUsuario['data']['aniversario'] == null) {
                $alerts[] = '<div class="alert alert-info custom-alert px-2 py-1" role="alert"><a href="?secao=meu-gabinete" class="alert-link"><i class="bi bi-info-circle-fill"></i> Atualize seus dados pessoais.</a></div>';
            }

            if ($buscaUsuarios['status'] == 'success') {
                $alerts[] = '<div class="alert alert-info custom-alert px-2 py-1" role="alert"><a href="?secao=meu-gabinete" class="alert-link"><i class="bi bi-info-circle-fill"></i> Você tem novos usuários aguardando ativação.</a></div>';
            }

            if ($buscaUsuario['status'] === 'success' && $buscaUsuario['data']['aniversario'] === date('d/m')) {
                $alerts[] = '<div class="alert alert-warning custom-alert px-2 py-1" role="alert"><i class="bi bi-cake"></i> Parabéns pelo seu aniversário!</div>';
            }

            if (!empty($alerts)) {
                echo '<div class="card mb-2" style="background: linear-gradient(45deg, rgba(2, 122, 131, 0.7), rgba(255,41,209,0.7));"><div class="card-body custom-card-body p-2">';
                $total = count($alerts);
                foreach ($alerts as $index => $alert) {
                    $mbClass = ($index == $total - 1) ? 'mb-0' : 'mb-2';
                    echo str_replace('custom-alert', 'custom-alert ' . $mbClass, $alert);
                }
                echo '</div></div>';
            }
            ?>

            <div class="row gx-2 gy-0">
                <div class="col-12 col-md-3">
                    <div class="card mb-2 border-0 text-white" style="background: linear-gradient(45deg, rgba(206, 74, 22, 0.7), rgba(206, 74, 22, 0.7)); min-height: 125px;">

                        <div class="card-body p-1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <p class="card-text text-center fw-bold mb-1" style="font-size:1.2em">Compromissos de hoje</p>
                            <?php
                            if ($buscaEvento['status'] == 'success') {
                                foreach ($buscaEvento['data'] as $evento) {
                                    $situacao = $controllerSituacaoCompromisso->buscar($evento['situacao_id'], 'id')['data']['nome'];
                                    echo '<p class="card-text text-center mb-0" style="font-size: 0.9em">' . date('H:i', strtotime($evento['hora'])) . ' | ' . $evento['titulo'] . '</p>';
                                }
                            } else if ($buscaEvento['status'] == 'empty') {
                                echo '<p class="card-text text-center mb-0" style="font-size: 0.9em">Nenhum compromisso para hoje</p>';
                            } else if ($buscaEvento['status'] == 'server_error') {
                                echo '<p class="card-text text-center mb-0" style="font-size: 0.9em">' . $buscaEvento['message'] . ' | ' . $buscaEvento['error_id'] . '</p>';
                            }
                            ?>




                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card mb-2 border-0 text-white" style="background: linear-gradient(45deg, rgba(206, 142, 22, 0.7), rgba(206, 142, 22, 0.7)); min-height: 125px;">

                        <?php

                        $condicoes = [
                            'gabinete' => $_SESSION['gabinete'],
                            'situacao_id' => '5',
                            'tipo' => 'Parlamentar'
                        ];

                        $lista = $EmendaController->listar('valor', 'desc', 1000, 1, $condicoes, 'AND');

                        $totalGeralCorrente = 0;
                        $totalGeralAnterior = 0;
                        $textoCard = '';

                        if ($lista['status'] == 'success') {
                            foreach ($lista['data'] as $emenda) {
                                if ($emenda['ano'] == date('Y')) {
                                    $totalGeralCorrente += $emenda['valor'];
                                } else if ($emenda['ano'] == (date('Y')) - 1) {
                                    $totalGeralAnterior += $emenda['valor'];
                                }
                            }

                            $percentual = 0;
                            if ($totalGeralAnterior > 0) {
                                $percentual = (($totalGeralCorrente - $totalGeralAnterior) / $totalGeralAnterior) * 100;
                            }

                            if ($percentual > 0) {
                                $textoCard = '<i class="bi bi-arrow-up"></i> ' . number_format($percentual, 2, ',', '.') . '% em relação a ' . (date('Y')) - 1;
                            } elseif ($percentual < 0) {
                                $textoCard = '<i class="bi bi-arrow-down"></i> ' . number_format($percentual, 2, ',', '.') . '% em relação a ' . (date('Y')) - 1;
                            } else {
                                $textoCard = 'Sem variação';
                            }
                        } else if ($lista['status'] == 'empty') {
                        }


                        ?>


                        <div class="card-body p-1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <p class="card-text text-center mb-1">Emendas Pagas em 2025</p>
                            <p class="card-text text-center fw-bold mb-1" style="font-size: 1.3em"><?php echo ($totalGeralCorrente != 0) ? 'R$ ' . number_format($totalGeralCorrente, 2, ',', '.') : 'Nenhuma emenda paga esse ano' ?></p>
                            <p class="card-text text-center mb-0" style="font-size: 0.8em"><?php echo ($totalGeralCorrente != 0) ? $textoCard : '' ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card mb-2 border-0 text-white" style="background: linear-gradient(45deg, rgba(22, 206, 129, 0.5), rgba(22, 206, 129, 0.7)); min-height: 125px;">
                        <div class="card-body p-1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <p class="card-text text-center mb-1">Proposições apresentadas em 2025</p>
                            <p class="card-text text-center fw-bold mb-0" style="font-size: 1.3em">Projetos de Lei: 4</p>
                            <p class="card-text text-center fw-bold mb-0" style="font-size: 1.3em">Requerimentos: 4</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card mb-2 border-0 text-white" style="background: linear-gradient(45deg, rgba(255,41,209,0.6), rgba(255,41,209,0.6)); min-height: 125px;">
                        <div class="card-body p-1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <p class="card-text text-center mb-1">Comissões que é membro em 2025</p>
                            <p class="card-text text-center fw-bold mb-0" style="font-size: 1.3em">CCJC | CE | PEC300</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>