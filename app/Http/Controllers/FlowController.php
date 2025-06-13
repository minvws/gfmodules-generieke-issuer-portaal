<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\CredentialData;
use App\Http\Requests\FlowCredentialDataRequest;
use App\Services\EnrichService;
use App\Services\FlowStateService;
use App\Services\VCIssuerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use JsonException;

class FlowController extends Controller
{
    public function __construct(
        protected FlowStateService $stateService,
        protected VCIssuerService $issuerService,
        protected EnrichService $enrichService,
    ) {
    }

    public function index(): View
    {
        $credentialSubject = $this->getDefaultCredentialSubject();
        $credentialSubject = $this->enrichService->enrich($credentialSubject);

        return $this->returnFlowView($credentialSubject);
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

    public function editCredentialData(): View
    {
        $flow = $this->stateService->getFlowStateFromSession();
        $credentialSubject = $flow->getCredentialData()?->getSubjectAsArray() ?? $this->getDefaultCredentialSubject();

        return $this->returnFlowView($credentialSubject, editCredentialData: true);
    }

    public function storeCredentialData(FlowCredentialDataRequest $request): RedirectResponse
    {
        $data = new CredentialData(
            subject: $request->validated('subject'),
        );
        $this->stateService->setCredentialDataInSession($data);

        return redirect()->route('flow');
    }

    /**
     * @param mixed[] $credentialSubject
     * @param bool $editCredentialData
     * @param bool $editAuthorization
     * @return View
     */
    protected function returnFlowView(
        array $credentialSubject,
        bool $editCredentialData = false,
        bool $editAuthorization = false
    ): View {
        $state = $this->stateService->getFlowStateFromSession();
        try {
            $cs = json_encode($credentialSubject, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (JsonException) {
            $cs = '';
        }

        return view('flow.index')
            ->with('state', $state)
            ->with('editCredential', $editCredentialData)
            ->with('editAuthorization', $editAuthorization)
            ->with('defaultCredentialSubject', $cs);
    }

    /**
     * @return string[]
     */
    protected function getDefaultCredentialSubject(): array
    {
        return [
            "organization_code" => '12341234'
        ];
    }
}
