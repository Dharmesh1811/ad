<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PaymentController extends Controller
{
    public function create(Request $request, Application $application): View
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $payment = $request->user()->payments()->firstOrCreate(
            ['exam_id' => $application->exam_id],
            ['amount' => $application->exam?->fee ?? 500, 'status' => 'pending']
        );

        return view('payments.create', compact('payment', 'application'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_id' => ['required', 'string', 'max:255'],
        ]);

        $application = Application::with('exam')->findOrFail($validated['application_id']);
        abort_unless($application->user_id === $request->user()->id, 403);

        $payment = $request->user()->payments()->firstOrNew([
            'exam_id' => $application->exam_id,
        ]);
        $payment->fill([
            'amount' => $validated['amount'],
            'exam_id' => $application->exam_id,
            'transaction_id' => $validated['transaction_id'],
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        $payment->user()->associate($request->user());
        $payment->save();

        $application->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('status', 'Payment successful and application submitted.');
    }
}
