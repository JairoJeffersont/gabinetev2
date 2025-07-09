<link href="../css/cadastro.css" rel="stylesheet" />

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="centralizada text-center">
        <img src="../img/logo_white.png" alt="" class="img_logo" />
        <h2 class="login_title mb-1">Mandato Digital</h2>
        <h6 class="host mb-3">Novo usuário | Todos os campos são obrigatórios</h6>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
            $usuarioController = new \App\Controllers\UsuarioController();


            if ($_POST['usuario_senha'] == $_POST['usuario_senha2']) {
                $dados = [
                    'nome' => $_POST['usuario_nome'],
                    'email' => $_POST['usuario_email'],
                    'telefone' => $_POST['usuario_telefone'],
                    'aniversario' => $_POST['usuario_aniversario'],
                    'senha' => $_POST['usuario_senha'],
                    'tipo_id' => '6',
                    'gabinete' => $_GET['gabinete'],
                    'ativo' => 0
                ];

                $resultado = $usuarioController->novoUsuario($dados);

                if ($resultado['status'] == 'server_error') {
                    echo '<div class="alert alert-danger custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">' . $resultado['message'] . '</div>';
                }

                if ($resultado['status'] == 'duplicated') {
                    echo '<div class="alert alert-info custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">' . $resultado['message'] . '</div>';
                }

                if ($resultado['status'] == 'success') {
                    echo '<div class="alert alert-success custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">' . $resultado['message'] . '</div>';
                }
            } else {
                echo '<div class="alert alert-danger custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" data-timeout="2">Senhas não conferem</div>';
            }
        }

        ?>




        <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
            <div class="col-md-12 col-12">
                <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Nome" required>
            </div>
            <div class="col-md-12 col-12">
                <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Email" required>
            </div>
            <div class="col-md-6 col-6">
                <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Celular (com DDD)" data-mask="(00) 00000-0000" maxlength="15" required>
            </div>
            <div class="col-md-6 col-6">
                <input type="text" class="form-control form-control-sm" name="usuario_aniversario" placeholder="dd/mm" data-mask="00/00" required>
            </div>
            <div class="col-md-6 col-6">
                <input type="password" class="form-control form-control-sm" name="usuario_senha" placeholder="Senha" required>
            </div>
            <div class="col-md-6 col-6">
                <input type="password" class="form-control form-control-sm" name="usuario_senha2" placeholder="Confirme a senha" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">

                <a type="button" href="?secao=login" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Voltar</a>
                <button type="submit" name="btn_salvar" class="btn btn-primary confirm-action"><i class="bi bi-floppy"></i> Salvar</button>
            </div>
        </form>

    </div>
</div>