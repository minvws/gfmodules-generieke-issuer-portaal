<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\VCIssuerService;
use Illuminate\Console\Command;

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
    public function handle(VCIssuerService $issuerService): void
    {
        //

        // Choose credential...

        // http://localhost:8497/openid4vc/jwt/issue
        // http://localhost:8497/openid4vc/sdjwt/issue
        // er is ook nog een batch endpoint

        try {
            $issuanceUrl = $issuerService->issueCredential($this->exampleSubject());
        } catch (\Exception $exception) {
            $this->error('Error: ' . $exception->getMessage());
            return;
        }

        $this->info('Credential created, here is the offer uri:');
        $this->info($issuanceUrl->getUrl());
    }

    /**
     * @return array<string, mixed>
     */
    protected function exampleSubject(): array
    {
        return [
            "initials" => "R.M.A.",
            "surname_prefix" => "van",
            "surname" => "Laar",
            "uzi_id" => "900000001",
            "ura" => "87654321",
            "roles" => "96.000"
        ];
    }
}
