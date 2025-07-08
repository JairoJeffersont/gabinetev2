<link href="../css/cadastro.css" rel="stylesheet" />

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="centralizada text-center">
        <img src="public/img/logo_white.png" alt="" class="img_logo" />
        <h2 class="login_title mb-2">Mandato Digital</h2>
        <p class="text-white">Recuperar senha</p>

        <?php

        $cadastroController = new \App\Controllers\CadastroController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_enviar'])) {
            $result = $cadastroController->recuperarSenha($_POST['email']);

            if ($result['status'] == 'success') {
                echo '<div class="alert alert-success custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;" date-timeout="3">' . $result['message'] . '</div>';
            }

            if ($result['status'] == 'not_found') {
                echo '<div class="alert alert-info custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;">' . $result['message'] . '</div>';
            }
        }

        ?>

        <form id="form_login" method="post" enctype="application/x-www-form-urlencoded" class="form-group">
            <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="Digite o e-mail cadastrado" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">

                <a type="button" href="?secao=login" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Voltar</a>
                <button type="submit" name="btn_enviar" class="btn btn-primary"><i class="bi bi-envelope"></i> Enviar</button>
            </div>
        </form>

        <p class="mt-3 copyright">
            &copy; <?php echo date('Y'); ?> | Just Solutions. Todos os direitos reservados.
        </p>
    </div>
</div>