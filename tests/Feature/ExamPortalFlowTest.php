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
            'full_name' => 'Student One',
            'mobile' => '9876543210',
            'email' => 'student1@example.com',
            'password' => 'secret123',
        ]);

        $this->assertNotNull($user->fresh()->application_number);
        $this->assertStringStartsWith('APP', $user->fresh()->application_number);
    }

    public function test_public_status_check_uses_application_number(): void
    {
        $user = User::create([
            'full_name' => 'Student Two',
            'mobile' => '9876543211',
            'email' => 'student2@example.com',
            'password' => 'secret123',
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

    public function test_user_can_register_with_password_based_fields(): void
    {
        $response = $this->post('/auth/register-password', [
            'full_name' => 'Student Three',
            'mobile' => '9876543212',
            'email' => 'student3@example.com',
            'dob' => '2001-04-20',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'full_name' => 'Student Three',
            'mobile' => '9876543212',
            'email' => 'student3@example.com',
            'dob' => '2001-04-20',
        ]);
    }

    public function test_user_can_login_with_email_or_mobile_and_password(): void
    {
        $user = User::create([
            'full_name' => 'Student Four',
            'mobile' => '9876543213',
            'email' => 'student4@example.com',
            'dob' => '2002-05-21',
            'password' => 'secret123',
        ]);

        $emailLoginResponse = $this->post('/auth/login-password', [
            'mobile_or_email' => $user->email,
            'password' => 'secret123',
        ]);

        $emailLoginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        auth()->logout();

        $mobileLoginResponse = $this->post('/auth/login-password', [
            'mobile_or_email' => $user->mobile,
            'password' => 'secret123',
        ]);

        $mobileLoginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
