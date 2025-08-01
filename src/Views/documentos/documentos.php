<?php

use App\Controllers\DocumentoController;
use App\Controllers\OrgaoController;
use App\Controllers\TipoDocumentoController;
use App\Controllers\UsuarioController;

ob_start();

include '../src/Views/includes/verificaLogado.php';

$tipoDocumentoController = new TipoDocumentoController();
$documentoController = new DocumentoController();
$orgaoController = new OrgaoController();
$usuarioController = new UsuarioController();

$anoGet = $_GET['ano'] ?? date('Y');
$tipoGet = $_GET['tipo_id'] ?? '0';
$termoGet = $_GET['termo'] ?? null;
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
                    <i class="bi bi-file-earmark-text"></i> Documentos
                </div>
                <div class="card-body custom-card-body p-2">
                    <p class="card-text mb-2">
                        Nesta seção, é possível adicionar e editar documentos do gabinete, garantindo a organização correta dessas informações no sistema.
                    </p>
                    <p class="card-text mb-0">
                        Todos os campos são obrigatórios. São permitidos arquivos <b>PDF, Word, Excel, JPG e PNG</b>. Tamanho máximo de <b>20MB</b>
                    </p>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body custom-card-body p-2">
                    <?php

                    $dados = [
                        'nome' => $_POST['nome'] ?? '',
                        'ano' => $_POST['ano'] ?? '',
                        'tipo_id' => $_POST['tipo_id'] ?? '',
                        'orgao' => $_POST['orgao'] ?? '',
                        'descricao' => $_POST['descricao'] ?? '',
                        'arquivo' => $_FILES['arquivo'] ?? '',
                        'criado_por' => $_SESSION['id'],
                        'gabinete' => $_SESSION['gabinete']
                    ];

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                        $result = $documentoController->novoDocumento($dados);


                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="4">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'format_not_allowed' || $result['status'] == 'max_file_size_exceeded' || $result['status'] == 'file_already_exists') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="4">Tipo de arquivo não permitido, arquivo muito grande ou arquivo já existe</div>';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . ' - ' . $result['error_id'] . '</div>';
                        }
                    }
                    if ($_SESSION['tipo'] == '6') {
                        echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">Você não tem autorização para inserir ou editar documentos.</div>';
                    }
                    ?>
                    <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                        <div class="col-md-1 col-2">
                            <input type="text" class="form-control form-control-sm" name="ano" value="<?php echo date('Y') ?>" data-mask=0000 required>
                        </div>
                        <div class="col-md-3 col-10">
                            <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome do documento. (ex. Oficio 25/2025, Carta...)" required>
                        </div>

                        <div class="col-md-2 col-12">
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" name="tipo_id" required>
                                    <?php
                                    $buscaTipo = $tipoDocumentoController->listar('nome', 'asc', '1000', 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
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
                                <a href="?secao=tipos-documentos" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo tipo">
                                    <i class="bi bi-plus"></i> novo tipo
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" name="orgao" required>
                                    <?php
                                    $buscaOrgao = $orgaoController->listar('nome', 'asc', '1000', 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaOrgao['status'] == 'success') {
                                        foreach ($buscaOrgao['data'] as $orgao) {
                                            if ($orgao['id'] == '1') {
                                                echo '<option value="' . $orgao['id'] . '" selected>' . $orgao['nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $orgao['id'] . '">' . $orgao['nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <a href="?secao=orgaos" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo órgão">
                                    <i class="bi bi-plus"></i> novo órgão
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <input type="file" class="form-control form-control-sm" name="arquivo" required />
                        </div>

                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" name="descricao" id="descricao" rows="2" placeholder="Resumo do documento" required></textarea>
                        </div>
                        <div class="col-md-1 col-12">
                            <?php
                            if ($_SESSION['tipo'] != '6') {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" disabled name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body custom-card-body p-2">

                    <form class="row g-2 form_custom mb-0" action="" method="GET" enctype="application/x-www-form-urlencoded">
                        <input type="hidden" name="secao" value="documentos" />

                        <div class="col-md-1 col-2">
                            <input type="text" class="form-control form-control-sm" name="ano" value="<?php echo $anoGet ?>">
                        </div>
                        <div class="col-md-2 col-10">
                            <select class="form-select form-select-sm" name="tipo_id">
                                <?php
                                echo '<option value="0" ' . ($tipoGet == '0' ? 'selected' : '') . '>Todos os Tipos</option>';

                                $buscaTipo = $tipoDocumentoController->listar('nome', 'asc', '1000', 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);

                                if ($buscaTipo['status'] == 'success') {
                                    foreach ($buscaTipo['data'] as $tipo) {
                                        $selected = ($tipo['id'] == $tipoGet) ? 'selected' : '';
                                        echo '<option value="' . $tipo['id'] . '" ' . $selected . '>' . $tipo['nome'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3 col-9">
                            <input type="text" class="form-control form-control-sm" name="termo" placeholder="Digite o nome do documento que deseja encontrar" value="<?php echo $termoGet ?>">
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
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Resumo</th>
                                        <th scope="col">Órgão</th>
                                        <th scope="col" style="white-space: nowrap;">Adicionado em | por</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                    if ($termoGet == null) {
                                        $condicoes = [
                                            'gabinete' => $_SESSION['gabinete'],
                                            'ano'      => $anoGet
                                        ];
                                    }else{
                                         $condicoes = [
                                        'gabinete' => $_SESSION['gabinete'],
                                        'nome'     => ['LIKE' => "%$termoGet%"]
                                    ];
                                    }

                                    if ($tipoGet != '0') {
                                        $condicoes['tipo_id'] = $tipoGet;
                                    }

                                    $buscaDocumentos = $documentoController->listar('nome', 'asc', 1000, 1,  $condicoes, 'AND');

                                    if ($buscaDocumentos['status'] == 'success') {
                                        foreach ($buscaDocumentos['data'] as $documento) {

                                            $usuario = $usuarioController->buscar($documento['criado_por'])['data']['nome'];
                                            $buscaTipo = $tipoDocumentoController->buscar($documento['tipo_id'])['data']['nome'];
                                            $buscaOrgao = $orgaoController->buscar($documento['orgao'])['data']['nome'];

                                            echo '<tr>';
                                            echo '<td style="white-space: nowrap;"><a class="link_loading" href="?secao=documento&id=' . $documento['id'] . '">' . htmlspecialchars($documento['nome'] ?? '') . '</a></td>';
                                            echo '<td style="white-space: nowrap;">' . ($buscaTipo ?? '') . '</td>';
                                            echo '<td style="white-space: nowrap;">' . $documento['descricao'] . '</td>';
                                            echo '<td style="white-space: nowrap;">' . ($buscaOrgao ?? '') . '</td>';
                                            echo '<td style="white-space: nowrap;">' . date('d/m H:i', strtotime($documento['criado_em'])) . ' | ' . ($usuario ?? '') . '</td>';
                                            echo '</tr>';
                                        }
                                    } else if ($buscaDocumentos['status'] == 'empty') {
                                        echo '<tr><td colspan="5">Nenhum documento encontrado</td></tr>';
                                    } else if ($buscaDocumentos['status'] == 'server_error') {
                                        echo '<tr><td colspan="5">' . $buscaDocumentos['message'] . ' | ' . $buscaDocumentos['error_id'] . '</td></tr>';
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