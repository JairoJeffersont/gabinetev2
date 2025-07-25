<?php

namespace App\Models;

use App\Core\BaseModel;

class SituacaoEmendaModel extends BaseModel {

    protected string $table = 'situacao_emendas';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
        'descricao' => ['type' => 'TEXT', 'required' => false],
        'gabinete' => ['type' => 'VARCHAR(36)', 'required' => true],
        'criado_por' => ['type' => 'VARCHAR(36)', 'required' => true],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
