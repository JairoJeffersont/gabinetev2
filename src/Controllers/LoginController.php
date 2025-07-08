<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UsuarioModel;
use Dotenv\Dotenv;


class LoginController extends BaseController {

    public function __construct() {
        parent::__construct(new UsuarioModel());
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
    }

    public function Logar($email, $senha) {

        $busca = $this->buscar($email, 'email');

        if ($busca['status'] == 'not_found') {
            return $busca;
        }

        if (!password_verify($senha, $busca['data']['senha'])) {
            return ['status' => 'wrong_password', 'message' => 'Senha incorreta.'];
        }

        if (!$busca['data']['ativo']) {
            return ['status' => 'user_deactived', 'message' => 'Usuário desativado.'];
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_regenerate_id(true);
        $_SESSION['id'] = $busca['data']['id'];
        $_SESSION['nome'] = $busca['data']['nome'];
        $_SESSION['gabinete'] = $busca['data']['gabinete'];
        $_SESSION['tipo'] = $busca['data']['tipo_id'];

        $_SESSION['login_time'] = time();
        $_SESSION['session_expire'] = (int) $_ENV['SESSION_EXPIRATION_TIME'];
        return ['status' => 'success', 'message' => 'Login feito com sucesso.'];
    }

    public function Logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header('Location: ?secao=login');
        exit;
    }
}
