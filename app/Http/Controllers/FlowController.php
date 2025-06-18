<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\EnrichService;
use App\Services\FlowStateService;
use App\Services\VCIssuerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FlowController extends Controller
{
    public function __construct(
        protected FlowStateService $stateService,
        protected VCIssuerService $issuerService,
        protected EnrichService $enrichService,
    ) {
    }

    public function index(): RedirectResponse|View
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

    /**
     * @return View
     */
    protected function returnFlowView(): View {
        $state = $this->stateService->getFlowStateFromSession();

        return view('flow.index')
            ->with('state', $state);
    }
}
