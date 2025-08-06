<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class DeputadoModel extends BaseModel {

    protected string $table = 'deputado';

    protected array $columns = [
        'deputado_id' => [
            'type' => 'INT',
            'required' => false,
            'primary' => true,
            'auto_increment' => false
        ],
        'deputado_nome' => [
            'type' => 'TEXT',
            'required' => false,
            'default' => null
        ],
        'deputado_nascimento' => [
            'type' => 'DATE',
            'required' => false,
            'default' => null
        ],
        'deputado_vivo' => [
            'type' => 'TINYINT',
            'required' => false,
            'default' => null
        ],
        'deputado_foto' => [
            'type' => 'TEXT',
            'required' => false,
            'default' => null
        ],
    ];

    public function getColumns(): array {
        return $this->columns;
    }

}
