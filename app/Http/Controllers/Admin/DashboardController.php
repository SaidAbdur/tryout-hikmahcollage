<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TryoutSession;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => Student::count(),
            'pending' => Student::where('account_status', 'pending')->count(),
            'approved' => Student::where('account_status', 'approved')->count(),
            'subjects' => Subject::count(),
            'completed' => TryoutSession::where('status', 'completed')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
