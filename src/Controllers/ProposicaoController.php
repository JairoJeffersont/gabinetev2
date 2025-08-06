<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Helpers\GetApi;
use App\Helpers\Validation;
use App\Models\ProposicaoModel;


class ProposicaoController extends BaseController {

    private GetApi $getApi;
    private AutorProposicaoController $autorProposicaoController;

    public function __construct() {
        parent::__construct(new ProposicaoModel());
        $this->getApi = new GetApi();
        $this->autorProposicaoController = new AutorProposicaoController();
    }

    public function atualizarProposicoesCD($data) {
        $proposicoesJson = $this->getApi->getJson('https://dadosabertos.camara.leg.br/api/v2/proposicoes?dataApresentacaoInicio=' . $data . '&dataApresentacaoFim=' . $data . '&itens=100&ordem=ASC&ordenarPor=id');

        if (!isset($proposicoesJson['data']['dados']) || empty($proposicoesJson['data']['dados'])) {
            return ['status' => 'empty', 'message' => 'Nenhuma proposição nova encontrada'];
        }

        foreach ($proposicoesJson['data']['dados'] as $proposicao) {
            $dados = [
                'proposicao_id' => $proposicao['id'],
                'proposicao_titulo' => $proposicao['siglaTipo'] . ' ' . $proposicao['numero'] . '/' . $proposicao['ano'],
                'proposicao_ano' => $proposicao['ano'],
                'proposicao_tipo' => $proposicao['siglaTipo'],
                'proposicao_ementa' => $proposicao['ementa'],
                'proposicao_apresentacao' => $data,
                'proposicao_tramitacao' => 1,
                'proposicao_casa' => 1
            ];

            $this->inserir($dados);
            $this->autorProposicaoController->atualizarAutoresCD($proposicao['id']);
        }

        return ['status' => 'success', 'message' => 'Proposições atualizadas com sucesso.'];
    }
}
