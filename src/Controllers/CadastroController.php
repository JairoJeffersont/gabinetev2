<?php

namespace App\Controllers;

class CadastroController {

    protected $usuarioController;
    protected $gabineteController;

    public function __construct() {
        $this->gabineteController = new GabineteController();
        $this->usuarioController = new UsuarioController();
    }

    public function novoGabinete($dados) {

        $buscaGabinete = $this->gabineteController->buscar($dados['gabinete_nome'], 'nome');

        if (!empty($buscaGabinete['data'])) {
            return ['status' => 'duplicated', 'message' => 'Gabinete já cadastrado.'];
        }

        $gabinete = [
            'nome' => $dados['gabinete_nome'],
            'nome_slug' => $dados['gabinete_nome_slug'],
            'tipo' => $dados['gabinete_tipo'],
            'estado' => $dados['gabinete_estado']
        ];

        $result = $this->gabineteController->inserir($gabinete);

        if ($result['status'] == 'success') {
            $this->novoUsuario($dados);
            return ['status' => 'success', 'message' => 'Gabinete cadastrado com sucesso.'];
        } else {
            return $result;
        }
    }

    public function novoUsuario($dados) {
        $buscaUsuario = $this->usuarioController->buscar($dados['usuario_email'], 'email');
        $buscaGabinete = $this->gabineteController->listar('criado_em', 'desc', 1, 1);

        if (!empty($buscaUsuario['data'])) {
            $this->gabineteController->apagar($buscaGabinete['data'][0]['id'], 'id');
            return ['status' => 'duplicated', 'message' => 'Usuário já cadastrado.'];
        }

        $usuario = [
            'nome' => $dados['usuario_nome'],
            'email' => $dados['usuario_email'],
            'senha' => $dados['usuario_senha'],
            'telefone' => $dados['usuario_telefone'],
            'ativo' => 1,
            'gabinete' => $buscaGabinete['data'][0]['id'],
            'tipo_id' => 1,
        ];

        return $this->usuarioController->novoUsuario($usuario);
        return ['status' => 'success', 'message' => 'Gabinete cadastrado com sucesso.'];
    }

    public function recuperarSenha($email) {
        $buscaUsuario = $this->usuarioController->buscar($email, 'email');

        if (empty($buscaUsuario['data'])) {
            return ['status' => 'not_found', 'message' => 'Email não encontrado.'];
        }

        $dados = [
            'token' => uniqid()
        ];

        $result = $this->usuarioController->atualizar($email, $dados, 'email');

        $token = $dados['token'];
        include '../src/Helpers/recovery_template.php';

        $email = new \App\Helpers\EmailService();
        $email->sendMail($buscaUsuario['data']['email'], 'Email de recuperação', $html);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Se o e-mail estiver correto, você receberá uma mensagem com instruções de recuperação.'];
        } else {
            return $result;
        }
    }

    public function novaSenha($token, $senha) {
        $buscaUsuario = $this->usuarioController->buscar($token, 'token');

        if (empty($buscaUsuario['data'])) {
            return ['status' => 'not_found', 'message' => 'Token inválido.'];
        }

        $dados = [
            'token' => null,
            'senha' => password_hash($senha, PASSWORD_DEFAULT)
        ];

        $result = $this->usuarioController->atualizar($token, $dados, 'token');

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Senha atualizada com sucesso.'];
        } else {
            return $result;
        }
    }
}
