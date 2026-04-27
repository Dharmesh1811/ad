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
        
        $search = $request->query('search');
        $statusFilter = $request->query('status', 'active'); // Default to active

        $query = Exam::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($statusFilter === 'active') {
            $query->where('status', 'active')
                  ->where('last_date', '>=', now()->toDateString());
        } elseif ($statusFilter === 'closed') {
            $query->where(function($q) {
                $q->where('status', 'inactive')
                  ->orWhere('last_date', '<', now()->toDateString());
            });
        }

        return view('dashboard', [
            'user' => $user,
            'exams' => $query->orderBy('last_date')->get(),
            'applications' => $user->applications->sortByDesc('created_at')->values(),
            'filters' => [
                'search' => $search,
                'status' => $statusFilter
            ]
        ]);
    }
}
