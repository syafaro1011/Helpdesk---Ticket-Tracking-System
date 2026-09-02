<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            $redirectRoute = in_array($request->user()->role, ['admin', 'technician'])
                ? route('tech.tickets.index', absolute: false)
                : route('user.tickets.index', absolute: false);

            return redirect()->intended($redirectRoute);
        }

        return view('auth.verify-email');
    }
}
