<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Container\Attributes\Config;
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

            if ($response->getStatusCode() !== 200) {
                return $data;
            }

            try {
                $enrichedData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                return $data;
            }
        } catch (GuzzleException $e) {
            return $data;
        }

        return $enrichedData;
    }
}
