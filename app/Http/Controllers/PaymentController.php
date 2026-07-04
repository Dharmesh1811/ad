<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Payment;
use App\Services\RazorpayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PaymentController
 *
 * Handles the full Razorpay payment lifecycle:
 *  1. create()  — Build Razorpay Order server-side, render checkout page
 *  2. store()   — AJAX: verify Razorpay signature, save payment, return JSON
 *  3. success() — Display payment success confirmation page
 *  4. failed()  — Display payment failure page
 *
 * Security enforced at every step:
 *  - Amount ALWAYS fetched from database (never trusted from frontend)
 *  - Ownership verified via user_id check
 *  - CSRF token validated on all POST requests
 *  - Razorpay signature verified server-side before marking payment as paid
 */
class PaymentController extends Controller
{
    /**
     * Constructor — inject the RazorpayService.
     */
    public function __construct(
        private readonly RazorpayService $razorpay
    ) {}

    // =========================================================================
    // STEP 1 — Payment Page
    // =========================================================================

    /**
     * Show the payment checkout page.
     *
     * - Verifies the application belongs to the authenticated user
     * - Fetches the exam fee from the database (NEVER from request)
     * - Creates a Razorpay Order server-side
     * - Passes only the Razorpay Key ID (public) to the view — never the secret
     *
     * @param  Request      $request
     * @param  Application  $application  Route-model-bound application
     * @return View
     */
    public function create(Request $request, Application $application): View
    {
        // Authorization: ensure this application belongs to the logged-in user
        abort_unless(
            $application->user_id === $request->user()->id,
            403,
            'You are not authorized to access this payment.'
        );

        // Load exam relationship to get the fee
        $application->loadMissing('exam');
        $exam = $application->exam;

        abort_if(is_null($exam), 404, 'Exam not found for this application.');

        // SECURITY: Amount comes from the DB, not from user input
        $amountInRupees = (float) ($exam->fee ?? 500);
        $amountInPaise  = $this->razorpay->rupeesToPaise($amountInRupees);

        // Create or retrieve an existing pending payment record for this application
        $payment = $request->user()->payments()->firstOrCreate(
            ['exam_id' => $application->exam_id],
            [
                'amount'         => $amountInRupees,
                'status'         => 'pending',
                'payment_status' => 'pending',
            ]
        );

        // Create a fresh Razorpay order for this session
        // (We always create a new order to get a valid order_id for Razorpay checkout)
        try {
            $receiptId    = 'rcpt_' . $application->id . '_' . time();
            $razorpayOrder = $this->razorpay->createOrder($amountInPaise, $receiptId);

            // Persist the Razorpay Order ID so we can verify it later
            $payment->update([
                'razorpay_order_id' => $razorpayOrder['id'],
                'payment_status'    => 'pending',
            ]);

        } catch (\Exception $e) {
            Log::error('PaymentController@create: Failed to create Razorpay order', [
                'application_id' => $application->id,
                'error'          => $e->getMessage(),
            ]);

            return view('payments.create', [
                'application'   => $application,
                'payment'       => $payment,
                'razorpayOrder' => null,
                'razorpayKeyId' => config('razorpay.key_id'),
                'amountInPaise' => $amountInPaise,
                'orderError'    => 'Unable to initialize payment. Please try again.',
            ]);
        }

        Log::info('PaymentController@create: Payment page loaded', [
            'user_id'           => $request->user()->id,
            'application_id'    => $application->id,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount_rupees'     => $amountInRupees,
        ]);

        return view('payments.create', [
            'application'    => $application,
            'payment'        => $payment,
            'razorpayOrder'  => $razorpayOrder,
            'razorpayKeyId'  => config('razorpay.key_id'),  // Public key only
            'amountInPaise'  => $amountInPaise,
            'orderError'     => null,
        ]);
    }

    // =========================================================================
    // STEP 2 — Verify Payment (AJAX Endpoint)
    // =========================================================================

