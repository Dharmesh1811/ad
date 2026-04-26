<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table): void {
            if (Schema::hasColumn('exams', 'form_fields')) {
                $table->dropColumn('form_fields');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table): void {
            $table->json('form_fields')->nullable()->after('status');
        });
    }
};
