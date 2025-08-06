<?php

namespace App\Models;

use App\Core\BaseModel;

class GabineteModel extends BaseModel {

    protected string $table = 'gabinete';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
        'nome_slug' => ['type' => 'VARCHAR(100)', 'required' => true],
        'estado' => ['type' => 'VARCHAR(2)', 'required' => true],
        'cidade' => ['type' => 'VARCHAR(100)', 'required' => false, 'default' => null],
        'partido' => ['type' => 'VARCHAR(100)', 'required' => false, 'default' => null],
        'tipo' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'gabinete_tipo.id'],
        'ativo' => ['type' => 'BOOLEAN', 'required' => true, 'default' => true],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
