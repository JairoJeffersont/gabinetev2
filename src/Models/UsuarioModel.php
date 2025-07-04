<?php

namespace App\Models;

use App\Core\BaseModel;

class UsuarioModel extends BaseModel {

    protected string $table = 'usuario';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(100)', 'required' => true],
        'email' => ['type' => 'VARCHAR(100)', 'required' => true, 'unique' => true],
        'senha' => ['type' => 'VARCHAR(100)', 'required' => true],
        'token' => ['type' => 'VARCHAR(36)', 'required' => false, 'default' => null],
        'telefone' => ['type' => 'VARCHAR(20)', 'required' => true],
        'foto' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'aniversario' => ['type' => 'VARCHAR(5)', 'required' => true],
        'ativo' => ['type' => 'BOOLEAN', 'required' => true, 'default' => true],
        'gabinete' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'gabinete.id'],
        'tipo_id' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'usuario_tipo.id'],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}