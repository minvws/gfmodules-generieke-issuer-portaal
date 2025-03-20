<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class VCIssuerService
{
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
        return 'http://host.docker.internal:8497/openid4vc/jwt/issue';
    }

    protected function buildIssueBody(array $subject): array
    {
        return [
            // Verplicht
            "issuerDid" => "did:jwk:eyJrdHkiOiJFQyIsImNydiI6IlAtMjU2Iiwia2lkIjoiM1lOZDlGbng5Smx5UFZZd2dXRkUzN0UzR3dJMGVHbENLOHdGbFd4R2ZwTSIsIngiOiJGb3ZZMjFMQUFPVGxnLW0tTmVLV2haRUw1YUZyblIwdWNKakQ1VEtwR3VnIiwieSI6IkNyRkpmR1RkUDI5SkpjY3BRWHV5TU8zb2h0enJUcVB6QlBCSVRZajBvZ0EifQ",

            // Verplicht
            "issuerKey" => [
                "type" => "jwk",
                "jwk" => [
                    "kty" => "EC",
                    "d" => "8jH4vwtvCw6tcBzdxQ6V7FY2L215lBGm-x3flgENx4Y",
                    "crv" => "P-256",
                    "kid" => "3YNd9Fnx9JlyPVYwgWFE37E3GwI0eGlCK8wFlWxGfpM",
                    "x" => "FovY21LAAOTlg-m-NeKWhZEL5aFrnR0ucJjD5TKpGug",
                    "y" => "CrFJfGTdP29JJccpQXuyMO3ohtzrTqPzBPBITYj0ogA"
                ]
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
//                    "id" => "did:key:z6MkrHKzgsahxBLyNAbLQyB1pcWNYC9GmywiWPgkrvntAZcj", // Wordt door mapping overschreven
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
}
