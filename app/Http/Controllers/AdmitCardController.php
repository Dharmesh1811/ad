<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdmitCardController extends Controller
{
    public function index(): View
    {
        return view('admit-card', ['candidate' => null, 'eligible' => false]);
    }

    public function show(Request $request): Response|View
    {
        $validated = $request->validate([
            'application_number' => ['required', 'string'],
        ]);

        $candidate = User::where('application_number', strtoupper($validated['application_number']))
            ->with(['applications.exam', 'payments.exam'])
            ->first();

        $application = $candidate?->applications->firstWhere('status', 'approved')
            ?? $candidate?->applications->firstWhere('status', 'submitted');
        $payment = $candidate?->payments->firstWhere('exam_id', $application?->exam_id);

        $eligible = $candidate
            && $application
            && $payment?->status === 'paid';

        if (! $candidate || ! $eligible) {
            return response()->view('admit-card', [
                'candidate' => $candidate,
                'application' => $application,
                'eligible' => false,
            ]);
        }

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.id-card', [
                'candidate' => $candidate,
            ]);

            return $pdf->download($candidate->application_number . '-id-card.pdf');
        }

        return view('admit-card', [
            'candidate' => $candidate,
            'application' => $application,
            'eligible' => true,
        ]);
    }
}
