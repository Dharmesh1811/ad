<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add Razorpay payment columns to the payments table.
 *
 * This migration is ADDITIVE — it adds Razorpay-specific fields to the
 * existing payments table without dropping or altering existing columns.
 * Safe to run on databases that already have payment records.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            // Razorpay Order ID — created server-side before checkout opens
            $table->string('razorpay_order_id')->nullable()->unique()->after('transaction_id');

            // Razorpay Payment ID — returned by Razorpay after successful payment
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');

            // Razorpay Signature — HMAC-SHA256 hash used for server-side verification
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');

            // Payment method used: upi, card, netbanking, wallet, emi, etc.
            $table->string('payment_method')->nullable()->after('razorpay_signature');

            // Dedicated Razorpay payment status: pending | paid | failed
            // (separate from the existing 'status' column for backwards compatibility)
            $table->string('payment_status')->default('pending')->after('payment_method');

            // Exact timestamp when the transaction was completed
            $table->timestamp('transaction_time')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            $table->dropUnique(['razorpay_order_id']);
            $table->dropColumn([
                'razorpay_order_id',
                'razorpay_payment_id',
                'razorpay_signature',
                'payment_method',
                'payment_status',
                'transaction_time',
            ]);
        });
    }
};
