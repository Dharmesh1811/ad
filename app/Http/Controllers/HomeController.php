<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('index', [
            'exams' => Exam::where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('module_type')->orWhere('module_type', 'exam');
                })
                ->orderBy('last_date')
                ->get(),
            'vacancies' => Exam::where('status', 'active')
                ->where('module_type', 'vacancy')
                ->orderBy('last_date')
                ->get(),
        ]);
    }
}
