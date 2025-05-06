<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\PresentationSessionInitiated;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class VCVerifierService
{
    public function __construct(
        #[Config('verifier.url')]
        protected string $verifierUrl,
    ) {
    }

    /**
     * Initialize an OIDC for Verifiable Presentation session.
     *
     * @param string $credentialType The type of credential to request
     * @return PresentationSessionInitiated Containing the information about the presentation session
     */
    public function initializePresentationSession(string $credentialType): PresentationSessionInitiated
    {
        $response = Http::withHeaders([
          'authorizeBaseUrl' => 'openid4vp://authorize',
          'responseMode' => 'direct_post',
        ])->post($this->getVerifyEndpointUrl(), [
            "request_credentials" => [
                [
                    "format" => "jwt_vc_json",
                    "type" => $credentialType,
                ]
            ]
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Failed to start the verifiable presentation session');
        }

        $url = $response->body();
        return new PresentationSessionInitiated(url: $url, sessionId: $this->getSessionIdOfVpAuthorizeUrl($url));
    }

    /**
     * Retrieve the verifiable presentation session.
     *
     * @param string $sessionId The session ID of the verifiable presentation session
     * @return array<mixed> The session information
     */
    public function getPresentationSession(string $sessionId): array
    {
        $response = Http::get($this->getSessionEndpointUrl($sessionId));

        if ($response->failed()) {
            throw new RuntimeException('Failed to retrieve the verifiable presentation session');
        }

        $data = $response->json();
        if (empty($data) || !is_array($data)) {
            throw new RuntimeException('Invalid response format for the verifiable presentation session');
        }

        return $data;
    }

    protected function getSessionIdOfVpAuthorizeUrl(string $authorizeUrl): string
    {
        $urlParts = parse_url($authorizeUrl);
        if (empty($urlParts['query'])) {
            throw new RuntimeException('Query parameters not found in the authorize URL');
        }
        parse_str($urlParts['query'], $queryParams);

        $state = $queryParams['state'] ?? null;
        if (empty($state) || !is_string($state)) {
            throw new RuntimeException('State not found in the query parameters of the authorize URL');
        }
        return $state;
    }

    protected function getVerifyEndpointUrl(): string
    {
        return $this->verifierUrl . '/openid4vc/verify';
    }

    protected function getSessionEndpointUrl(string $sessionId): string
    {
        return $this->verifierUrl . '/openid4vc/session/' . $sessionId;
    }
}
