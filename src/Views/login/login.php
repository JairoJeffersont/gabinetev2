<link href="../css/login.css" rel="stylesheet" />

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="centralizada text-center">
        <img src="public/img/logo_white.png" alt="" class="img_logo" />
        <h2 class="login_title mb-2">Mandato Digital</h2>
        <p class="text-white">Gestão de gabinete</p>

        <?php

        $loginController = new \App\Controllers\LoginController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_logar'])) {
            $resultado = $loginController->Logar($_POST['email'], $_POST['senha']);

            if ($resultado['status'] == 'wrong_password' || $resultado['status'] == 'server_error') {
                echo '<div class="alert alert-danger custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;">' . $resultado['message'] . '</div>';
            }

            if ($resultado['status'] == 'not_found') {
                echo '<div class="alert alert-danger custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;">Usuário não encontrado.</div>';
            }

            if ($resultado['status'] == 'user_deactived') {
                echo '<div class="alert alert-info custom-alert px-2 py-1 " role="alert" style="border-radius: 15px;">' . $resultado['message'] . '</div>';
            }

            if ($resultado['status'] == 'success') {
                header('Location: ?secao=home');
            }
        }

        ?>

        <form id="form_login" method="post" enctype="application/x-www-form-urlencoded" class="form-group">
            <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" value="jairojeffersont@gmail.com" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" value="intell01" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" name="btn_logar" class="btn"><i class="bi bi-door-open"></i> Entrar</button>
            </div>
        </form>
        <p class="mt-3 link"> <a href="?secao=recuperar-senha"><i class="bi bi-lock"></i> Esqueceu a senha?</a> | <a href="?secao=cadastro"><i class="bi bi-plus"></i>Cadastre seu gabinete</a></p>
        <p class="mt-3 copyright">
            &copy; <?php echo date('Y'); ?> | Just Solutions. Todos os direitos reservados.
        </p>
    </div>
</div>