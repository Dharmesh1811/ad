<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->load(['applications.exam', 'payments.exam']);

        return view('dashboard', [
            'user' => $user,
            'exams' => Exam::where('status', 'active')->orderBy('last_date')->get(),
            'applications' => $user->applications->sortByDesc('created_at')->values(),
        ]);
    }
}
