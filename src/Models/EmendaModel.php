<?php

namespace App\Models;

use App\Core\BaseModel;

class EmendaModel extends BaseModel {

    protected string $table = 'emenda';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'numero' => ['type' => 'VARCHAR(255)', 'required' => true],
        'ano' => ['type' => 'VARCHAR(4)', 'required' => false],
        'valor' => ['type' => 'DECIMAL(15,2)', 'required' => true],
        'situacao_id' => ['type' => 'VARCHAR(36)', 'required' => true],
        'objetivo_id' => ['type' => 'VARCHAR(36)', 'required' => true],
        'tipo' => ['type' => "ENUM('Parlamentar','Bancada','Extra')", 'required' => false, 'default' => 'Parlamentar'],
        'objeto' => ['type' => 'TEXT', 'required' => false],
        'informacoes' => ['type' => 'TEXT', 'required' => false],
        'estado' => ['type' => 'VARCHAR(2)', 'required' => true],
        'municipio' => ['type' => 'VARCHAR(100)', 'required' => false],
        'gabinete' => ['type' => 'VARCHAR(36)', 'required' => true],
        'criado_por' => ['type' => 'VARCHAR(36)', 'required' => true],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
