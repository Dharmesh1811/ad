<?php

namespace App\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\Log;

/**
 * RazorpayService
 *
 * Encapsulates all Razorpay SDK interactions.
 * This keeps the controller clean and makes the integration testable and reusable.
 *
 * Usage:
 *   $service = app(RazorpayService::class);
 *   $order   = $service->createOrder(50000, 'receipt_123');  // ₹500 = 50000 paise
 *   $valid   = $service->verifySignature($orderId, $paymentId, $signature);
 */
class RazorpayService
{
    /**
     * Razorpay API client instance.
     */
    private Api $api;

    /**
     * The configured currency (default: INR).
     */
    private string $currency;

    /**
     * Boot the service — reads credentials from config/razorpay.php.
     */
    public function __construct()
    {
        $this->api = new Api(
            config('razorpay.key_id'),
            config('razorpay.key_secret')
        );

        $this->currency = config('razorpay.currency', 'INR');
    }

    // -------------------------------------------------------------------------
    // Order Management
    // -------------------------------------------------------------------------

    /**
     * Create a new Razorpay Order.
     *
     * IMPORTANT: Amount must be in PAISE (smallest currency unit).
     * ₹100 = 10000 paise, ₹250 = 25000 paise.
     *
     * @param  int    $amountInPaise  Amount in paise (multiply rupees × 100)
     * @param  string $receiptId      Unique receipt ID (your internal reference)
     * @return array                  Razorpay order array with 'id', 'amount', 'currency', etc.
     *
     * @throws \Exception  If Razorpay API call fails
     */
    public function createOrder(int $amountInPaise, string $receiptId): array
    {
        try {
            Log::info('RazorpayService: Creating order', [
                'amount_paise' => $amountInPaise,
                'receipt'      => $receiptId,
                'currency'     => $this->currency,
            ]);

            $order = $this->api->order->create([
                'amount'          => $amountInPaise,
                'currency'        => $this->currency,
                'receipt'         => $receiptId,
                'payment_capture' => 1, // Auto-capture payment after authorization
                'notes'           => [
                    'source'      => config('app.name'),
                    'receipt'     => $receiptId,
                ],
            ]);

            $orderArray = $order->toArray();

            Log::info('RazorpayService: Order created', [
                'razorpay_order_id' => $orderArray['id'],
                'amount_paise'      => $orderArray['amount'],
            ]);

            return $orderArray;

        } catch (\Exception $e) {
            Log::error('RazorpayService: Failed to create order', [
                'error'        => $e->getMessage(),
                'receipt'      => $receiptId,
                'amount_paise' => $amountInPaise,
            ]);

            throw $e;
        }
    }

    // -------------------------------------------------------------------------
    // Signature Verification
    // -------------------------------------------------------------------------

    /**
     * Verify the Razorpay payment signature (HMAC-SHA256).
     *
     * This is the CRITICAL security step. Razorpay signs the response using
     * your Key Secret. Verifying this signature confirms:
     *  - The payment was genuinely processed by Razorpay
     *  - The response was not tampered with by the user
     *
     * @param  string $orderId   razorpay_order_id from the checkout response
     * @param  string $paymentId razorpay_payment_id from the checkout response
     * @param  string $signature razorpay_signature from the checkout response
     * @return bool              true if signature is valid, false otherwise
     */
    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature'  => $signature,
            ]);

            Log::info('RazorpayService: Signature verified successfully', [
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
            ]);

            return true;

        } catch (SignatureVerificationError $e) {
            Log::warning('RazorpayService: Signature verification FAILED — possible tampering', [
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
                'error'               => $e->getMessage(),
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('RazorpayService: Unexpected error during signature verification', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Convert rupees (float) to paise (int) for Razorpay API.
     *
     * @param  float $rupees  Amount in Indian Rupees
     * @return int            Amount in paise
     */
    public function rupeesToPaise(float $rupees): int
    {
        return (int) round($rupees * 100);
    }

    /**
     * Convert paise (int) back to rupees (float).
     *
     * @param  int   $paise  Amount in paise
     * @return float         Amount in Indian Rupees
     */
    public function paiseToRupees(int $paise): float
    {
        return round($paise / 100, 2);
    }

    /**
     * Fetch details of a specific Razorpay payment by Payment ID.
     * Useful for retrieving payment_method after checkout.
     *
     * @param  string $paymentId  razorpay_payment_id
     * @return array              Payment details from Razorpay
     *
     * @throws \Exception
     */
    public function fetchPayment(string $paymentId): array
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            return $payment->toArray();
        } catch (\Exception $e) {
            Log::error('RazorpayService: Failed to fetch payment details', [
                'razorpay_payment_id' => $paymentId,
                'error'               => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
