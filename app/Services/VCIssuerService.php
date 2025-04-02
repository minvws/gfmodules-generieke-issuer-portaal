<?php

declare(strict_types=1);

namespace App\Services;

use App\Attributes\JWK as JWKAttribute;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Http;
use Jose\Component\Core\JWK;
use JsonException;
use RuntimeException;

class VCIssuerService
{
    public function __construct(
        #[Config('issuer.name')]
        protected string $issuerName,
        #[Config('issuer.url')]
        protected string $issuerUrl,
        #[JWKAttribute('issuer.private_key_path')]
        protected JWK $privateKey,
        #[Config('issuer.custom_did')]
        protected ?string $issuerCustomDid = null,
    ) {
    }

    /**
     * @param array<string, mixed> $subject The data to be included in the credential
     * @return string The issuance URL
     * @throws RuntimeException
     */
    public function issueCredential(array $subject = []): string
    {
        $response = Http::post($this->getIssuerUrl(), $this->buildIssueBody($subject));

        if ($response->failed()) {
            throw new RuntimeException('Failed to issue credential');
        }

        return $response->body();
    }

    protected function getIssuerUrl(): string
    {
        return $this->issuerUrl . '/openid4vc/jwt/issue';
    }

    /**
     * @param array<string, mixed> $subject
     * @return array<string, mixed> POST data for the issuer API
     * @throws JsonException
     */
    protected function buildIssueBody(array $subject): array
    {
        // See also this documentation about the issuer did and key.
        // https://docs.walt.id/community-stack/issuer/api/credential-issuance/sd-jwt-vc-oid4vc#step-1-get-a-signing-key-issuer-did

        // As the issuer API doesn't store any cryptographic key material by default.
        // You need to provide the key used for signing the credential in JWK format
        // or a reference object that points to a key stored in an external KMS the Walt.id issuer API support.

        // At the moment, the issuer API supports Hashicorp Vault and Oracle KMS. In a production environment,
        // the Walt.id issuer recommends the usage of an external KMS provider to secure the key material.

        return [
            // Verplicht, moet je zelf matchen met issuer key
            "issuerDid" => $this->issuerCustomDid ?? $this->calculateDidJwkFromJwk($this->privateKey),

            // Verplicht - JWK met private key
            "issuerKey" => [
                "type" => "jwk",
                "jwk" => $this->privateKey->jsonSerialize(),
            ],

            // Credential configuration id overeenkomend met credential-issuer-metadata.conf
            "credentialConfigurationId" => "MijnGeneriekeCredential_jwt_vc_json",

            // Data van de credential
            "credentialData" => [
                "@context" => [
                    "https://www.w3.org/2018/credentials/v1"
                ],
                "type" => [
                    "VerifiableCredential",
                    "MijnGeneriekeCredential"
                ],
                "id" => "88652d6c-e93a-4ac5-afeb-0120ddb0f2b5", // Wordt door mapping overschreven
                "credentialSubject" => $subject,
                "issuer" => [
                    // id - Wordt door mapping overschreven
//                    "id" => "did:key:z6MkrHKzgsahxBLyNAbLQyB1pcWNYC9GmywiWPgkrvntAZcj",
                    "name" => "Mijn Generieke Credentials Issuer Test"
                ],
//                "issuanceDate" => "2021-08-31T00:00:00Z", // Wordt door mapping overschreven
//                "validFrom" => "2021-09-01T00:00:00Z", // Wordt door mapping overschreven
//                "expirationDate" => "2031-08-31T00:00:00Z" // Wordt door mapping overschreven
            ],

            // Optionele mapping
            "mapping" => [
                "id" => "<uuid>",
                "issuer" => [
                    "id" => "<issuerDid>"
                ],
                "credentialSubject" => [
                    "id" => "<subjectDid>"
                ],
//                "issuanceDate" => "<timestamp>",
//                "validFrom" => "<timestamp>",
//                "expirationDate" => "<timestamp-in:7d>" // Configureerbaar
            ],
            "authenticationMethod" => "PRE_AUTHORIZED",
        ];
    }

    /**
     * @throws JsonException
     */
    protected function calculateDidJwkFromJwk(JWK $jwk): string
    {
        $publicKey = $jwk->toPublic()->jsonSerialize();

        $json = json_encode($publicKey, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return 'did:jwk:' . base64_encode($json);
    }
}
