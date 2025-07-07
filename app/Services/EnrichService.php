<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Log;
use JsonException;

class EnrichService
{
    public function __construct(
        #[Config('source_connector.url')]
        protected string $sourceConnectorUrl,
        #[Config('source_connector.mtls_cert')]
        protected ?string $mtlsCert,
        #[Config('source_connector.mtls_key')]
        protected ?string $mtlsKey,
        #[Config('source_connector.mtls_cacert')]
        protected ?string $mtlsCacert,
        #[Config('source_connector.mtls_verify_peer_cert')]
        protected bool $mtlsVerifyPeerCert = true,
    ) {
    }

    /**
     * @param mixed[] $data
     * @return mixed[]
     * @throws GuzzleException
     */
    public function enrich(array $data): array
    {
        $config = [
            'base_uri' => $this->sourceConnectorUrl,
            'verify' => $this->mtlsVerifyPeerCert,
            'timeout' => 10.0,
            'connect_timeout' => 5.0,
            'http_errors' => false,     // Disable exceptions for HTTP errors
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        if ($this->mtlsCert) {
            $config['cert'] = $this->mtlsCert;
            $config['ssl_key'] = $this->mtlsKey;
            $config['verify'] = $this->mtlsCacert ?? true;
        }

        try {
            $client = new Client($config);
            $response = $client->post('/enrich', [
                'json' => $data,
            ]);
        } catch (GuzzleException $e) {
            Log::error('EnrichService GuzzleException: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception("Failed to connect to Enrich client: " . $e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Failed to enrich data: HTTP
                {$response->getStatusCode()} - {$response->getReasonPhrase()}");
        }

        try {
            $enrichedData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($enrichedData)) {
                Log::error("Invalid response from source connector: " . json_encode($enrichedData));
                throw new Exception('Invalid response from source connector: ' . json_encode($enrichedData));
            }
        } catch (JsonException $e) {
            Log::error('EnrichService JsonException: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception("Failed to decode JSON response: " . $e->getMessage());
        }

        return $enrichedData;
    }
}
