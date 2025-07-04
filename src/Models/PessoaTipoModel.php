<?php

namespace App\Models;

use App\Core\BaseModel;

class PessoaTipoModel extends BaseModel {

    protected string $table = 'pessoa_tipo';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
        'gabinete' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'gabinete.id'],
        'criado_por' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'usuario.id'],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
