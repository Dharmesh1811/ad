<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IdCardController extends Controller
{
    /**
     * Show the ID Card download form.
     */
    public function form()
    {
        return view('idcard.form');
    }

    /**
     * Handle the ID Card download request.
     */
    public function download(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'application_number' => 'required|string',
            'captcha' => 'required|captcha',
        ], [
            'captcha.captcha' => 'Invalid captcha code. Please try again.',
        ]);

        $user = User::where('mobile', $request->mobile)
                    ->where('application_number', strtoupper($request->application_number))
                    ->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Invalid details. No candidate found with these credentials.'])->withInput();
        }

        // Check for an approved application
        $application = $user->applications()
            ->with('exam')
            ->where('status', 'approved')
            ->first();

        if (!$application) {
            return back()->withErrors(['error' => 'ID Card is not available yet. Your application must be approved first.'])->withInput();
        }

        // Generate and download the PDF
        $pdf = Pdf::loadView('idcard.template', [
            'user' => $user,
            'application' => $application,
            'exam' => $application->exam,
        ]);

        // Set paper size and orientation if needed
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('ID_Card_' . $user->application_number . '.pdf');
    }
}
