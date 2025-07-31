<?php

namespace App\Models;

use App\Core\BaseModel;

class SituacaoCompromissoModel extends BaseModel {

    protected string $table = 'situacao_compromisso';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
        'descricao' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'gabinete' => [
            'type' => 'VARCHAR(36)',
            'required' => true,
            'foreign' => 'gabinete.id',
            'on_delete' => 'RESTRICT',
            'on_update' => 'RESTRICT'
        ],
        'criado_por' => [
            'type' => 'VARCHAR(36)',
            'required' => true,
            'foreign' => 'usuario.id',
            'on_delete' => 'RESTRICT',
            'on_update' => 'RESTRICT'
        ],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
    ];



    public function getColumns(): array {
        return $this->columns;
    }
}
