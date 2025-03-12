<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Uzi\OidcAuthGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(protected OidcAuthGuard $guard)
    {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->guard->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('index');
    }
}
