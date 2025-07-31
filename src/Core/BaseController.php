<?php

namespace App\Core;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Helpers\Logger;

/**
 * Classe base para todos os controllers.
 * Fornece operações comuns como inserir, atualizar, apagar, buscar e listar registros.
 */
abstract class BaseController {

    /**
     * Instância do model que será utilizado pelo controller.
     *
     * @var BaseModel
     */
    protected BaseModel $model;

    /**
     * Logger para registrar erros internos do sistema.
     *
     * @var Logger
     */
    private Logger $logger;

    /**
     * Construtor da classe.
     *
     * @param BaseModel $model Instância do model específico da entidade.
     */
    public function __construct(BaseModel $model) {
        $this->model = $model;
        $this->logger = new Logger();
    }

    /**
     * Insere um novo registro no banco de dados.
     *
     * @param array $dados Dados do registro a ser inserido.
     * @return array Retorna o status da operação.
     */
    public function inserir(array $dados): array {
        try {
            $dados['id'] = Uuid::uuid4()->toString();
            $this->model->inserir($dados);
            return ['status' => 'success', 'message' => 'Registro inserido com sucesso.'];
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'message' => 'Registro duplicado'];
            }

            $error_id = uniqid();
            $this->logger->newLog('db_error_log', $error_id . ' | ' . $e->getMessage());
            return ['status' => 'server_error', 'message' => 'Erro interno do servidor.', 'error_id' => $error_id];
        }
    }

    /**
     * Remove um registro do banco de dados.
     *
     * @param string $colunaCondicao Nome da coluna para a condição (padrão: 'id').
     * @param mixed $valorCondicao Valor da condição.
     * @return array Retorna o status da operação.
     */
    public function apagar($valorCondicao, string $colunaCondicao = 'id'): array {
        try {
            $registro = $this->model->buscar($colunaCondicao, $valorCondicao);

            if (!$registro) {
                return ['status' => 'not_found', 'message' => 'Registro não encontrado.'];
            }

            $this->model->apagar($colunaCondicao, $valorCondicao);
            return ['status' => 'success', 'message' => 'Registro apagado com sucesso.'];
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'forbidden', 'message' => 'Não é possível apagar. Existem registros dependentes.'];
            }

            $error_id = uniqid();
            $this->logger->newLog('db_error_log', $error_id . ' | ' . $e->getMessage());
            return ['status' => 'server_error', 'message' => 'Erro interno do servidor.', 'error_id' => $error_id];
        }
    }

    /**
     * Atualiza um registro existente no banco de dados.
     *
     * @param string $colunaCondicao Nome da coluna para a condição (padrão: 'id').
     * @param mixed $valorCondicao Valor da condição.
     * @param array $dados Dados atualizados do registro.
     * @return array Retorna o status da operação.
     */
    public function atualizar($valorCondicao, array $dados, string $colunaCondicao = 'id'): array {
        try {
            $registro = $this->model->buscar($colunaCondicao, $valorCondicao);

            if (!$registro) {
                return ['status' => 'not_found', 'message' => 'Registro não encontrado.'];
            }

            $this->model->atualizar($colunaCondicao, $valorCondicao, $dados);
            return ['status' => 'success', 'message' => 'Registro atualizado com sucesso.'];
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'message' => 'Registro duplicado'];
            }

            $error_id = uniqid();
            $this->logger->newLog('db_error_log', $error_id . ' | ' . $e->getMessage());
            return ['status' => 'server_error', 'message' => 'Erro interno do servidor.', 'error_id' => $error_id];
        }
    }

    /**
     * Lista registros com paginação e ordenação.
     *
     * @param string $ordenarPor Coluna de ordenação (padrão: 'id').
     * @param string $ordem Ordem (ASC ou DESC).
     * @param int $itens Quantidade de itens por página.
     * @param int $pagina Número da página atual.
     * @param array $condicoes Filtros de busca (coluna => valor).
     * @return array Retorna os dados, total de páginas e status.
     */
    public function listar(string $ordenarPor = 'id', string $ordem = 'ASC', int $itens = 10, int $pagina = 1, array $condicoes = [], $operador = 'OR'): array {
        try {
            $resultado = $this->model->listas($ordenarPor, $ordem, $itens, $pagina, $condicoes, $operador);

            if (empty($resultado['data'])) {
                return ['status' => 'empty', 'message' => 'Nenhum registro encontrado'];
            }

            $totalRegistros = $resultado['total'] ?? 0;
            $totalPaginas = ceil($totalRegistros / $itens);

            return [
                'status' => 'success',
                'data' => $resultado['data'],
                'total_paginas' => $totalPaginas
            ];
        } catch (Exception $e) {
            $error_id = uniqid();
            $this->logger->newLog('db_error_log', $error_id . ' | ' . $e->getMessage());
            return [
                'status' => 'server_error',
                'message' => 'Erro interno do servidor.',
                'error_id' => $error_id
            ];
        }
    }

    /**
     * Busca um único registro pelo valor de uma coluna.
     *
     * @param string $colunaCondicao Nome da coluna (padrão: 'id').
     * @param mixed $valorCondicao Valor a ser buscado.
     * @return array Retorna o status e os dados encontrados.
     */
    public function buscar($valorCondicao, string $colunaCondicao = 'id'): array {
        try {
            $registro = $this->model->buscar($colunaCondicao, $valorCondicao);

            if (!$registro) {
                return ['status' => 'not_found', 'message' => 'Registro não encontrado.'];
            }

            return ['status' => 'success', 'data' => $registro];
        } catch (Exception $e) {
            $error_id = uniqid();
            $this->logger->newLog('db_error_log', $error_id . ' | ' . $e->getMessage());
            return ['status' => 'server_error', 'message' => 'Erro interno do servidor.', 'error_id' => $error_id];
        }
    }
}