    /**
     * Verify Razorpay payment signature and save payment details.
     *
     * This endpoint is called via AJAX (Fetch API) after the Razorpay
     * checkout succeeds on the frontend. It performs server-side verification.
     *
     * Flow:
     *  1. Validate incoming fields
     *  2. Verify user ownership of the payment
     *  3. VERIFY Razorpay signature (HMAC-SHA256) — reject if invalid
     *  4. Fetch payment method from Razorpay API
     *  5. Save all payment details in a DB transaction
     *  6. Mark application as submitted
     *  7. Return JSON response
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // ------------------------------------------------------------------
        // 1. Validate Request Fields
        // ------------------------------------------------------------------
        $validated = $request->validate([
            'razorpay_payment_id' => ['required', 'string', 'max:255'],
            'razorpay_order_id'   => ['required', 'string', 'max:255'],
            'razorpay_signature'  => ['required', 'string', 'max:512'],
            'application_id'      => ['required', 'integer', 'exists:applications,id'],
        ]);

        // ------------------------------------------------------------------
        // 2. Authorization — find payment & verify ownership
        // ------------------------------------------------------------------
        $application = Application::with('exam')->findOrFail($validated['application_id']);
        abort_unless(
            $application->user_id === $request->user()->id,
            403,
            'Unauthorized payment verification attempt.'
        );

        $payment = Payment::where('razorpay_order_id', $validated['razorpay_order_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $payment) {
            Log::warning('PaymentController@store: Payment record not found for order', [
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'user_id'           => $request->user()->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment record not found. Please contact support.',
            ], 404);
        }

        // ------------------------------------------------------------------
        // 3. SECURITY: Verify Razorpay Signature
        // ------------------------------------------------------------------
        $signatureValid = $this->razorpay->verifySignature(
            $validated['razorpay_order_id'],
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature']
        );

        if (! $signatureValid) {
            // Signature mismatch — mark as failed and reject
            $payment->update([
                'payment_status' => 'failed',
                'status'         => 'failed',
                'transaction_time' => now(),
            ]);

            Log::alert('PaymentController@store: INVALID signature — possible fraud attempt', [
                'user_id'             => $request->user()->id,
                'application_id'      => $application->id,
                'razorpay_order_id'   => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
            ]);

            return response()->json([
                'success'      => false,
                'message'      => 'Payment verification failed. Signature mismatch.',
                'redirect_url' => route('payments.failed', $application),
            ], 422);
        }

        // ------------------------------------------------------------------
        // 4. Fetch Payment Method from Razorpay
        // ------------------------------------------------------------------
        $paymentMethod = 'unknown';
        try {
            $razorpayDetails = $this->razorpay->fetchPayment($validated['razorpay_payment_id']);
            $paymentMethod   = $razorpayDetails['method'] ?? 'unknown';
        } catch (\Exception $e) {
            // Non-fatal — we log it but continue saving the payment
            Log::warning('PaymentController@store: Could not fetch payment method', [
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'error'               => $e->getMessage(),
            ]);
        }

        // ------------------------------------------------------------------
        // 5. Save Payment in DB Transaction
        // ------------------------------------------------------------------
        try {
            DB::transaction(function () use ($payment, $application, $validated, $paymentMethod): void {
                // Update payment record with full Razorpay details
                $payment->update([
                    'razorpay_payment_id' => $validated['razorpay_payment_id'],
                    'razorpay_signature'  => $validated['razorpay_signature'],
                    'payment_method'      => $paymentMethod,
                    'payment_status'      => 'paid',
                    'status'              => 'paid',
                    'paid_at'             => now(),
                    'transaction_time'    => now(),
                    'transaction_id'      => $validated['razorpay_payment_id'], // Legacy field
                ]);

                // Mark the application as approved (payment verified automatically approves)
                $application->update([
                    'status'       => 'approved',
                    'submitted_at' => now(),
                ]);
            });

        } catch (\Exception $e) {
            Log::error('PaymentController@store: DB transaction failed after signature verification', [
                'error'               => $e->getMessage(),
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment was verified but could not be saved. Please contact support with Payment ID: ' . $validated['razorpay_payment_id'],
            ], 500);
        }

        Log::info('PaymentController@store: Payment completed successfully', [
            'user_id'             => $request->user()->id,
            'application_id'      => $application->id,
            'razorpay_order_id'   => $validated['razorpay_order_id'],
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'payment_method'      => $paymentMethod,
            'amount'              => $payment->amount,
        ]);

        // ------------------------------------------------------------------
        // 6. Return Success JSON
        // ------------------------------------------------------------------
        return response()->json([
            'success'      => true,
            'message'      => 'Payment verified and saved successfully.',
            'redirect_url' => route('payments.success', $application),
            'payment'      => [
                'id'             => $payment->id,
                'payment_id'     => $validated['razorpay_payment_id'],
                'amount'         => $payment->formattedAmount(),
                'payment_method' => $paymentMethod,
            ],
        ]);
    }

    // =========================================================================
    // STEP 3 — Success / Failure Pages
    // =========================================================================

    /**
     * Display the payment success page.
     *
     * @param  Request      $request
     * @param  Application  $application
     * @return View|RedirectResponse
     */
    public function success(Request $request, Application $application): View|RedirectResponse
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $payment = Payment::where('user_id', $request->user()->id)
            ->where('exam_id', $application->exam_id)
            ->where('payment_status', 'paid')
            ->latest()
            ->first();

        if (! $payment) {
            return redirect()->route('payments.create', $application)
                ->with('error', 'No successful payment found for this application.');
        }

        return view('payments.success', compact('application', 'payment'));
    }

    /**
     * Display the payment failure page.
     *
     * @param  Request      $request
     * @param  Application  $application
     * @return View
     */
    public function failed(Request $request, Application $application): View
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $payment = Payment::where('user_id', $request->user()->id)
            ->where('exam_id', $application->exam_id)
            ->latest()
            ->first();

        return view('payments.failed', compact('application', 'payment'));
    }
}
