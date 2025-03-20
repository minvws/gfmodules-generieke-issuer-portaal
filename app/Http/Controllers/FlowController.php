<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\CredentialData;
use App\Http\Requests\FlowCredentialDataRequest;
use App\Models\UziUser;
use App\Services\FlowStateService;
use App\Services\VCIssuerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FlowController extends Controller
{
    public function __construct(
        protected FlowStateService $stateService,
        protected VCIssuerService $issuerService,
    )
    {
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
            ->with('issuanceUrl', $issuanceUrl);
    }

    public function editCredentialData(): View
    {
        return $this->returnFlowView(editCredentialData: true);
    }

    public function storeCredentialData(FlowCredentialDataRequest $request): RedirectResponse
    {
        $data = new CredentialData(
            subject: $request->validated('subject'),
        );
        $this->stateService->setCredentialDataInSession($data);

        return redirect()->route('flow');
    }

    protected function returnFlowView(bool $editCredentialData = false, bool $editAuthorization = false): View
    {
        $state = $this->stateService->getFlowStateFromSession();

        return view('flow.index')
            ->with('state', $state)
            ->with('editCredential', $editCredentialData)
            ->with('editAuthorization', $editAuthorization)
            ->with('defaultCredentialSubject', $this->getDefaultCredentialSubject($state->getUser()));
    }

    protected function getDefaultCredentialSubject(?UziUser $uziUser = null): string
    {
        $firstUra = $uziUser?->uras[0] ?? null;

        $data = [
            "initials" => $uziUser->initials ?? '',
            "surname_prefix" => $uziUser->surnamePrefix ?? null,
            "surname" => $uziUser->surname ?? '',
            "uzi_id" => $uziUser->uziId ?? '',
            "ura" => $firstUra->ura ?? '',
            "roles" => implode(',', $firstUra?->roles ?? [])
        ];

        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
