<?php

ob_start();

include '../src/Views/includes/verificaLogado.php';

$tipoPessoaController = new \App\Controllers\PessoaTipoController();
$usuarioController = new \App\Controllers\UsuarioController();
$orgaoController = new \App\Controllers\OrgaoController();
$gabineteController = new \App\Controllers\GabineteController();
$pessoaController = new \App\Controllers\PessoaController();


$estadogabinete = $gabineteController->buscar($_SESSION['gabinete'])['data']['estado'];

$pessoaGet = isset($_GET['id']) ? $_GET['id'] : '';

$buscaPessoa = $pessoaController->buscar($pessoaGet);

if ($buscaPessoa['status'] != 'success') {
    header('Location: ?secao=pessoas');
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

            <div class="card card-perfil w-100 p-2 d-flex flex-row align-items-center mb-2 text-white">
                <img src="<?= ($buscaPessoa['data']['foto']) ? $buscaPessoa['data']['foto'] : 'img/not_found.jpg' ?>" alt="Foto do usuário" class="foto-perfil me-3">
                <div>
                    <h5 class="mb-1"><?= $buscaPessoa['data']['nome'] ?></h5>
                    <p class="mb-0"><?= ($buscaPessoa['data']['email']) ? $buscaPessoa['data']['email'] : 'Email não informado' ?></p>
                    <p class="mb-0"><?= ($buscaPessoa['data']['telefone']) ? $buscaPessoa['data']['telefone'] : 'Whatsapp não informado' ?></p>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-header bg-primary text-white px-2 py-1 custom-card-body"><i class="bi bi-people"></i> Editar pessoa</div>
                <div class="card-body custom-card-body p-2">
                    <?php

                    $dados = [
                        'nome' => $_POST['nome'] ?? '',
                        'email' => $_POST['email'] ?? '',
                        'aniversario' => $_POST['aniversario'] ?? '',
                        'telefone' => $_POST['telefone'] ?? '',
                        'endereco' => $_POST['endereco'] ?? '',
                        'bairro' => $_POST['bairro'] ?? '',
                        'cep' => $_POST['cep'] ?? '',
                        'estado' => $_POST['estado'] ?? '',
                        'municipio' => $_POST['municipio'] ?? '',
                        'sexo' => $_POST['sexo'] ?? 'Não informado',
                        'facebook' => $_POST['facebook'] ?? '',
                        'instagram' => $_POST['instagram'] ?? '',
                        'twitter' => $_POST['twitter'] ?? '',
                        'orgao' => $_POST['orgao'] ?? '',
                        'tipo_id' => $_POST['tipo_id'] ?? '',
                        'profissao' => $_POST['profissao'] ?? '',
                        'importancia' => $_POST['importancia'] ?? '',
                        'informacoes' => $_POST['informacoes'] ?? '',
                        'criado_por' => $_SESSION['id'],
                        'gabinete' => $_SESSION['gabinete']
                    ];

                    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                        $dados['foto'] = $_FILES['foto'];
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                        $result = $pessoaController->atualizarPessoa($pessoaGet, $dados);

                        if ($result['status'] == 'success') {
                            echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="2">' . $result['message'] . '! Aguarde...</div>';
                            echo '<script>
                                        setTimeout(function() {
                                            window.location.href = "?secao=pessoa&id=' . $pessoaGet . '";
                                        }, 500);
                                        </script>
                                        ';
                        } else if ($result['status'] == 'server_error') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . ' - ' . $result['error_id'] . '</div>';
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                        $result = $pessoaController->apagarPessoa($pessoaGet);

                        if ($result['status'] == 'success') {
                            header('Location: ?secao=pessoas');
                        } else if ($result['status'] == 'server_error' || $result['status'] == 'forbidden') {
                            echo '<div class="alert alert-danger custom-alert px-2 py-1 mb-2" role="alert">' . $result['message'] . '</div>';
                        }
                    }


                    if ($buscaPessoa['data']['aniversario'] == date('d/m')) {
                        echo '<div class="alert alert-warning custom-alert px-2 py-1 mb-2" role="alert"><i class="bi bi-cake-fill text-warning"></i> Hoje é aniversário dessa pessoa!</div>';
                    }


                    ?>

                    <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                        <div class="col-md-4 col-12">
                            <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" value="<?= $buscaPessoa['data']['nome'] ?>" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <input type="text" class="form-control form-control-sm" name="email" placeholder="Email" value="<?= $buscaPessoa['data']['email'] ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <input type="text" class="form-control form-control-sm" name="aniversario" placeholder="Aniversário (dd/mm)" data-mask="00/00" value="<?= $buscaPessoa['data']['aniversario'] ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Whatsapp (Somente números)" maxlength="15" data-mask="(00) 00000-0000" value="<?= $buscaPessoa['data']['telefone'] ?>">
                        </div>
                        <div class="col-md-5 col-12">
                            <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço " value="<?= $buscaPessoa['data']['endereco'] ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro " value="<?= $buscaPessoa['data']['bairro'] ?>">
                        </div>
                        <div class="col-md-1 col-6">
                            <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (Somente números)" maxlength="9" data-mask="00000-000" value="<?= $buscaPessoa['data']['cep'] ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="estado" name="estado" data-selected="<?= $buscaPessoa['data']['estado'] ?>" required>
                                <option value="" selected>UF</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select class="form-select form-select-sm" id="municipio" name="municipio" data-selected="<?= $buscaPessoa['data']['municipio'] ?>" required>
                                <option value="" selected>Município</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <select class="form-select form-select-sm" id="sexo" name="sexo" required>
                                <option value="Não informado" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Não informado' ? 'selected' : '' ?>>Selecione o gênero</option>
                                <option value="Feminino" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                                <option value="Masculino" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                <option value="Transgênero" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Transgênero' ? 'selected' : '' ?>>Transgênero</option>
                                <option value="Não binário" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Não binário' ? 'selected' : '' ?>>Não binário</option>
                                <option value="Agênero" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Agênero' ? 'selected' : '' ?>>Agênero</option>
                                <option value="Gênero fluido" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Gênero fluido' ? 'selected' : '' ?>>Gênero fluido</option>
                                <option value="Outro" <?= ($buscaPessoa['data']['sexo'] ?? '') === 'Outro' ? 'selected' : '' ?>>Outro</option>
                            </select>

                        </div>
                        <div class="col-md-2 col-4">
                            <input type="text" class="form-control form-control-sm" name="facebook" placeholder="@facebook " value="<?= $buscaPessoa['data']['facebook'] ?>">
                        </div>
                        <div class="col-md-2 col-4">
                            <input type="text" class="form-control form-control-sm" name="instagram" placeholder="@instagram " value="<?= $buscaPessoa['data']['instagram'] ?>">
                        </div>
                        <div class="col-md-2 col-4">
                            <input type="text" class="form-control form-control-sm" name="twitter" placeholder="@X (Twitter) " value="<?= $buscaPessoa['data']['twitter'] ?>">
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" id="orgao" name="orgao" required>
                                    <?php
                                    $buscaOrgaos = $orgaoController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaOrgaos['status'] == 'success') {
                                        foreach ($buscaOrgaos['data'] as $orgao) {
                                            if ($orgao['id'] == $buscaPessoa['data']['orgao']) {
                                                echo '<option value="' . $orgao['id'] . '" selected>' . $orgao['nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $orgao['id'] . '">' . $orgao['nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <a href="?secao=orgaos" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo tipo">
                                    <i class="bi bi-plus"></i> novo órgão
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" id="tipo_id" name="tipo_id" required>
                                    <?php
                                    $buscaTipos = $tipoPessoaController->listar('nome', 'asc', 1000, 1, ['gabinete' => [$_SESSION['gabinete'], '1']]);
                                    if ($buscaTipos['status'] == 'success') {
                                        foreach ($buscaTipos['data'] as $tipos) {
                                            if ($tipos['id'] == $buscaPessoa['data']['tipo_id']) {
                                                echo '<option value="' . $tipos['id'] . '" selected>' . $tipos['nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $tipos['id'] . '">' . $tipos['nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <a href="?secao=tipos-pessoas" type="button" class="btn btn-secondary confirm-action" title="Adicionar novo tipo">
                                    <i class="bi bi-plus"></i> novo tipo
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <input type="text" class="form-control form-control-sm" name="profissao" placeholder="Profissão. (ex. Advogado, Professor...)" value="<?= $buscaPessoa['data']['profissao'] ?>">
                        </div>
                        <div class="col-md-2 col-12">
                            <select class="form-select form-select-sm" id="importancia" name="importancia" required>
                                <option value="" disabled <?= empty($buscaPessoa['data']['importancia']) ? 'selected' : '' ?>>Selecione a importância</option>
                                <option value="Baixa" <?= ($buscaPessoa['data']['importancia'] ?? '') === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                                <option value="Média" <?= ($buscaPessoa['data']['importancia'] ?? '') === 'Média' ? 'selected' : '' ?>>Média</option>
                                <option value="Neutra" <?= ($buscaPessoa['data']['importancia'] ?? '') === 'Neutra' ? 'selected' : '' ?>>Neutra</option>
                                <option value="Alta" <?= ($buscaPessoa['data']['importancia'] ?? '') === 'Alta' ? 'selected' : '' ?>>Alta</option>
                                <option value="Muito Alta" <?= ($buscaPessoa['data']['importancia'] ?? '') === 'Muito Alta' ? 'selected' : '' ?>>Muito Alta</option>
                            </select>

                        </div>
                        <div class="col-md-4 col-12">
                            <input type="file" class="form-control form-control-sm" name="foto" />
                        </div>
                        <div class="col-md-12 col-12">
                            <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes dessa pessoa"><?= $buscaPessoa['data']['informacoes'] ?></textarea>
                        </div>
                        <div class="col-md-2 col-6">
                            <button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>
                            <button type="submit" class="btn btn-danger btn-sm confirm-action" name="btn_apagar"><i class="bi bi-trash"></i> Apagar</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>