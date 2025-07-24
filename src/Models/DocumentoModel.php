<?php

namespace App\Models;

use App\Core\BaseModel;

class DocumentoModel extends BaseModel {

    protected string $table = 'documento';

    protected array $columns = [
        'id' => [
            'type' => 'VARCHAR(36)',
            'required' => true,
            'primary' => true
        ],
        'nome' => [
            'type' => 'VARCHAR(255)',
            'required' => true
        ],
        'descricao' => [
            'type' => 'TEXT',
            'required' => false,
            'default' => null
        ],
        'ano' => [
            'type' => 'VARCHAR(4)',
            'required' => false,
            'default' => null
        ],
        'arquivo' => [
            'type' => 'TEXT',
            'required' => true,
        ],
        'tipo_id' => [
            'type' => 'VARCHAR(36)',
            'required' => true,
            'foreign' => 'tipo_documento.id'
        ],
        'orgao' => [
            'type' => 'VARCHAR(36)',
            'required' => true,
            'foreign' => 'orgao.id'
        ],
        'gabinete' => [
            'type' => 'VARCHAR(36)',
            'required' => true,
            'foreign' => 'gabinete.id'
        ],
        'criado_por' => [
            'type' => 'VARCHAR(36)',
            'required' => true,
            'foreign' => 'usuario.id'
        ],
        'criado_em' => [
            'type' => 'TIMESTAMP',
            'required' => true,
            'default' => 'CURRENT_TIMESTAMP'
        ],
        'atualizado_em' => [
            'type' => 'TIMESTAMP',
            'required' => true,
            'default' => 'CURRENT_TIMESTAMP',
            'on_update' => 'CURRENT_TIMESTAMP'
        ]
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
