<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamPortalFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_number_is_generated_for_new_user(): void
    {
        $user = User::create([
            'name' => 'Student One',
            'mobile' => '9876543210',
        ]);

        $this->assertNotNull($user->fresh()->application_number);
        $this->assertStringStartsWith('APP', $user->fresh()->application_number);
    }

    public function test_public_status_check_uses_application_number(): void
    {
        $user = User::create([
            'name' => 'Student Two',
            'mobile' => '9876543211',
        ]);

        $exam = Exam::create([
            'title' => 'Test Exam 2026',
            'description' => 'Exam description',
            'last_date' => now()->addWeek(),
            'status' => 'active',
        ]);

        Application::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'full_name' => 'Student Two',
            'dob' => '2000-01-01',
            'gender' => 'Male',
            'mobile' => '9876543211',
            'address' => 'Surat',
            'status' => 'approved',
        ]);

        Payment::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'amount' => 500,
            'status' => 'paid',
            'transaction_id' => 'TEST-REF',
        ]);

        $response = $this->post('/check-status', [
            'application_number' => $user->application_number,
        ]);

        $response->assertOk();
        $response->assertSee($user->application_number);
        $response->assertSee('approved');
        $response->assertSee('paid');
    }
}
