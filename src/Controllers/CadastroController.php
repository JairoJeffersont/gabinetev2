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
            'senha' => password_hash($dados['usuario_senha'], PASSWORD_DEFAULT),
            'telefone' => $dados['usuario_telefone'],
            'ativo' => 1,
            'gabinete' => $buscaGabinete['data'][0]['id'],
            'tipo_id' => 1,
        ];

        return $this->usuarioController->inserir($usuario);
        return ['status' => 'success', 'message' => 'Gabinete cadastrado com sucesso.'];
    }
}