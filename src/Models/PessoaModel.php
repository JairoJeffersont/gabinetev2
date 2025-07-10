<?php

namespace App\Models;

use App\Core\BaseModel;

class PessoaModel extends BaseModel {

    protected string $table = 'pessoa';

    protected array $columns = [
        'id' => ['type' => 'VARCHAR(36)', 'required' => true, 'primary' => true],
        'nome' => ['type' => 'VARCHAR(255)', 'required' => true],
        'aniversario' => ['type' => 'VARCHAR(5)', 'required' => false, 'default' => null],
        'email' => ['type' => 'VARCHAR(255)', 'required' => true],
        'telefone' => ['type' => 'VARCHAR(255)', 'required' => false, 'default' => null],
        'endereco' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'bairro' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'municipio' => ['type' => 'VARCHAR(255)', 'required' => true],
        'estado' => ['type' => 'VARCHAR(255)', 'required' => true],
        'cep' => ['type' => 'VARCHAR(255)', 'required' => false, 'default' => null],
        'sexo' => ['type' => 'VARCHAR(255)', 'required' => false, 'default' => null],
        'facebook' => ['type' => 'VARCHAR(255)', 'required' => false, 'default' => null],
        'instagram' => ['type' => 'VARCHAR(255)', 'required' => false, 'default' => null],
        'twitter' => ['type' => 'VARCHAR(255)', 'required' => false, 'default' => null],
        'informacoes' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'profissao' => ['type' => 'VARCHAR(36)', 'required' => true],
        'importancia' => ['type' => 'VARCHAR(20)', 'required' => false, 'default' => NULL],
        'tipo_id' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'pessoa_tipo.id'],
        'orgao' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'orgao.id'],
        'gabinete' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'gabinete.id'],
        'foto' => ['type' => 'TEXT', 'required' => false, 'default' => null],
        'criado_por' => ['type' => 'VARCHAR(36)', 'required' => true, 'foreign' => 'usuario.id'],
        'criado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP'],
        'atualizado_em' => ['type' => 'TIMESTAMP', 'required' => true, 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
    ];

    public function getColumns(): array {
        return $this->columns;
    }
}
