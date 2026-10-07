<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    public function index()
    {
        $students = Student::where('account_status', 'pending')->latest()->paginate(20);

        return view('admin.students.pending', compact('students'));
    }

    public function updateStatus(Request $request, Student $student)
    {
        $data = $request->validate(['account_status' => ['required', 'in:approved,rejected']]);
        abort_unless($student->account_status === 'pending', 409);

        if ($data['account_status'] === 'approved') {
            $requiredProofs = $student->package_type === 'free' ? 5 : 1;
            abort_unless(count($student->proof_files ?? []) === $requiredProofs, 422);
        }

        $student->update(['account_status' => $data['account_status']]);

        return redirect()->route('admin.students.pending')->with('status', 'Status pendaftaran berhasil diperbarui.');
    }

    public function proof(Student $student, int $proof)
    {
        $path = $student->proof_files[$proof] ?? null;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path));
    }
}