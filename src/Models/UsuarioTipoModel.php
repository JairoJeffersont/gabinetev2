<?php

namespace App\Models;

use App\Core\BaseModel;

class UsuarioTipoModel extends BaseModel {

    protected string $table = 'usuario_tipo';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
