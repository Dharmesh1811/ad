<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('index', [
            'exams' => Exam::where('status', 'active')->orderBy('last_date')->get(),
        ]);
    }
}
