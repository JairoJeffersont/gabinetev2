<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\AutorProposicaoModel;
use App\Helpers\GetApi;
use App\Helpers\Validation;

class AutorProposicaoController extends BaseController {


    private GetApi $getApi;
    private Validation $validation;

    public function __construct() {
        parent::__construct(new AutorProposicaoModel());
        $this->getApi = new GetApi();
        $this->validation = new Validation();
    }


    public function atualizarAutoresCD($id) {

        $autoresJson = $this->getApi->getJson('https://dadosabertos.camara.leg.br/api/v2/proposicoes/' . $id . '/autores');

        if (!isset($autoresJson['data']['dados']) || empty($autoresJson['data']['dados'])) {
            return false;
        }

        foreach ($autoresJson['data']['dados'] as $autor) {
            $dados = [
                'proposicao_id' => $id,
                'autor_proposicao_id' => preg_replace('~.*/(\d+)$~', '$1', $autor['uri']),
                'autor_proposicao_nome' => $autor['nome'],
                'autor_proposicao_nome_slug' => $this->validation->slug($autor['nome']),
                'proposicao_ano' => date('Y'),
                'proposicao_assinatura' => $autor['ordemAssinatura'],
                'proposicao_proponente' => $autor['proponente'],
            ];

            $this->inserir($dados);
        }
        return true;
    }
}
