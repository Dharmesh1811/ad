<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Payment Model
 *
 * Represents a payment transaction linked to a user and an exam.
 * Stores both legacy transaction data and full Razorpay payment details.
 *
 * @property int         $id
 * @property int         $user_id
 * @property int|null    $exam_id
 * @property float       $amount
 * @property string      $status              Legacy status field
 * @property string|null $transaction_id      Legacy transaction reference
 * @property string|null $razorpay_order_id   Razorpay Order ID (server-generated)
 * @property string|null $razorpay_payment_id Razorpay Payment ID (post-payment)
 * @property string|null $razorpay_signature  HMAC-SHA256 signature (verified server-side)
 * @property string|null $payment_method      upi | card | netbanking | wallet | emi
 * @property string      $payment_status      pending | paid | failed
 * @property \Carbon\Carbon|null $transaction_time
 * @property \Carbon\Carbon|null $paid_at
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        // Legacy fields
        'user_id',
        'exam_id',
        'amount',
        'status',
        'transaction_id',
        'paid_at',

        // Razorpay fields
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'payment_method',
        'payment_status',
        'transaction_time',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'amount'           => 'decimal:2',
        'paid_at'          => 'datetime',
        'transaction_time' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * The user who made this payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The exam this payment is for.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    // -------------------------------------------------------------------------
    // Helper Methods
    // -------------------------------------------------------------------------

    /**
     * Check if this payment was successfully completed via Razorpay.
     */
    public function isSuccessful(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if this payment has failed.
     */
    public function hasFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check if this payment is still pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Get the amount formatted as Indian Rupees string.
     */
    public function formattedAmount(): string
    {
        return '₹' . number_format((float) $this->amount, 2);
    }

    /**
     * Get the status badge CSS class for display.
     */
    public function statusBadgeClass(): string
    {
        return match ($this->payment_status) {
            'paid'    => 'bg-success',
            'failed'  => 'bg-danger',
            default   => 'bg-warning text-dark',
        };
    }
}
