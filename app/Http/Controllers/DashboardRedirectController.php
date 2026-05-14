<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isPanitia()) {
            return redirect()->route('panitia.dashboard');
        }

        if ($user->isKepsek()) {
            return redirect()->route('kepsek.dashboard');
        }

        return redirect()->route('panel.ortu');
    }
}
