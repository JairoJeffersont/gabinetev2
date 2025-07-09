<?php

namespace App\Models;

use App\Core\BaseModel;

class OrgaoModel extends BaseModel {

    protected string $table = 'orgao';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'TEXT', 'required' => true, 'unique' => true],
        'email' => ['type' => 'VARCHAR(255)', 'required' => false, 'default' => null],
        'telefone' => ['type' => 'VARCHAR(255)', 'required' => false],
        'endereco' => ['type' => 'TEXT', 'required' => false],
        'municipio' => ['type' => 'VARCHAR(255)', 'required' => true],
        'estado' => ['type' => 'VARCHAR(255)', 'required' => true],
        'cep' => ['type' => 'VARCHAR(255)', 'required' => false],
        'tipo_id' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'orgao_tipo.id'],
        'informacoes' => ['type' => 'TEXT', 'required' => false],
        'site' => ['type' => 'TEXT', 'required' => false],
        'instagram' => ['type' => 'TEXT', 'required' => false],
        'twitter' => ['type' => 'TEXT', 'required' => false],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        'criado_por' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'usuario.id'],
        'gabinete' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'gabinete.id'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
