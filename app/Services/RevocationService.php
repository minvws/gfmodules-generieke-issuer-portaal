<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use RuntimeException;

class RevocationService
{
    public function __construct(
        #[Config('revocation.enabled')]
        protected bool $enabled,
        #[Config('revocation.url')]
        protected string $revocationUrl,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return array<string, mixed> Credential status information
     */
    public function getCredentialStatus(): array
    {
        try {
            $revocationIndex = $this->allocateIndex();
            return [
                "id" => "{$this->revocationUrl}#{$revocationIndex}",
                "type" => "BitstringStatusListEntry",
                "statusPurpose" => "revocation",
                "statusListIndex" => "{$revocationIndex}",
                "statusSize" => 1,
                "statusListCredential" => $this->revocationUrl,
            ];
        } catch (Exception $exception) {
            Log::error(
                'Failed to allocate revocation index',
                ['exception' => $exception]
            );
        }

        return [];
    }

    protected function allocateIndex(): int
    {
        $client = new Client([
            'base_uri' => $this->revocationUrl,
            'timeout' => 10.0,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $response = $client->post('/allocate');
            if ($response->getStatusCode() !== 200) {
                if ($response->getStatusCode() === 404) {
                    throw new RuntimeException('Revocation service not found at the specified URL.');
                }
                throw new RuntimeException('Failed to allocate revocation index: ' . $response->getReasonPhrase());
            }

            $data = $response->getBody()->getContents();
            return (int)$data;
        } catch (\Exception $e) {
            throw new RuntimeException('Invalid response from revocation service: ' . $e->getMessage());
        }
    }
}
