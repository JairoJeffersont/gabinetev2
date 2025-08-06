<?php

namespace App\Helpers;

use App\Helpers\Logger;

/**
 * Classe utilitária para realizar requisições GET em APIs JSON ou XML,
 * com tratamento de erros e logging de falhas.
 */
class GetApi {
    /**
     * Instância do logger para registrar erros.
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
 * Realiza uma requisição GET para uma URL que retorna JSON.
 *
 * @param string $url URL da API a ser chamada.
 * @return array Retorna um array com status, dados, headers (em caso de sucesso),
 *               ou mensagem e ID do erro (em caso de falha).
 *
 * Formatos de retorno possíveis:
 * - Sucesso:
 *   [
 *     'status' => 'success',
 *     'data' => mixed,
 *     'headers' => array
 *   ]
 *
 * - Erro interno:
 *   [
 *     'status' => 'server_error',
 *     'message' => 'Erro interno do servidor.',
 *     'error_id' => string
 *   ]
 *
 * - JSON inválido:
 *   [
 *     'status' => 'invalid_response',
 *     'message' => 'Resposta da API não é um JSON válido.',
 *     'error_id' => string
 *   ]
 */
public function getJson(string $url): array {
    $ch = curl_init();

    $headers = [];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    // Captura os headers da resposta
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers) {
        $len = strlen($header);
        $header = explode(':', $header, 2);
        if (count($header) == 2) {
            $headers[trim($header[0])] = trim($header[1]);
        }
        return $len;
    });

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        $error_id = uniqid();
        $this->logger->newLog('api_error_log', $error_id . ' | ' . $error);
        return [
            'status' => 'server_error',
            'message' => 'Erro interno do servidor.',
            'error_id' => $error_id
        ];
    }

    curl_close($ch);

    $json = json_decode($response, true);

    if ($json === null) {
        $error_id = uniqid();
        $this->logger->newLog('api_error_log', $error_id . ' | Resposta inválida da API.');
        return [
            'status' => 'invalid_response',
            'message' => 'Resposta da API não é um JSON válido.',
            'error_id' => $error_id
        ];
    }

    return [
        'status' => 'success',
        'data' => $json,
        'headers' => $headers
    ];
}


    /**
     * Realiza uma requisição GET para uma URL que retorna XML.
     *
     * @param string $url URL da API a ser chamada.
     * @return array Retorna um array com status e dados convertidos (em caso de sucesso),
     *               ou mensagem e ID do erro (em caso de falha).
     *
     * Formatos de retorno possíveis:
     * - Sucesso:
     *   [
     *     'status' => 'success',
     *     'data' => mixed
     *   ]
     *
     * - Erro interno:
     *   [
     *     'status' => 'server_error',
     *     'message' => 'Erro interno do servidor.',
     *     'error_id' => string
     *   ]
     *
     * - XML inválido:
     *   [
     *     'status' => 'invalid_response',
     *     'message' => 'Resposta da API não é um XML válido.',
     *     'error_id' => string
     *   ]
     */
    public function getXml(string $url): array {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',
            'Accept: application/xml'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            $error_id = uniqid();
            $this->logger->newLog('api_error_log', $error_id . ' | ' . $error);
            return [
                'status' => 'server_error',
                'message' => 'Erro interno do servidor.',
                'error_id' => $error_id
            ];
        }

        curl_close($ch);

        libxml_use_internal_errors(true);
        $xmlObject = simplexml_load_string($response);

        if ($xmlObject === false) {
            $error_id = uniqid();
            $errors = libxml_get_errors();
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = trim($error->message);
            }
            libxml_clear_errors();

            $this->logger->newLog('api_error_log', $error_id . ' | Resposta inválida da API XML: ' . implode('; ', $errorMessages));
            return [
                'status' => 'invalid_response',
                'message' => 'Resposta da API não é um XML válido.',
                'error_id' => $error_id
            ];
        }

        $json = json_encode($xmlObject);
        $array = json_decode($json, true);

        return [
            'status' => 'success',
            'data' => $array
        ];
    }
}
