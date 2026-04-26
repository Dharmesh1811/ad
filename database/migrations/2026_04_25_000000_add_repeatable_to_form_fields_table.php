<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->boolean('is_repeatable')->default(false)->after('is_required');
            $table->integer('max_repeat')->nullable()->after('is_repeatable');
        });
    }

    public function down(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn(['is_repeatable', 'max_repeat']);
        });
    }
};
