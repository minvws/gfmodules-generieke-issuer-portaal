<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MakeCredential extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-credential';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //

        // Choose credential...

        // http://localhost:8497/openid4vc/jwt/issue
        // http://localhost:8497/openid4vc/sdjwt/issue
        // er is ook nog een batch endpoint

//        $response = Http::post('http://issuer-api:8497/', [
        $response = Http::post('http://host.docker.internal:8497/openid4vc/jwt/issue', $this->exampleBodyTwo());

        if ($response->failed()) {
            $this->error('Failed to create credential');
            $this->error($response->body());
            return;
        }

        $this->info('Credential created, here is the offer uri:');
        $this->info($response->body());
    }

    /**
     * @return array<string, mixed>
     */
    protected function exampleBodyOne(): array
    {
        return [
            // Verplicht
            "issuerDid" => "did:jwk:eyJrdHkiOiJFQyIsImNydiI6IlAtMjU2Iiwia2lkIjoiM1lOZDlGbng5Smx5UFZZd2dXRkUzN0UzR3dJMGVHbENLOHdGbFd4R2ZwTSIsIngiOiJGb3ZZMjFMQUFPVGxnLW0tTmVLV2haRUw1YUZyblIwdWNKakQ1VEtwR3VnIiwieSI6IkNyRkpmR1RkUDI5SkpjY3BRWHV5TU8zb2h0enJUcVB6QlBCSVRZajBvZ0EifQ", // phpcs:ignore

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
                "credentialSubject" => [
                    "initials" => "R.M.A.",
                    "surname_prefix" => "van",
                    "surname" => "Laar",
                    "uzi_id" => "900000001",
                    "ura" => "87654321",
                    "roles" => "96.000"
                ],
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
     * @return array<string, mixed>
     */
    protected function exampleBodyTwo(): array
    {
        return [
            // Verplicht
            "issuerDid" => "did:jwk:eyJrdHkiOiJFQyIsImNydiI6IlAtMjU2Iiwia2lkIjoiM1lOZDlGbng5Smx5UFZZd2dXRkUzN0UzR3dJMGVHbENLOHdGbFd4R2ZwTSIsIngiOiJGb3ZZMjFMQUFPVGxnLW0tTmVLV2haRUw1YUZyblIwdWNKakQ1VEtwR3VnIiwieSI6IkNyRkpmR1RkUDI5SkpjY3BRWHV5TU8zb2h0enJUcVB6QlBCSVRZajBvZ0EifQ", // phpcs:ignore

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
                    "https://www.w3.org/ns/credentials/v2",
                    "https://purl.imsglobal.org/spec/ob/v3p0/context-3.0.2.json",
                    "https://w3id.org/security/suites/ed25519-2020/v1"
                ],
                "id" => "urn:uuid:2fe53dc9-b2ec-4939-9b2c-0d00f6663b6c",
                "type" => [
                    "VerifiableCredential",
                    "OpenBadgeCredential"
                ],
                "name" => "DCC Test Credential",
                "issuer" => [
                    "type" => [
                        "Profile"
                    ],
                    "id" => "did:key:z6MknNQD1WHLGGraFi6zcbGevuAgkVfdyCdtZnQTGWVVvR5Q",
                    "name" => "Digital Credentials Consortium Test Issuer",
                    "url" => "https://dcconsortium.org",
                    "image" => "https://user-images.githubusercontent.com/752326/230469660-8f80d264-eccf-4edd-8e50-ea634d407778.png" // phpcs:ignore
                ],
                "validFrom" => "2023-08-02T17:43:32.903Z",
                "credentialSubject" => [
                    "type" => [
                        "AchievementSubject"
                    ],
                    "achievement" => [
                        "id" => "urn:uuid:bd6d9316-f7ae-4073-a1e5-2f7f5bd22922",
                        "type" => [
                            "Achievement"
                        ],
                        "achievementType" => "Diploma",
                        "name" => "Badge",
                        "description" => "This is a sample credential issued by the Digital Credentials Consortium to demonstrate the functionality of Verifiable Credentials for wallets and verifiers.", // phpcs:ignore
                        "criteria" => [
                            "type" => "Criteria",
                            "narrative" => "This credential was issued to a student that demonstrated proficiency in the Python programming language that occurred from **February 17, 2023** to **June 12, 2023**." // phpcs:ignore
                        ],
                        "image" => [
                            "id" => "https://user-images.githubusercontent.com/752326/214947713-15826a3a-b5ac-4fba-8d4a-884b60cb7157.png", // phpcs:ignore
                            "type" => "Image"
                        ]
                    ],
                    "name" => "Jane Doe"
                ],
                "credentialStatus" => [
                    [
                        "id" => "https://digitalcredentials.github.io/credential-status-jc-test/XA5AAK1PV4#2",
                        "type" => "BitstringStatusListEntry",
                        "statusPurpose" => "revocation",
                        "statusListIndex" => 2,
                        "statusListCredential" => "https://digitalcredentials.github.io/credential-status-jc-test/XA5AAK1PV4" // phpcs:ignore
                    ],
                    [
                        "id" => "https://digitalcredentials.github.io/credential-status-jc-test/DKSPRCX9WB#5",
                        "type" => "BitstringStatusListEntry",
                        "statusPurpose" => "suspension",
                        "statusListIndex" => 5,
                        "statusListCredential" => "https://digitalcredentials.github.io/credential-status-jc-test/DKSPRCX9WB" // phpcs:ignore
                    ]
                ]
            ],

            // Optionele mapping
//            "mapping" => [
//                "id" => "<uuid>",
//                "issuer" => [
//                    "id" => "<issuerDid>"
//                ],
//                "credentialSubject" => [
//                    "id" => "<subjectDid>"
//                ],
//                "issuanceDate" => "<timestamp>",
//                "validFrom" => "<timestamp>",
//                "expirationDate" => "<timestamp-in:7d>" // Configureerbaar
//            ],
            "authenticationMethod" => "PRE_AUTHORIZED",
        ];
    }

