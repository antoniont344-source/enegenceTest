<?php

namespace App\Services;

//use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Serv_Copomex
{
    protected Client $client;
    protected string $token;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->token = config('services.copomex.token');

        $this->client = new Client([
            'base_uri' => 'https://api.copomex.com/',
            'timeout'  => 10,
        ]);
    }

    public function getStates(): array
    {
        try {
            $response = $this->client->get('query/get_estado_clave', [
                'query' => [
                    'token' => $this->token,
                ],
            ]);
        } catch (GuzzleException $e) {
            Log::error('Error HTTP COPOMEX', ['exception' => $e]);
            throw new \Exception('No se pudo conectar con COPOMEX');
        }

        $body = (string) $response->getBody();
        $json = json_decode($body, true);

        // JSON inválido
        if (!is_array($json)) {
            Log::error('COPOMEX invalid JSON', ['body' => $body]);
            throw new \Exception('Respuesta inválida de COPOMEX');
        }

        // Error explícito del API
        if (!array_key_exists('error', $json) || $json['error'] !== false) {
            Log::error('COPOMEX API error', $json);
            throw new \Exception(
                'COPOMEX error: ' . ($json['error_message'] ?? 'Respuesta no válida')
            );
        }

        // Validar estructura esperada
        if (
            !isset($json['response']) ||
            !isset($json['response']['estado_clave']) ||
            !is_array($json['response']['estado_clave'])
        ) {
            Log::error('Invalid COPOMEX response format', $json);
            throw new \Exception('Invalid COPOMEX response format');
        }

        return $json['response']['estado_clave'];
    }

    public function getMunicipalitiesByState(string $state): array
    {
        try {
            $response = $this->client->get(
                'query/get_municipio_por_estado/' . urlencode($state),
                [
                    'query' => [
                        'token' => $this->token,
                    ],
                ]
            );
        } catch (\Throwable $e) {
            Log::error('COPOMEX municipalities error', ['exception' => $e]);
            throw new \Exception('COPOMEX could not be contacted');
        }

        $json = json_decode((string) $response->getBody(), true);

        if (
            !is_array($json) ||
            !isset($json['error']) ||
            $json['error'] !== false ||
            !isset($json['response']['municipios']) ||
            !is_array($json['response']['municipios'])
        ) {
            Log::error('Invalid COPOMEX response format', $json ?? []);
            throw new \Exception('Invalid COPOMEX response format');
        }

        return $json['response']['municipios'];
    }
}
