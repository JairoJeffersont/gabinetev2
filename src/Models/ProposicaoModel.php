<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class ProposicaoModel extends BaseModel {

    protected string $table = 'proposicao';

    protected array $columns = [
        'proposicao_id' => [
            'type' => 'INT',
            'required' => false,
            'primary' => true,
            'auto_increment' => false
        ],
        'proposicao_titulo' => [
            'type' => 'TEXT',
            'required' => false,
            'default' => null
        ],
        'proposicao_ano' => [
            'type' => 'INT',
            'required' => false,
            'default' => null
        ],
        'proposicao_tipo' => [
            'type' => 'VARCHAR(10)',
            'required' => false,
            'default' => null
        ],
        'proposicao_ementa' => [
            'type' => 'TEXT',
            'required' => false,
            'default' => null
        ],
        'proposicao_apresentacao' => [
            'type' => 'DATE',
            'required' => false,
            'default' => null
        ],
        'proposicao_casa' => [
            'type' => 'INT',
            'required' => false,
            'default' => null
        ],
        'proposicao_tramitacao' => [
            'type' => 'TINYINT',
            'required' => false,
            'default' => null
        ],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
