<?php

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
    public function handle()
    {
        //

        // Choose credential...

        // http://localhost:8497/openid4vc/jwt/issue
        // http://localhost:8497/openid4vc/sdjwt/issue
        // er is ook nog een batch endpoint



//        $response = Http::post('http://issuer-api:8497/', [
        $response = Http::post('http://host.docker.internal:8497/openid4vc/jwt/issue', $this->exampleBodyOne());

        if ($response->failed()) {
            $this->error('Failed to create credential');
            $this->error($response->body());
            return;
        }

        $this->info('Credential created, here is the offer uri:');
        $this->info($response->body());
    }

    protected function exampleBodyOne(): array
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
                "credentialSubject" => [
                    "initials" => "R.M.A.",
                    "surname_prefix" => "van",
                    "surname" => "Laar",
                    "uzi_id" => "900000001",
                    "ura" => "87654321",
                    "roles" => "96.000"
                ],
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
