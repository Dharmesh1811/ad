<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('full_name')->nullable()->after('name');
            $table->date('dob')->nullable()->after('date_of_birth');
        });

        DB::table('users')->update([
            'full_name' => DB::raw('COALESCE(full_name, name)'),
            'dob' => DB::raw('COALESCE(dob, date_of_birth)'),
        ]);

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'otp',
                'otp_expires_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('otp')->nullable()->after('mobile');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->dropColumn([
                'full_name',
                'dob',
            ]);
        });
    }
};
