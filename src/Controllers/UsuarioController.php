<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UsuarioModel;
use App\Helpers\FileUploader;

class UsuarioController extends BaseController {

    private FileUploader $fileUploader;

    public function __construct() {
        parent::__construct(new UsuarioModel());
        $this->fileUploader = new FileUploader();
    }

    public function novoUsuario($dados) {

        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

        if (isset($dados['foto'])) {
            $result = $this->fileUploader->uploadFile('arquivos/usuario_fotos', $dados['foto'], ['image/jpeg', 'image/png'], 2);
            if ($result['status'] == 'success') {
                $dados['foto'] = $result['file_path'];
            } else {
                return $result;
            }
        }

        return $this->inserir($dados);
    }

    public function atualizarUsuario($id, $dados) {

        $busca = $this->buscar($id);

        if (isset($dados['foto'])) {
            $result = $this->fileUploader->uploadFile('arquivos/usuario_fotos', $dados['foto'], ['image/jpeg', 'image/png'], 2);
            if ($result['status'] == 'success') {
                $dados['foto'] = $result['file_path'];
            } else {
                return $result;
            }
        } else {
            $dados['foto'] = $busca['data']['foto'];
        }

        return $this->atualizar($id, $dados);
    }
}
