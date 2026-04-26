<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(): View
    {
        return view('track-status', ['result' => null]);
    }

    public function show(Request $request): View
    {
        $validated = $request->validate([
            'application_number' => ['required', 'string'],
        ]);

        $user = User::where('application_number', strtoupper($validated['application_number']))
            ->with(['applications.exam', 'payments.exam'])
            ->first();

        return view('track-status', [
            'result' => $user,
        ]);
    }
}
