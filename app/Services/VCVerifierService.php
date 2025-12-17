<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\PresentationSessionInitiated;
use App\Dto\PresentationSessionResult;
use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JsonException;
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
     * @param string|null $successRedirectUrl The URL to redirect to on success
     * @param string|null $errorRedirectUrl The URL to redirect to on error
     * @return PresentationSessionInitiated Containing the information about the presentation session
     * @throws ConnectionException
     */
    public function initializePresentationSession(
        string $credentialType,
        ?string $successRedirectUrl = null,
        ?string $errorRedirectUrl = null,
    ): PresentationSessionInitiated {
        $additionalHeaders = [];

        if (!empty($successRedirectUrl)) {
            $additionalHeaders['successRedirectUri'] = $successRedirectUrl;
        }
        if (!empty($errorRedirectUrl)) {
            $additionalHeaders['errorRedirectUri'] = $errorRedirectUrl;
        }

        /** @var Response $response */
        $response = Http::withHeaders([
            'authorizeBaseUrl' => 'openid4vp://authorize',
            'responseMode' => 'direct_post',
            ...$additionalHeaders,
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
     * @return PresentationSessionResult The session information
     * @throws ConnectionException|JsonException
     */
    public function getPresentationSession(string $sessionId): PresentationSessionResult
    {
        /** @var Response $response */
        $response = Http::get($this->getSessionEndpointUrl($sessionId));

        if ($response->failed()) {
            throw new RuntimeException('Failed to retrieve the verifiable presentation session');
        }

        $data = $response->json();
        if (empty($data) || !is_array($data)) {
            throw new RuntimeException('Invalid response format for the verifiable presentation session');
        }

        return PresentationSessionResult::parse($data);
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
