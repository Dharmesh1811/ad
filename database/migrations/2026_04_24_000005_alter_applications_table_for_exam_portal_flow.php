<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table): void {
            $table->foreignId('exam_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->string('full_name')->nullable()->after('exam_id');
            $table->date('dob')->nullable()->after('full_name');
            $table->string('gender')->nullable()->after('dob');
            $table->string('mobile')->nullable()->after('gender');
            $table->string('email')->nullable()->after('mobile');
            $table->text('address')->nullable()->after('email');
            $table->string('photo')->nullable()->after('address');
            $table->string('signature')->nullable()->after('photo');
            $table->string('status')->default('not_filled')->after('signature');
            $table->unique(['user_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table): void {
            $table->dropUnique(['user_id', 'exam_id']);
            $table->dropConstrainedForeignId('exam_id');
            $table->dropColumn([
                'full_name',
                'dob',
                'gender',
                'mobile',
                'email',
                'address',
                'photo',
                'signature',
                'status',
            ]);
        });
    }
};
