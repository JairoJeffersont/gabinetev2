<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class CompromissoModel extends BaseModel {

    protected string $table = 'compromisso';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'titulo' => ['type' => 'VARCHAR(255)', 'required' => true],
        'descricao' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'data_hora' => ['type' => 'DATETIME', 'required' => true, 'unique' => true],
        'endereco' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'estado' => ['type' => 'VARCHAR(2)', 'required' => true],
        'municipio' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'tipo_id' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'tipo_compromisso.id'],
        'situacao_id' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'situacao_compromisso.id'],
        'gabinete' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'gabinete.id'],
        'criado_por' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'usuario.id'],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }

}
