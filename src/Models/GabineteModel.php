<?php

namespace App\Models;

use App\Core\BaseModel;

class GabineteModel extends BaseModel {

    protected string $table = 'gabinete';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
        'estado' => ['type' => 'VARCHAR(2)', 'required' => true, 'foreign' => 'estado.id'],
        'cidade' => ['type' => 'VARCHAR(100)', 'required' => true, 'foreign' => 'cidade.id'],
        'partido' => ['type' => 'VARCHAR(100)', 'required' => true],
        'tipo' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'gabinete_tipo.id'],
        'ativo' => ['type' => 'BOOLEAN', 'required' => true, 'default' => true],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
