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

    public function show(Request $request): View
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'application_number' => ['required', 'string'],
            'captcha' => 'required|captcha',
        ], [
            'captcha.captcha' => 'Invalid captcha code. Please try again.',
        ]);

        $candidate = User::where('mobile', $request->mobile)
            ->where('application_number', strtoupper($request->application_number))
            ->with(['applications.exam', 'payments.exam'])
            ->first();

        if (!$candidate) {
            return view('admit-card', ['candidate' => null, 'eligible' => false])->withErrors(['error' => 'Invalid details. No candidate found with these credentials.']);
        }

        $application = $candidate->applications()->with('exam')->where('status', 'approved')->first();

        if (!$application) {
            return view('admit-card', ['candidate' => null, 'eligible' => false])->withErrors(['error' => 'Admit card is not available yet. Your application must be approved first.']);
        }

        return view('admit-card', [
            'candidate' => $candidate,
            'application' => $application,
            'eligible' => true,
        ]);
    }

    public function download(\App\Models\Application $application)
    {
        $candidate = $application->user;
        
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('idcard.template', [
                'user' => $candidate,
                'application' => $application,
                'exam' => $application->exam,
            ]);

            return $pdf->download($candidate->application_number . '-id-card.pdf');
        }

        return back()->withErrors(['error' => 'PDF generation failed. Contact support.']);
    }
}
