<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\PessoaModel;
use App\Helpers\FileUploader;

class PessoaController extends BaseController {

    private FileUploader $fileUploader;

    public function __construct() {
        parent::__construct(new PessoaModel());
        $this->fileUploader = new FileUploader();
    }

    public function novaPessoa($dados) {

        if (isset($dados['foto'])) {
            $result = $this->fileUploader->uploadFile('arquivos/pessoas_fotos', $dados['foto'], ['image/jpeg', 'image/png'], 2);
            if ($result['status'] == 'success') {
                $dados['foto'] = $result['file_path'];
            } else {
                return $result;
            }
        }

        return $this->inserir($dados);
    }

    public function atualizarPessoa($id, $dados) {

        $busca = $this->buscar($id);

        if (isset($dados['foto'])) {
            $result = $this->fileUploader->uploadFile('arquivos/pessoas_fotos', $dados['foto'], ['image/jpeg', 'image/png'], 2);
            if ($result['status'] == 'success') {
                if (!empty($busca['data']['foto'])) {
                    $this->fileUploader->deleteFile($busca['data']['foto']);
                }
                $dados['foto'] = $result['file_path'];
            } else {
                return $result;
            }
        } else {
            $dados['foto'] = $busca['data']['foto'];
        }

        return $this->atualizar($id, $dados);
    }

    public function apagarPessoa($id) {
        $busca = $this->buscar($id);
        if (!empty($busca['data']['foto'])) {
            $this->fileUploader->deleteFile($busca['data']['foto']);
        }
        return $this->apagar($id);
    }
}
