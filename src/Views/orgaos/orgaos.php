<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$tipoOrgaoController = new \App\Controllers\OrgaoTipoController();
$usuarioController = new \App\Controllers\UsuarioController();
$orgaoController = new \App\Controllers\OrgaoController();
$gabineteController = new \App\Controllers\GabineteController();
$exportHelper = new \App\Helpers\FileExportHelper();

$estadogabinete = $gabineteController->buscar($_SESSION['gabinete'])['data']['estado'];

$ordenarPor = isset($_GET['ordenarPor']) ? $_GET['ordenarPor'] : 'criado_em';
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'desc';
$itens = isset($_GET['itens']) ? $_GET['itens'] : 10;
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : '1';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '0';
$estado = isset($_GET['estado']) ? $_GET['estado'] : $estadogabinete;
$termo = isset($_GET['termo']) ? $_GET['termo'] : '';

$filtros = [];

if ($tipo !== '0') {
    $filtros['tipo_id'] = ['=' => $tipo];
}

if ($estado !== '0') {
    $filtros['estado'] = ['=' => $estado];
}
if ($termo !== '') {
    $filtros['nome'] = ['LIKE' => "%$termo%"];
}

$filtros['gabinete'] = $_SESSION['gabinete'];

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
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header"><i class="bi bi-building"></i> Órgãos e Entidades</div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-0">Nesta seção, é possível gerenciar órgãos ou entidades, garantindo a organização correta dessas informações no sistema.</p>
                </div>
            </div>
            <div class="card mb-2">
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
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="4">' . $result['message'] . '</div>';
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
                            <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (somente números)" data-mask="00000-000" maxlength="9">
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
                                <a href="?secao=tipos-orgaos" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo tipo">
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
                        <div class="col-md-4 col-12">
                            <button type="submit" class="btn btn-success confirm-action  btn-sm" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-2 ">
                <div class="card-body custom-card-body p-2">
                    <form class="row g-2 form_custom mb-0" action="" method="GET" enctype="application/x-www-form-urlencoded">
                        <input type="hidden" name="secao" value="orgaos" />

                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="ordenarPor" required>
                                <option value="nome" <?php echo ($ordenarPor == 'nome') ? 'selected' : ''; ?>>Ordenar por | Nome</option>
                                <option value="criado_em" <?php echo ($ordenarPor == 'criado_em') ? 'selected' : ''; ?>>Ordenar por | Criação</option>
                                <option value="municipio" <?php echo ($ordenarPor == 'criado_em') ? 'selected' : ''; ?>>Ordenar por | Município</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="ordem" required>
                                <option value="asc" <?php echo ($ordem == 'asc') ? 'selected' : ''; ?>>Ordem Crescente</option>
                                <option value="desc" <?php echo ($ordem == 'desc') ? 'selected' : ''; ?>>Ordem Decrescente</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="itens" required>
                                <option value="5" <?php echo ($itens == 5) ? 'selected' : ''; ?>>5 itens</option>
                                <option value="10" <?php echo ($itens == 10) ? 'selected' : ''; ?>>10 itens</option>
                                <option value="25" <?php echo ($itens == 25) ? 'selected' : ''; ?>>25 itens</option>
                                <option value="50" <?php echo ($itens == 50) ? 'selected' : ''; ?>>50 itens</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" name="tipo" required>
                                <option value="0" <?php echo ($tipo == '0') ? 'selected' : ''; ?>>Todos os tipos</option>
                                <?php
                                $buscaTipos = $tipoOrgaoController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                if ($buscaTipos['status'] == 'success') {
                                    foreach ($buscaTipos['data'] as $tipos) {
                                        $selected = ($tipo == $tipos['id']) ? 'selected' : '';
                                        echo '<option value="' . $tipos['id'] . '" ' . $selected . '>' . $tipos['nome'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-1 col-12">
                            <select class="form-select form-select-sm" name="estado" required>
                                <option value="0" <?php echo ($estado == '0') ? 'selected' : ''; ?>>Todos os estados</option>
                                <option value="<?php echo $estadogabinete ?>" <?php echo ($estado == $estadogabinete) ? 'selected' : ''; ?>>Somente <?php echo $estadogabinete ?></option>
                            </select>
                        </div>

                        <div class="col-md-2 col-9">
                            <input type="text" class="form-control form-control-sm" name="termo" placeholder="Digite o órgão que deseja encontrar" value="<?php echo htmlspecialchars($termo); ?>">
                        </div>

                        <div class="col-md-1 col-3">
                            <button type="submit" class="btn btn-primary btn-sm w-100 w-md-auto">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="card mb-2 ">
                <div class="card-body custom-card-body p-2">
                    <?php
                    if ($estado == $estadogabinete) {
                        echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="0">Mostrando somente órgãos/entidades do estado (' . $estadogabinete . ').</div>';
                    }

                    ?>
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Telefone</th>
                                        <th scope="col">Endereço</th>
                                        <th scope="col">UF/Município</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col" style="white-space: nowrap;">Criado em | por</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $buscaOrgaos = $orgaoController->listar($ordenarPor, $ordem, $itens, $pagina, $filtros, 'AND');

                                    if ($buscaOrgaos['status'] == 'success') {
                                        foreach ($buscaOrgaos['data'] as $orgao) {
                                            $usuario = $usuarioController->buscar($orgao['criado_por'])['data']['nome'];
                                            $buscaTipo = $tipoOrgaoController->buscar($orgao['tipo_id'])['data']['nome'];
                                            echo '<tr>';
                                            echo '<td style="white-space: nowrap;"><a href="?secao=orgao&id=' . $orgao['id'] . '">' . htmlspecialchars($orgao['nome'] ?? '') . '</a></td>';
                                            echo '<td>' . htmlspecialchars($orgao['email'] ?? '') . '</td>';
                                            echo '<td>' . htmlspecialchars($orgao['telefone'] ?? '') . '</td>';
                                            echo '<td>' . htmlspecialchars($orgao['endereco'] ?? '') . '</td>';
                                            echo '<td>' . htmlspecialchars($orgao['municipio'] ?? '') . ' / ' . htmlspecialchars($orgao['estado'] ?? '') . '</td>';
                                            echo '<td>' . ($buscaTipo ?? '') . '</td>';
                                            echo '<td>' . date('d/m H:i', strtotime($orgao['criado_em'])) . ' | ' . ($usuario ?? '') . '</td>';
                                            echo '</tr>';
                                        }
                                    } else if ($buscaOrgaos['status'] == 'empty') {
                                        echo '<tr><td colspan="7">Nenhum órgão encontrado.</td></tr>';
                                    } else if ($buscaOrgaos['status'] == 'server_error') {
                                        echo '<tr><td colspan="7">' . $buscaOrgaos['message'] . ' | ' . $buscaOrgaos['error_id'] . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                            $totalPaginas = isset($buscaOrgaos['total_paginas']) ? $buscaOrgaos['total_paginas'] : 0;

                            if ($totalPaginas > 1):

                                $maxLinks = 5;
                                $meio = floor($maxLinks / 2);

                                $inicio = max(1, $pagina - $meio);
                                $fim = min($totalPaginas, $inicio + $maxLinks - 1);

                                if ($fim - $inicio + 1 < $maxLinks) {
                                    $inicio = max(1, $fim - $maxLinks + 1);
                                }
                            ?>
                                <ul class="pagination custom-pagination mt-2 mb-0">
                                    <!-- Primeiro -->
                                    <li class="page-item <?= $pagina == 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?secao=orgaos&ordenarPor=<?= $ordenarPor ?>&ordem=<?= $ordem ?>&itens=<?= $itens ?>&tipo=<?= $tipo ?>&estado=<?= $estado ?>&termo=<?= urlencode($termo) ?>&pagina=1">Primeiro</a>
                                    </li>

                                    <!-- Números de página -->
                                    <?php for ($i = $inicio; $i <= $fim; $i++): ?>
                                        <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                                            <a class="page-link" href="?secao=orgaos&ordenarPor=<?= $ordenarPor ?>&ordem=<?= $ordem ?>&itens=<?= $itens ?>&tipo=<?= $tipo ?>&estado=<?= $estado ?>&termo=<?= urlencode($termo) ?>&pagina=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- Último -->
                                    <li class="page-item <?= $pagina == $totalPaginas ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?secao=orgaos&ordenarPor=<?= $ordenarPor ?>&ordem=<?= $ordem ?>&itens=<?= $itens ?>&tipo=<?= $tipo ?>&estado=<?= $estado ?>&termo=<?= urlencode($termo) ?>&pagina=<?= $totalPaginas ?>">Último</a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>