<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\CredentialData;
use App\Http\Requests\FlowCredentialDataRequest;
use App\Services\FlowStateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FlowController extends Controller
{
    public function __construct(protected FlowStateService $stateService)
    {
    }

    public function index(): View
    {
        return $this->returnFlowView();
    }

    public function retrieveCredential(): RedirectResponse
    {
        $flowState = $this->stateService->getFlowStateFromSession();
        if (!$flowState->isFlowComplete()) {
            return redirect()->route('flow');
        }

        return redirect()->route('timeline.fetch');
    }

    public function editCredentialData(): View
    {
        return $this->returnFlowView(editCredentialData: true);
    }

    public function storeCredentialData(FlowCredentialDataRequest $request): RedirectResponse
    {
        $data = new CredentialData(
            bsn: $request->validated('bsn'),
            consent: $request->validated('consent') ? true : false,
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
            ->with('editAuthorization', $editAuthorization);
    }
}
