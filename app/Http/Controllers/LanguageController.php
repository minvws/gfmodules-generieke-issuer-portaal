<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;

class LanguageController extends Controller
{
    public function __invoke(string $locale): RedirectResponse
    {
        if (in_array($locale, Config::get('app.locales'), true)) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }
}
