<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\CredentialData;
use App\Services\EnrichService;
use App\Services\FlowStateService;
use App\Services\RevocationService;
use App\Services\VCIssuerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;

class FlowController extends Controller
{
    public function __construct(
        protected FlowStateService $stateService,
        protected VCIssuerService $issuerService,
        protected EnrichService $enrichService,
        protected RevocationService $revocationService,
    ) {
    }

    public function index(): View
    {
        return $this->returnFlowView();
    }

    public function retrieveCredential(): RedirectResponse|View
    {
        $flowState = $this->stateService->getFlowStateFromSession();
        if (!$flowState->isFlowComplete()) {
            return redirect()->route('flow');
        }

        $subject = $flowState->getCredentialData()?->getSubjectAsArray();
        if ($subject === null) {
            return redirect()->route('flow');
        }

        $issuanceUrl = $this->issuerService->issueCredential($subject);

        return view('flow.credential')
            ->with('issuanceUrl', $issuanceUrl->getUrl())
            ->with('credentialOfferUri', $issuanceUrl->getCredentialOfferUri());
    }

    public function enrichCredentialData(): RedirectResponse
    {
        $state = $this->stateService->getFlowStateFromSession();
        if ($state->getUser() === null) {
            return redirect()->route('index')
                ->with('error', __('You must be logged in to retrieve a credential.'));
        }
        if ($state->hasCredentialData()) {
            return redirect()
                ->route('flow')
                ->with('error', __('Credential already enriched'));
        }

        try {
            $credentialSubject = $state->getUser()->getAsArray();
            $credentialSubject = $this->enrichService->enrich($credentialSubject);
            $cs = json_encode($credentialSubject, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            return redirect()
                ->route('flow')
                ->with('error', __('Could not enrich credential'))
                ->with('error_description', __($e->getMessage()));
        }

        $data = new CredentialData(
            subject: $cs,
        );

        $this->stateService->setCredentialDataInSession($data);

        return redirect()->route('flow');
    }

    /**
     * @return View
     */
    protected function returnFlowView(): View
    {
        $state = $this->stateService->getFlowStateFromSession();
        return view('flow.index')
            ->with('state', $state);
    }
}
