<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$pessoaController = new \App\Controllers\PessoaController();
$tipoPessoaController = new \App\Controllers\PessoaTipoController();
$gabineteController = new \App\Controllers\GabineteController();
$orgaoController = new \App\Controllers\OrgaoController();

$estadogabinete = $gabineteController->buscar($_SESSION['gabinete'])['data']['estado'];

$estado = (isset($_GET['estado'])) ? $_GET['estado'] : $estadogabinete;





$buscaPessoas = $pessoaController->listar('nome', 'asc', 10000, 1, ['gabinete' => $_SESSION['gabinete'], 'estado' => $estado], 'AND');

if ($buscaPessoas['status'] == 'success') {
    $totalPessoas = count($buscaPessoas['data']);
    $buscaPessoas = $buscaPessoas;
} else {
    $totalPessoas = 0;
    $buscaPessoas = ['data' => []];
}





?>


<div class="d-flex" id="wrapper">

    <?php include '../src/Views/base/side_menu.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../src/Views/base/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary custom-card-body btn-sm" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
                    <a class="btn btn-success custom-card-body btn-sm" href="?secao=pessoas" role="button"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header">
                    <i class="bi bi-file-earmark-bar-graph"></i> Relatório de Pessoas do Sistema
                </div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-2">
                        Esta seção exibe o relatório de pessoas cadastradas no sistema, organizadas por diferentes tipos e categorias.
                    </p>
                    <p class="card-text mb-0">
                        Aqui é possível visualizar, filtrar e analisar as pessoas conforme sua classificação, facilitando o acompanhamento e a gestão de informações relevantes.
                    </p>
                </div>
            </div>

            <div class="accordion mb-3" id="accordionPessoas">

                <!-- Pessoas por Tipo -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTipo">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTipo" aria-expanded="true" aria-controls="collapseTipo">
                            <i class="bi bi-list-ul me-2"></i> Por Tipo
                        </button>
                    </h2>
                    <div id="collapseTipo" class="accordion-collapse collapse" aria-labelledby="headingTipo" data-bs-parent="#accordionPessoas">
                        <div class="accordion-body p-0">
                            <div class="card mb-0 border-0">
                                <div class="card-body custom-card-body p-2">
                                    <table class="table table-hover table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Quantidade</th>
                                                <th>(%) total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $agrupadoPorTipo = [];

                                            foreach ($buscaPessoas['data'] as $pessoa) {
                                                $tipo = $pessoa['tipo_id'];
                                                if (!isset($agrupadoPorTipo[$tipo])) {
                                                    $agrupadoPorTipo[$tipo] = 0;
                                                }
                                                $agrupadoPorTipo[$tipo]++;
                                            }

                                            $dadosTipo = [];
                                            foreach ($agrupadoPorTipo as $tipo => $quantidade) {
                                                $percentual = ($quantidade / $totalPessoas) * 100;
                                                $dadosTipo[] = [
                                                    'nome' => $tipoPessoaController->buscar($tipo)['data']['nome'],
                                                    'quantidade' => $quantidade,
                                                    'percentual' => $percentual
                                                ];
                                            }

                                            usort($dadosTipo, fn($a, $b) => $b['percentual'] <=> $a['percentual']);

                                            foreach ($dadosTipo as $item) {
                                                echo "<tr>
                                        <td>{$item['nome']}</td>
                                        <td>{$item['quantidade']}</td>
                                        <td>
                                            <div class='d-flex align-items-center gap-2'>
                                                <span>" . number_format($item['percentual'], 1, ',', '') . "%</span>
                                                <div class='progress flex-grow-1' style='height: 8px;'>
                                                    <div class='progress-bar bg-info' role='progressbar' style='width: {$item['percentual']}%;'></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pessoas por Sexo -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSexo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSexo" aria-expanded="false" aria-controls="collapseSexo">
                            <i class="bi bi-gender-ambiguous me-2"></i> Por gênero
                        </button>
                    </h2>
                    <div id="collapseSexo" class="accordion-collapse collapse" aria-labelledby="headingSexo" data-bs-parent="#accordionPessoas">
                        <div class="accordion-body p-0">
                            <div class="card mb-0 border-0">
                                <div class="card-body custom-card-body p-2">
                                    <table class="table table-hover table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Gênero</th>
                                                <th>Quantidade</th>
                                                <th>(%) total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sexoContagem = [];

                                            foreach ($buscaPessoas['data'] as $pessoa) {
                                                $sexo = $pessoa['sexo'] ?? 'Não informado';
                                                if (!isset($sexoContagem[$sexo])) {
                                                    $sexoContagem[$sexo] = 0;
                                                }
                                                $sexoContagem[$sexo]++;
                                            }

                                            $dadosSexo = [];
                                            foreach ($sexoContagem as $sexo => $quantidade) {
                                                $percentual = ($quantidade / $totalPessoas) * 100;
                                                $dadosSexo[] = [
                                                    'sexo' => $sexo,
                                                    'quantidade' => $quantidade,
                                                    'percentual' => $percentual
                                                ];
                                            }

                                            usort($dadosSexo, fn($a, $b) => $b['percentual'] <=> $a['percentual']);

                                            foreach ($dadosSexo as $item) {
                                                echo "<tr>
                                        <td>{$item['sexo']}</td>
                                        <td>{$item['quantidade']}</td>
                                        <td>
                                            <div class='d-flex align-items-center gap-2'>
                                                <span>" . number_format($item['percentual'], 1, ',', '') . "%</span>
                                                <div class='progress flex-grow-1' style='height: 8px;'>
                                                    <div class='progress-bar bg-success' role='progressbar' style='width: {$item['percentual']}%;'></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pessoas por Profissão -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingProfissao">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProfissao" aria-expanded="false" aria-controls="collapseProfissao">
                            <i class="bi bi-briefcase me-2"></i> Por profissão
                        </button>
                    </h2>
                    <div id="collapseProfissao" class="accordion-collapse collapse" aria-labelledby="headingProfissao" data-bs-parent="#accordionPessoas">
                        <div class="accordion-body p-0">
                            <div class="card mb-0 border-0">
                                <div class="card-body custom-card-body p-2">
                                    <table class="table table-hover table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Profissão</th>
                                                <th>Quantidade</th>
                                                <th>(%) total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $profissaoContagem = [];

                                            foreach ($buscaPessoas['data'] as $pessoa) {
                                                $profissao = trim($pessoa['profissao'] ?? '') ?: 'Não informado';
                                                if (!isset($profissaoContagem[$profissao])) {
                                                    $profissaoContagem[$profissao] = 0;
                                                }
                                                $profissaoContagem[$profissao]++;
                                            }

                                            $dadosProfissao = [];
                                            foreach ($profissaoContagem as $profissao => $quantidade) {
                                                $percentual = ($quantidade / $totalPessoas) * 100;
                                                $dadosProfissao[] = [
                                                    'profissao' => $profissao,
                                                    'quantidade' => $quantidade,
                                                    'percentual' => $percentual
                                                ];
                                            }

                                            usort($dadosProfissao, fn($a, $b) => $b['percentual'] <=> $a['percentual']);

                                            foreach ($dadosProfissao as $item) {
                                                echo "<tr>
                                        <td>{$item['profissao']}</td>
                                        <td>{$item['quantidade']}</td>
                                        <td>
                                            <div class='d-flex align-items-center gap-2'>
                                                <span>" . number_format($item['percentual'], 1, ',', '') . "%</span>
                                                <div class='progress flex-grow-1' style='height: 8px;'>
                                                    <div class='progress-bar bg-warning' role='progressbar' style='width: {$item['percentual']}%;'></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Pessoas por Município -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingMunicipio">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMunicipio" aria-expanded="false" aria-controls="collapseMunicipio">
                            <i class="bi bi-geo-alt me-2"></i> Por município (Estado: <?= strtoupper($estado) ?>)
                        </button>
                    </h2>
                    <div id="collapseMunicipio" class="accordion-collapse collapse" aria-labelledby="headingMunicipio" data-bs-parent="#accordionPessoas">
                        <div class="accordion-body p-0">
                            <div class="card mb-0 border-0">
                                <div class="card-body custom-card-body p-2">
                                    <table class="table table-hover table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Município</th>
                                                <th>Quantidade</th>
                                                <th>(%) total (<?= strtoupper($estado) ?>)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $municipiosContagem = [];
                                            $totalEstado = 0;

                                            foreach ($buscaPessoas['data'] as $pessoa) {
                                                if (($pessoa['estado'] ?? '') === strtoupper($estado)) {
                                                    $municipio = trim($pessoa['municipio'] ?? '') ?: 'Não informado';
                                                    if (!isset($municipiosContagem[$municipio])) {
                                                        $municipiosContagem[$municipio] = 0;
                                                    }
                                                    $municipiosContagem[$municipio]++;
                                                    $totalEstado++;
                                                }
                                            }

                                            $dadosMunicipios = [];
                                            foreach ($municipiosContagem as $municipio => $quantidade) {
                                                $percentual = ($quantidade / $totalEstado) * 100;
                                                $dadosMunicipios[] = [
                                                    'municipio' => $municipio,
                                                    'quantidade' => $quantidade,
                                                    'percentual' => $percentual
                                                ];
                                            }

                                            usort($dadosMunicipios, fn($a, $b) => $b['percentual'] <=> $a['percentual']);

                                            foreach ($dadosMunicipios as $item) {
                                                echo "<tr>
                                        <td>{$item['municipio']}</td>
                                        <td>{$item['quantidade']}</td>
                                        <td>
                                            <div class='d-flex align-items-center gap-2'>
                                                <span>" . number_format($item['percentual'], 1, ',', '') . "%</span>
                                                <div class='progress flex-grow-1' style='height: 8px;'>
                                                    <div class='progress-bar bg-danger' role='progressbar' style='width: {$item['percentual']}%;'></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pessoas por Importância -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingImportancia">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImportancia" aria-expanded="false" aria-controls="collapseImportancia">
                            <i class="bi bi-star me-2"></i> Por importância
                        </button>
                    </h2>
                    <div id="collapseImportancia" class="accordion-collapse collapse" aria-labelledby="headingImportancia" data-bs-parent="#accordionPessoas">
                        <div class="accordion-body p-0">
                            <div class="card mb-0 border-0">
                                <div class="card-body custom-card-body p-2">
                                    <table class="table table-hover table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Importância</th>
                                                <th>Quantidade</th>
                                                <th>(%) total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $importanciaContagem = [];

                                            foreach ($buscaPessoas['data'] as $pessoa) {
                                                $importancia = trim($pessoa['importancia'] ?? '') ?: 'Não informada';
                                                if (!isset($importanciaContagem[$importancia])) {
                                                    $importanciaContagem[$importancia] = 0;
                                                }
                                                $importanciaContagem[$importancia]++;
                                            }

                                            $dadosImportancia = [];
                                            foreach ($importanciaContagem as $importancia => $quantidade) {
                                                $percentual = ($quantidade / $totalPessoas) * 100;
                                                $dadosImportancia[] = [
                                                    'importancia' => $importancia,
                                                    'quantidade' => $quantidade,
                                                    'percentual' => $percentual
                                                ];
                                            }

                                            usort($dadosImportancia, fn($a, $b) => $b['percentual'] <=> $a['percentual']);

                                            foreach ($dadosImportancia as $item) {
                                                echo "<tr>
                                                        <td>{$item['importancia']}</td>
                                                        <td>{$item['quantidade']}</td>
                                                        <td>
                                                            <div class='d-flex align-items-center gap-2'>
                                                                <span>" . number_format($item['percentual'], 1, ',', '') . "%</span>
                                                                <div class='progress flex-grow-1' style='height: 8px;'>
                                                                    <div class='progress-bar bg-primary' role='progressbar' style='width: {$item['percentual']}%;'></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pessoas por Órgão -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOrgao">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrgao" aria-expanded="false" aria-controls="collapseOrgao">
                            <i class="bi bi-building me-2"></i> Por orgão
                        </button>
                    </h2>
                    <div id="collapseOrgao" class="accordion-collapse collapse" aria-labelledby="headingOrgao" data-bs-parent="#accordionPessoas">
                        <div class="accordion-body p-0">
                            <div class="card mb-0 border-0">
                                <div class="card-body custom-card-body p-2">
                                    <table class="table table-hover table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Órgão</th>
                                                <th>Quantidade</th>
                                                <th>(%) total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $orgaoContagem = [];

                                            foreach ($buscaPessoas['data'] as $pessoa) {
                                                $orgao = trim($pessoa['orgao'] ?? '') ?: 'Não informado';
                                                if (!isset($orgaoContagem[$orgao])) {
                                                    $orgaoContagem[$orgao] = 0;
                                                }
                                                $orgaoContagem[$orgao]++;
                                            }

                                            $dadosOrgao = [];
                                            foreach ($orgaoContagem as $orgao => $quantidade) {
                                                $percentual = ($quantidade / $totalPessoas) * 100;
                                                $dadosOrgao[] = [
                                                    'orgao' => $orgao,
                                                    'quantidade' => $quantidade,
                                                    'percentual' => $percentual
                                                ];
                                            }

                                            usort($dadosOrgao, fn($a, $b) => $b['percentual'] <=> $a['percentual']);

                                            foreach ($dadosOrgao as $item) {
                                                echo "<tr>
                                                        <td>".$orgaoController->buscar($item['orgao'])['data']['nome']."</td>
                                                        <td>{$item['quantidade']}</td>
                                                        <td>
                                                            <div class='d-flex align-items-center gap-2'>
                                                                <span>" . number_format($item['percentual'], 1, ',', '') . "%</span>
                                                                <div class='progress flex-grow-1' style='height: 8px;'>
                                                                    <div class='progress-bar bg-secondary' role='progressbar' style='width: {$item['percentual']}%;'></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>";
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






        </div>
    </div>
</div>