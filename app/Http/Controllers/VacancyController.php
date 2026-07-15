<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Contracts\View\View;

class VacancyController extends Controller
{
    public function index(): View
    {
        return view('vacancies.index', [
            'vacancies' => Exam::where('status', 'active')
                ->where('module_type', 'vacancy')
                ->with('formFields')
                ->orderBy('last_date')
                ->get(),
        ]);
    }
}
