<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\VCVerifierService;
use Illuminate\Console\Command;

class VerifyCredential extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-credential';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(VCVerifierService $verifierService): void
    {
        try {
            $presentationSession = $verifierService->initializePresentationSession(
                credentialType: "MijnGeneriekeCredential",
            );
        } catch (\Exception $exception) {
            $this->error('Error: ' . $exception->getMessage());
            return;
        }

        $this->info('Presentation session started!' . PHP_EOL);
        $this->info('Use the following URL in your wallet to proceed the presentation:');
        $this->info($presentationSession->getUrl() . PHP_EOL);

        // Wait for the user to finish the presentation
        $this->info('Waiting for the user to finish the presentation...');
        $continue = $this->confirm('Press Enter to continue...', true);

        if (!$continue) {
            $this->info('Exiting...');
            return;
        }

        // Check the status of the presentation session
        $session = $verifierService->getPresentationSession($presentationSession->getSessionId());
        dd($session);
    }
}
