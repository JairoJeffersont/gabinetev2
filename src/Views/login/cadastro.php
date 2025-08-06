<link href="public/css/cadastro.css" rel="stylesheet" />

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="centralizada text-center">

        <img src="public/img/logo_white.png" alt="" class="img_logo" />
        <h2 class="login_title mb-1">Mandato Digital</h2>
        <h6 class="host mb-3">Novo Gabinete</h6>


        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

            $validation = new \App\Helpers\Validation();

            $dadosFormulario = [
                'usuario_nome'      => $_POST['usuario_nome'] ?? '',
                'usuario_email'     => $_POST['usuario_email'] ?? '',
                'usuario_senha'     => $_POST['usuario_senha'] ?? '',
                'usuario_senha2'    => $_POST['usuario_senha2'] ?? '',
                'usuario_telefone'  => $_POST['usuario_telefone'] ?? '',
                'gabinete_tipo'     => $_POST['gabinete_tipo'] ?? '',
                'gabinete_estado'   => $_POST['gabinete_estado'] ?? '',
                'gabinete_nome'     => $_POST['gabinete_nome'] ?? '',
                'gabinete_nome_slug'     => $validation->slug($_POST['gabinete_nome']) ?? '',
            ];

            $cadastroController = new \App\Controllers\CadastroController();

            if ($dadosFormulario['usuario_senha'] != $dadosFormulario['usuario_senha2']) {
                echo '<div class="alert alert-danger custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">Senhas não conferem</div>';
            } else {
                $resultado = $cadastroController->novoGabinete($dadosFormulario);

                if ($resultado['status'] == 'wrong_password' || $resultado['status'] == 'server_error') {
                    echo '<div class="alert alert-danger custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">' . $resultado['message'] . '</div>';
                }

                if ($resultado['status'] == 'duplicated') {
                    echo '<div class="alert alert-info custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">' . $resultado['message'] . '</div>';
                }

                if ($resultado['status'] == 'success') {
                    echo '<div class="alert alert-success custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">' . $resultado['message'] . '</div>';
                }
            }
        }

        ?>

        <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
            <div class="col-md-12 col-12">
                <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Seu nome" required>
            </div>
            <div class="col-md-12 col-12">
                <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Seu email" required>
            </div>
            <div class="col-md-6 col-6">
                <input type="password" class="form-control form-control-sm" name="usuario_senha" placeholder="Senha" required>
            </div>
            <div class="col-md-6 col-6">
                <input type="password" class="form-control form-control-sm" name="usuario_senha2" placeholder="Confirma a senha" required>
            </div>
            <div class="col-md-12 col-6">
                <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Seu telefone (com DDD)" data-mask="(00) 00000-0000" maxlength="15" required>
            </div>

            <div class="col-md-6 col-6">
                <select class="form-select form-select-sm form_dep" name="gabinete_tipo" required>
                    <option value="" disabled selected>Tipo do Gabinete</option>
                    <?php
                    $gabineteTipoController = new \App\Controllers\TipoGabineteController();
                    $buscaTipo = $gabineteTipoController->listar('nome', 'ASC', 1000);
                    foreach ($buscaTipo['data'] as $tipo) {
                        echo '<option value="' . $tipo['id'] . '">' . $tipo['nome'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6 col-12">
                <select class="form-select form-select-sm form_dep" id="estado" name="gabinete_estado" required>

                </select>
            </div>
            <div class="col-md-12 col-12">
                <input type="text" class="form-control form-control-sm" name="gabinete_nome" placeholder="Nome do deputado, senador..." required>
            </div>

            <div class="d-flex justify-content-between align-items-center">

                <a type="button" href="?secao=login" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Voltar</a>
                <button type="submit" name="btn_salvar" class="btn btn-primary confirm-action"><i class="bi bi-floppy"></i> Salvar</button>
            </div>
        </form>
        <p class="mt-3 copyright">
            &copy; 2025 | Just Solutions. Todos os direitos reservados.
        </p>
    </div>
</div>