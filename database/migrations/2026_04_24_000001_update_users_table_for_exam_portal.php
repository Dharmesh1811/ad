<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('mobile')->nullable()->unique()->after('name');
            $table->string('otp')->nullable()->after('mobile');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->string('application_number')->nullable()->unique()->after('password');
            $table->date('date_of_birth')->nullable()->after('application_number');
            $table->boolean('is_admin')->default(false)->after('date_of_birth');
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'mobile',
                'otp',
                'otp_expires_at',
                'application_number',
                'date_of_birth',
                'is_admin',
            ]);
        });
    }
};
