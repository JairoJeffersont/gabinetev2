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

$idGet = $_GET['id'] ?? null;

$buscaDocumento = $documentoController->buscar($idGet);


if ($buscaDocumento['status'] != 'success') {
    header('Location: ?secao=documentos');
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
                    <a class="btn btn-success custom-card-body btn-sm link_loading" href="?secao=documentos" role="button"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-header">
                    <i class="bi bi-file-earmark-text"></i> Editar documento
                </div>
                <div class="card-body custom-card-body p-2">

                    <?php

                    $dados = [
                        'nome' => $_POST['nome'] ?? '',
                        'ano' => $_POST['ano'] ?? '',
                        'tipo_id' => $_POST['tipo_id'] ?? '',
                        'orgao' => $_POST['orgao'] ?? '',
                        'descricao' => $_POST['descricao'] ?? '',
                        'criado_por' => $_SESSION['id'],
                        'gabinete' => $_SESSION['gabinete']
                    ];

                    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
                        $dados['arquivo'] = $_FILES['arquivo'];
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar'])) {

                        $result = $documentoController->atualizarDocumento($idGet, $dados);

                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '. Aguarde...</div>';
                            echo '<script>
                                        setTimeout(function() {
                                            window.location.href = "?secao=documento&id=' . $idGet . '";
                                        }, 500);
                                        </script>
                                        ';
                        } else if ($result['status'] == 'duplicated') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="4">' . $result['message'] . '</div>';
                        } else if ($result['status'] == 'format_not_allowed' || $result['status'] == 'max_file_size_exceeded') {
                            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert" data-timeout="4">Tipo de arquivo não permitido ou arquivo muito grande</div>';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . ' - ' . $result['error_id'] . '</div>';
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                        $result = $documentoController->apagarDocumento($idGet);

                        if ($result['status'] == 'success') {
                            header('Location: ?secao=documentos');
                        } else if ($result['status'] == 'server_error' || $result['status'] == 'forbidden') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                        }
                    }

                    if ($_SESSION['tipo'] == '6') {
                        echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">Você não tem autorização para inserir ou editar documentos.</div>';
                    }
                    ?>
                    <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                        <div class="col-md-1 col-2">
                            <input type="text" class="form-control form-control-sm" name="ano" value="<?php echo $buscaDocumento['data']['ano'] ?>" data-mask=0000 required>
                        </div>
                        <div class="col-md-3 col-10">
                            <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome do documento. (ex. OF 25/2025..)" value="<?php echo $buscaDocumento['data']['nome'] ?>" required>
                        </div>

                        <div class="col-md-2 col-12">
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" name="tipo_id" required>
                                    <?php
                                    $buscaTipo = $tipoDocumentoController->listar('nome', 'asc', '1000', 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaTipo['status'] == 'success') {
                                        foreach ($buscaTipo['data'] as $tipo) {
                                            if ($tipo['id'] == $buscaDocumento['data']['tipo_id']) {
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
                                <select class="form-select form-select-sm" name="orgao">
                                    <?php
                                    $buscaOrgao = $orgaoController->listar('nome', 'asc', '1000', 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaOrgao['status'] == 'success') {
                                        foreach ($buscaOrgao['data'] as $orgao) {
                                            if ($orgao['id'] == $buscaDocumento['data']['orgao']) {
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
                            <input type="file" class="form-control form-control-sm" name="arquivo" />
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" name="descricao" rows="10" placeholder="Resumo do documento" required><?php echo $buscaDocumento['data']['descricao'] ?></textarea>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php
                            if ($_SESSION['tipo'] != '6') {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_atualizar"><i class="bi bi-floppy-fill"></i> Atualizar</button>&nbsp;&nbsp;';
                                echo '<button type="submit" class="btn btn-danger btn-sm confirm-action" name="btn_apagar"><i class="bi bi-trash"></i> Apagar</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-success btn-sm confirm-action" disabled name="btn_atualizar"><i class="bi bi-floppy-fill"></i> Salvar</button>&nbsp;&nbsp;';
                                echo '<button type="submit" class="btn btn-danger btn-sm confirm-action" disabled name="btn_apagar"><i class="bi bi-trash"></i> Apagar</button>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            $arquivo = $buscaDocumento['data']['arquivo'];
            $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
            $nomeArquivo = basename($arquivo);

            // Definindo ícone e cor
            $icone = 'bi-file-earmark';
            $corIcone = 'text-secondary';
            $tipo = strtoupper($extensao);

            switch ($extensao) {
                case 'pdf':
                    $icone = 'bi-file-earmark-pdf';
                    $corIcone = 'text-danger';
                    break;
                case 'doc':
                case 'docx':
                    $icone = 'bi-file-earmark-word';
                    $corIcone = 'text-primary';
                    break;
                case 'xls':
                case 'xlsx':
                    $icone = 'bi-file-earmark-excel';
                    $corIcone = 'text-success';
                    break;
            }
            ?>

            <div class="card mb-2 p-2 shadow-sm border-0">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">

                    <!-- Ícone + Nome -->
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="me-3">
                            <i class="bi <?php echo $icone; ?> <?php echo $corIcone; ?>" style="font-size: 38px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-break" style="font-size: 1em;"><?php echo htmlspecialchars($nomeArquivo); ?></h6>
                            <small class="text-muted">Tipo: <?php echo $tipo; ?></small>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mb-2 ">
                <div class="card-body custom-card-body p-1">
                    <div class="d-flex gap-2 flex-wrap">
                        <?php if ($extensao === 'pdf'): ?>
                            <a href="public/<?php echo htmlspecialchars($arquivo); ?>" target="_blank" style="font-size: 1.0em;" class="btn btn-secondary btn-sm">
                                <i class="bi bi-eye"></i> Visualizar
                            </a>
                        <?php endif; ?>

                        <a href="public/<?php echo htmlspecialchars($arquivo); ?>" style="font-size: 1.0em;" class="btn btn-primary btn-sm" download>
                            <i class="bi bi-download"></i> Baixar
                        </a>
                    </div>

                </div>
            </div>


        </div>
    </div>