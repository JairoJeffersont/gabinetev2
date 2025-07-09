<?php

namespace App\Core;

use App\Config\Database;
use PDO;

/**
 * Classe abstrata BaseModel para operações básicas de CRUD no banco de dados.
 * 
 * Esta classe fornece métodos genéricos para inserção, atualização, busca, listagem e exclusão
 * de registros em uma tabela específica do banco de dados.
 * 
 * @package App\Core
 */
abstract class BaseModel {

    /**
     * Conexão PDO com o banco de dados.
     * 
     * @var PDO
     */
    protected PDO $conn;

    /**
     * Nome da tabela associada ao modelo.
     * 
     * @var string
     */
    protected string $table;

    /**
     * Nome da coluna que é chave primária da tabela.
     * 
     * @var string
     */
    protected string $primaryKey;

    /**
     * Colunas permitidas para operações de inserção e atualização.
     * 
     * @var array
     */
    protected array $columns = [];

    /**
     * Construtor que inicializa a conexão com o banco de dados.
     */
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Insere um novo registro na tabela.
     * 
     * Filtra os dados para considerar somente as colunas definidas em $columns.
     * 
     * @param array $data Array associativo onde a chave é o nome da coluna e o valor é o dado a ser inserido.
     * 
     * @return bool Retorna true se a inserção foi bem-sucedida, false caso contrário.
     */
    public function inserir(array $data): bool {

        $filteredData = array_intersect_key($data, array_flip(array_keys($this->columns)));

        $columns = implode(", ", array_keys($filteredData));
        $placeholders = ":" . implode(", :", array_keys($filteredData));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $stmt = $this->conn->prepare($sql);

        foreach ($filteredData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    /**
     * Atualiza um registro existente na tabela.
     * 
     * Filtra os dados para considerar somente as colunas definidas em $columns.
     * 
     * @param string $colunaCondicao Nome da coluna usada para localizar o registro (default 'id').
     * @param mixed $valorCondicao Valor usado para localizar o registro na coluna de condição.
     * @param array $data Array associativo de colunas e valores para atualização.
     * 
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário ou se não houver dados para atualizar.
     */
    public function atualizar(string $colunaCondicao, $valorCondicao, array $data): bool {

        $filteredData = array_intersect_key($data, array_flip(array_keys($this->columns)));

        if (empty($filteredData)) {
            return false;
        }

        $setParts = [];
        foreach ($filteredData as $coluna => $valor) {
            $setParts[] = "$coluna = :$coluna";
        }
        $setString = implode(', ', $setParts);

        $sql = "UPDATE {$this->table} SET $setString WHERE $colunaCondicao = :condicao";

        $stmt = $this->conn->prepare($sql);

        foreach ($filteredData as $coluna => $valor) {
            $stmt->bindValue(":$coluna", $valor);
        }

        $stmt->bindValue(":condicao", $valorCondicao);

        return $stmt->execute();
    }

    /**
     * Busca um único registro pela coluna de condição e seu valor.
     * 
     * @param string $colunaCondicao Nome da coluna usada como condição (default 'id').
     * @param mixed|null $valorCondicao Valor para buscar na coluna de condição. Se for null, retorna null.
     * 
     * @return array|null Retorna o registro encontrado como array associativo, ou null se não encontrar.
     */
    public function buscar(string $colunaCondicao, $valorCondicao = null): ?array {

        if ($valorCondicao === null) {
            return null;
        }

        $sql = "SELECT * FROM {$this->table} WHERE $colunaCondicao = :condicao LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':condicao', $valorCondicao);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /**
     * Lista múltiplos registros da tabela com paginação e filtros opcionais.
     * 
     * @param string $ordenarPor Coluna para ordenar os resultados (default 'id').
     * @param string $ordem Ordem da ordenação, 'ASC' ou 'DESC' (default 'ASC').
     * @param int $itens Quantidade de itens por página (default 10).
     * @param int $pagina Número da página (default 1).
     * @param array $condicoes Array associativo de condições no formato coluna => valor para filtro (default vazio).
     * 
     * $condicoes = [
     *'gabinete' => [
     *   $_SESSION['gabinete'],
     *   1
     *]
];
     * @return array Retorna um array de registros, cada um como array associativo.
     */
    public function listas(
        string $ordenarPor = 'id',
        string $ordem = 'ASC',
        int $itens = 10,
        int $pagina = 1,
        array $condicoes = [],
        string $operador = 'OR' // novo parâmetro
    ): array {
        $offset = ($pagina - 1) * $itens;

        $whereParts = [];
        $parametros = [];
        $contador = 0;

        foreach ($condicoes as $coluna => $valores) {
            if (!is_array($valores)) {
                $valores = [$valores];
            }
            foreach ($valores as $valor) {
                $param = "{$coluna}_{$contador}";
                $whereParts[] = "$coluna = :$param";
                $parametros[$param] = $valor;
                $contador++;
            }
        }

        // garante que o operador seja seguro
        $operador = strtoupper($operador) === 'OR' ? 'OR' : 'AND';

        $whereSql = !empty($whereParts) ? ' WHERE ' . implode(" $operador ", $whereParts) : '';

        $ordem = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';
        $sql = "SELECT * FROM {$this->table}{$whereSql} ORDER BY $ordenarPor $ordem LIMIT :itens OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        foreach ($parametros as $param => $valor) {
            $stmt->bindValue(":$param", $valor);
        }

        $stmt->bindValue(':itens', $itens, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // COUNT
        $sqlCount = "SELECT COUNT(*) FROM {$this->table}{$whereSql}";
        $stmtCount = $this->conn->prepare($sqlCount);

        foreach ($parametros as $param => $valor) {
            $stmtCount->bindValue(":$param", $valor);
        }

        $stmtCount->execute();
        $total = (int) $stmtCount->fetchColumn();

        return [
            'data' => $dados,
            'total' => $total
        ];
    }




    /**
     * Apaga um registro da tabela baseado em uma condição.
     * 
     * @param string $colunaCondicao Nome da coluna usada para localizar o registro (default 'id').
     * @param mixed $valorCondicao Valor usado para localizar o registro na coluna de condição.
     * 
     * @return bool Retorna true se a exclusão foi bem-sucedida, false caso contrário.
     */
    public function apagar(string $colunaCondicao, $valorCondicao): bool {
        $sql = "DELETE FROM {$this->table} WHERE $colunaCondicao = :condicao";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':condicao', $valorCondicao);

        return $stmt->execute();
    }
}
