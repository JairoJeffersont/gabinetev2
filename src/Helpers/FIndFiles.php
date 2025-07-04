<?php

namespace App\Helpers;

class FindFiles {

    /**
     * Lista arquivos de uma pasta com informações: nome, tamanho legível, data criação e URL.
     *
     * @param string $caminhoPasta Caminho absoluto da pasta no servidor.
     * @param string $urlBase URL base para construir a URL do arquivo (ex: http://meusite.com/arquivos).
     * @return array Array de arquivos com ['nome', 'tamanho', 'data_criacao', 'url'].
     */
    public function listarArquivos(string $caminhoPasta, string $urlBase): array {
        $resultado = [];

        if (!is_dir($caminhoPasta)) {
            return $resultado;
        }

        $arquivos = scandir($caminhoPasta);

        foreach ($arquivos as $arquivo) {
            if ($arquivo === '.' || $arquivo === '..') {
                continue;
            }

            $caminhoCompleto = rtrim($caminhoPasta, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $arquivo;

            if (is_file($caminhoCompleto)) {
                $tamanhoBytes = filesize($caminhoCompleto);
                $tamanhoFormatado = $this->formatarTamanho($tamanhoBytes);
                $dataCriacao = date("d/m/Y H:i:s", filectime($caminhoCompleto));
                $urlArquivo = rtrim($urlBase, '/') . '/' . $arquivo;

                $resultado[] = [
                    'nome' => $arquivo,
                    'tamanho' => $tamanhoFormatado,
                    'data_criacao' => $dataCriacao,
                    'url' => $urlArquivo
                ];
            }
        }

        return $resultado;
    }

    /**
     * Formata o tamanho do arquivo em MB, KB ou Bytes.
     *
     * @param int $bytes
     * @return string
     */
    private function formatarTamanho(int $bytes): string {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' Bytes';
        }
    }
}
