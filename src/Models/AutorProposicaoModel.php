<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class AutorProposicaoModel extends BaseModel {

    protected string $table = 'proposicao_autor';

    protected array $columns = [
        'proposicao_id' => [
            'type' => 'INT',
            'required' => false,
            'default' => null
        ],
        'autor_proposicao_id' => [
            'type' => 'INT',
            'required' => false,
            'default' => null
        ],
        'autor_proposicao_nome' => [
            'type' => 'TEXT',
            'required' => false,
            'default' => null
        ],
        'autor_proposicao_nome_slug' => [
            'type' => 'TEXT',
            'required' => false,
            'default' => null
        ],
        'proposicao_ano' => [
            'type' => 'INT',
            'required' => false,
            'default' => null
        ],
        'proposicao_assinatura' => [
            'type' => 'INT',
            'required' => false,
            'default' => null
        ],
        'proposicao_proponente' => [
            'type' => 'INT',
            'required' => false,
            'default' => null
        ],
    ];

    public function getColumns(): array {
        return $this->columns;
    }

}
