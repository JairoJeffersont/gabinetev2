<?php

namespace App\Models;

use App\Core\BaseModel;

class TipoGabineteModel extends BaseModel {

    protected string $table = 'gabinete_tipo';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
