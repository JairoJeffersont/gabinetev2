<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Helpers\Logger;

/**
 * Classe para geração de arquivos Excel (.xlsx) e CSV a partir de arrays de dados.
 * 
 * Utiliza PhpSpreadsheet para geração do arquivo Excel e funções nativas do PHP para CSV.
 */
class FileExportHelper {

    /**
     * Logger para registrar erros e eventos.
     * 
     * @var Logger
     */
    private Logger $logger;

    /**
     * Construtor da classe.
     * Inicializa o logger.
     */
    public function __construct() {
        $this->logger = new Logger();
    }

    /**
     * Gera um arquivo Excel (.xlsx) a partir dos dados fornecidos.
     * 
     * @param array $dados Array de arrays associativos representando as linhas e colunas.
     *                    Exemplo:
     *                    [
     *                      ['nome' => 'João', 'idade' => 30],
     *                      ['nome' => 'Maria', 'idade' => 25]
     *                    ]
     * @param string $nome Nome base para o arquivo gerado (sem extensão).
     * @param string $pasta Caminho da pasta onde o arquivo será salvo.
     * 
     * @return array Retorna um array com o status e o caminho do arquivo gerado ou dados do erro.
     *               Exemplo sucesso:
     *               [
     *                 'status' => 'success',
     *                 'file_path' => '1688520487_usuarios.xlsx'
     *               ]
     *               Exemplo erro:
     *               [
     *                 'status' => 'server_error',
     *                 'message' => 'Mensagem do erro',
     *                 'error_id' => 'id único do erro'
     *               ]
     */
    public function gerarXlsx(array $dados, string $nome, string $pasta) {
        if (empty($dados)) {
            return [
                'status' => 'invalid_response',
                'message' => 'Dados vazios para gerar XLSX.',
                'error_id' => uniqid()
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Obtém todas as chaves únicas para gerar cabeçalhos
        $headers = [];
        foreach ($dados as $row) {
            $headers = array_unique(array_merge($headers, array_keys($row)));
        }

        // Escreve os cabeçalhos na primeira linha
        $col = 1;
        foreach ($headers as $header) {
            $colLetra = $this->colunaParaLetra($col);
            $sheet->setCellValue("{$colLetra}1", $header);
            $col++;
        }

        // Preenche as linhas com os dados
        $rowNum = 2;
        foreach ($dados as $row) {
            $col = 1;
            foreach ($headers as $header) {
                $colLetra = $this->colunaParaLetra($col);
                $sheet->setCellValue("{$colLetra}{$rowNum}", $row[$header] ?? '');
                $col++;
            }
            $rowNum++;
        }

        $timestamp = time();
        $arquivo = "{$timestamp}_{$nome}.xlsx";

        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $caminhoCompleto = rtrim($pasta, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $arquivo;

        $writer = new Xlsx($spreadsheet);

        try {
            $writer->save($caminhoCompleto);
            return [
                'status' => 'success',
                'file_path' => $arquivo
            ];
        } catch (\Exception $e) {
            $error_id = uniqid();
            $this->logger->newLog('excel_error_log', $error_id . ' | ' . $e->getMessage());
            return [
                'status' => 'server_error',
                'message' => $e->getMessage(),
                'error_id' => $error_id
            ];
        }
    }

    /**
     * Converte um número de coluna (1-based) para sua letra correspondente no Excel.
     * Exemplo: 1 -> A, 27 -> AA
     * 
     * @param int $col Número da coluna (1 para A, 2 para B, etc).
     * @return string Letra(s) da coluna no formato Excel.
     */
    private function colunaParaLetra(int $col): string {
        $letra = '';
        while ($col > 0) {
            $mod = ($col - 1) % 26;
            $letra = chr(65 + $mod) . $letra;
            $col = (int)(($col - $mod) / 26);
        }
        return $letra;
    }

    /**
     * Gera um arquivo CSV a partir dos dados fornecidos.
     * 
     * @param array $dados Array de arrays associativos representando as linhas e colunas.
     * @param string $nome Nome base para o arquivo gerado (sem extensão).
     * @param string $pasta Caminho da pasta onde o arquivo será salvo.
     * 
     * @return array Retorna um array com o status e o caminho do arquivo gerado ou dados do erro.
     *               Exemplo sucesso:
     *               [
     *                 'status' => 'success',
     *                 'file_path' => '1688520487_usuarios.csv'
     *               ]
     *               Exemplo erro:
     *               [
     *                 'status' => 'server_error',
     *                 'message' => 'Mensagem do erro',
     *                 'error_id' => 'id único do erro'
     *               ]
     */
    public function gerarCsv(array $dados, string $nome, string $pasta) {
        if (empty($dados)) {
            $error_id = uniqid();
            return [
                'status' => 'invalid_response',
                'message' => 'Dados vazios para gerar CSV.',
                'error_id' => $error_id
            ];
        }

        // Obtém todas as chaves únicas para gerar cabeçalhos
        $headers = [];
        foreach ($dados as $row) {
            $headers = array_unique(array_merge($headers, array_keys($row)));
        }

        $timestamp = time();
        $arquivo = "{$timestamp}_{$nome}.csv";

        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $caminhoCompleto = rtrim($pasta, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $arquivo;

        try {
            $file = fopen($caminhoCompleto, 'w');

            // Escreve cabeçalhos no CSV
            fputcsv($file, $headers);

            // Escreve linhas de dados no CSV
            foreach ($dados as $row) {
                $linha = [];
                foreach ($headers as $header) {
                    $linha[] = $row[$header] ?? '';
                }
                fputcsv($file, $linha);
            }

            fclose($file);

            return [
                'status' => 'success',
                'file_path' => $arquivo
            ];
        } catch (\Exception $e) {
            $error_id = uniqid();
            $this->logger->newLog('csv_error_log', $error_id . ' | ' . $e->getMessage());
            return [
                'status' => 'server_error',
                'message' => $e->getMessage(),
                'error_id' => $error_id
            ];
        }
    }
}