//    protected function exampleBodyOne(): array
//    {
//        return [
//            "issuerDid" => "did:jwk:eyJrdHkiOiJFQyIsImNydiI6IlAtMjU2Iiwia2lkIjoiM1lOZDlGbng5Smx5UFZZd2dXRkUzN0UzR3dJMGVHbENLOHdGbFd4R2ZwTSIsIngiOiJGb3ZZMjFMQUFPVGxnLW0tTmVLV2haRUw1YUZyblIwdWNKakQ1VEtwR3VnIiwieSI6IkNyRkpmR1RkUDI5SkpjY3BRWHV5TU8zb2h0enJUcVB6QlBCSVRZajBvZ0EifQ",
//            "issuerKey" => [
//                "type" => "jwk",
//                "jwk" => [
//                    "kty" => "EC",
//                    "d" => "8jH4vwtvCw6tcBzdxQ6V7FY2L215lBGm-x3flgENx4Y",
//                    "crv" => "P-256",
//                    "kid" => "3YNd9Fnx9JlyPVYwgWFE37E3GwI0eGlCK8wFlWxGfpM",
//                    "x" => "FovY21LAAOTlg-m-NeKWhZEL5aFrnR0ucJjD5TKpGug",
//                    "y" => "CrFJfGTdP29JJccpQXuyMO3ohtzrTqPzBPBITYj0ogA"
//                ]
//            ],
//            "credentialConfigurationId" => "DEZIcredential_jwt_vc_json",
//            "credentialData" => [
//                "@context" => [
//                    "https://www.w3.org/2018/credentials/v1"
//                ],
//                "type" => [
//                    "VerifiableCredential",
//                    "DeziCredential"
//                ],
//                "id" => "88652d6c-e93a-4ac5-afeb-0120ddb0f2b5",
//                "credentialSubject" => [
//                    "initials" => "R.M.A.",
//                    "surname_prefix" => "van",
//                    "surname" => "Laar",
//                    "uzi_id" => "900000001",
//                    "ura" => "87654321",
//                    "roles" => "96.000"
//                ],
//                "issuer" => [
//                    "id" => "did:key:z6MkrHKzgsahxBLyNAbLQyB1pcWNYC9GmywiWPgkrvntAZcj",
//                    "name" => "CIBG Test"
//                ],
//                "issuanceDate" => "2021-08-31T00:00:00Z",
//                "validFrom" => "2021-09-01T00:00:00Z",
//                "expirationDate" => "2031-08-31T00:00:00Z"
//            ],
//            "mapping" => [
//                "id" => "<uuid>",
//                "issuer" => [
//                    "id" => "<issuerDid>"
//                ],
//                "credentialSubject" => [
//                    "id" => "<subjectDid>"
//                ],
//                "issuanceDate" => "<timestamp>",
//                "validFrom" => "<timestamp>",
//                "expirationDate" => "<timestamp-in:365d>"
//            ],
//            "authenticationMethod" => "PRE_AUTHORIZED",
//        ];
//    }
}
