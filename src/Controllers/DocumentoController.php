<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\DocumentoModel;
use App\Helpers\FileUploader;

class DocumentoController extends BaseController {

    private FileUploader $fileUploader;

    public function __construct() {
        parent::__construct(new DocumentoModel());
        $this->fileUploader = new FileUploader();
    }

    public function novoDocumento($dados) {

        $mimeTypes = [
            'application/pdf', // PDF
            'application/msword', // Word (doc)
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // Word (docx)
            'application/vnd.ms-excel', // Excel (xls)
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // Excel (xlsx)
        ];


        if (isset($dados['arquivo'])) {
            $result = $this->fileUploader->uploadFile('arquivos/documentos', $dados['arquivo'], $mimeTypes, 20, false);
            if ($result['status'] == 'success') {
                $dados['arquivo'] = $result['file_path'];
            } else {
                return $result;
            }
        }

        return $this->inserir($dados);
    }


    public function atualizarDocumento($id, $dados) {

        $mimeTypes = [            
            'application/pdf', // PDF
            'application/msword', // Word (doc)
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // Word (docx)
            'application/vnd.ms-excel', // Excel (xls)
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // Excel (xlsx)
        ];


        $busca = $this->buscar($id);

        if (isset($dados['arquivo'])) {
            $result = $this->fileUploader->uploadFile('arquivos/documentos', $dados['arquivo'], $mimeTypes, 5, false);
            if ($result['status'] == 'success') {
                if (!empty($busca['data']['arquivo'])) {
                    $this->fileUploader->deleteFile($busca['data']['arquivo']);
                }
                $dados['arquivo'] = $result['file_path'];
            } else {
                return $result;
            }
        } else {
            $dados['arquivo'] = $busca['data']['arquivo'];
        }

        return $this->atualizar($id, $dados);
    }

        public function apagarDocumento($id) {
        $busca = $this->buscar($id);
        if (!empty($busca['data']['arquivo'])) {
            print_r($this->fileUploader->deleteFile($busca['data']['arquivo']));
        }
        return $this->apagar($id);
    }
}
