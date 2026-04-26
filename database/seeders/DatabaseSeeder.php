<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Exam;
use App\Models\FormField;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['mobile' => '9999999999'],
            [
                'name' => 'Portal Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        $student = User::updateOrCreate(
            ['mobile' => '8888888888'],
            [
                'name' => 'Demo Student',
                'email' => 'student@example.com',
                'password' => Hash::make('student123'),
                'date_of_birth' => '2004-01-15',
            ]
        );

        $ssc = Exam::updateOrCreate(
            ['title' => 'SSC CGL 2026'],
            [
                'description' => 'Staff Selection Commission - Combined Graduate Level Recruitment.',
                'category' => 'Govt',
                'detail_label' => 'Vacancies',
                'detail_value' => '7,500+',
                'fee' => 500,
                'last_date' => now()->addWeeks(4)->toDateString(),
                'status' => 'active',
            ]
        );

        Exam::updateOrCreate(
            ['title' => 'NEET UG 2026'],
            [
                'description' => 'National Eligibility cum Entrance Test for Medical Courses.',
                'category' => 'Entrance',
                'detail_label' => 'Level',
                'detail_value' => 'All India',
                'fee' => 1200,
                'last_date' => now()->addDays(2)->toDateString(),
                'status' => 'active',
            ]
        );

        Exam::updateOrCreate(
            ['title' => 'JEE Mains 2026'],
            [
                'description' => 'Joint Entrance Examination for Engineering Admissions.',
                'category' => 'Tech',
                'detail_label' => 'Sessions',
                'detail_value' => '02 Sessions',
                'fee' => 1000,
                'last_date' => now()->addWeeks(6)->toDateString(),
                'status' => 'active',
            ]
        );

        foreach ([
            $ssc->id => [
                ['label' => 'Full Name', 'name' => 'full_name', 'type' => 'text', 'is_required' => true, 'sort_order' => 1],
                ['label' => 'Date of Birth', 'name' => 'dob', 'type' => 'date', 'is_required' => true, 'sort_order' => 2],
                ['label' => 'Gender', 'name' => 'gender', 'type' => 'select', 'options' => ['Male', 'Female', 'Other'], 'is_required' => true, 'sort_order' => 3],
                ['label' => 'Mobile Number', 'name' => 'mobile', 'type' => 'text', 'is_required' => true, 'sort_order' => 4],
                ['label' => 'Email Address', 'name' => 'email', 'type' => 'text', 'is_required' => false, 'sort_order' => 5],
                ['label' => 'Address', 'name' => 'address', 'type' => 'textarea', 'is_required' => true, 'sort_order' => 6],
                ['label' => 'Photo', 'name' => 'photo', 'type' => 'file', 'is_required' => true, 'sort_order' => 7],
                ['label' => 'Signature', 'name' => 'signature', 'type' => 'file', 'is_required' => true, 'sort_order' => 8],
            ],
        ] as $examId => $fields) {
            foreach ($fields as $field) {
                FormField::updateOrCreate(
                    ['exam_id' => $examId, 'name' => $field['name']],
                    $field + ['exam_id' => $examId]
                );
            }
        }

        Application::updateOrCreate(
            ['user_id' => $student->id, 'exam_id' => $ssc->id],
            [
                'form_data' => [
                    'full_name' => 'Demo Student',
                    'dob' => '2004-01-15',
                    'gender' => 'Male',
                    'mobile' => '8888888888',
                    'email' => 'student@example.com',
                    'address' => 'Delhi, India',
                ],
                'status' => 'approved',
                'submitted_at' => now(),
            ]
        );

        Payment::updateOrCreate(
            ['user_id' => $student->id, 'exam_id' => $ssc->id],
            [
                'amount' => 500,
                'status' => 'paid',
                'transaction_id' => 'DEMO-PAY-2026',
                'paid_at' => now(),
            ]
        );
    }
}
