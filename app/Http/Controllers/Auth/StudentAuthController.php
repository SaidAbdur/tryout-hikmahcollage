<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\StudentRegistered;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate(Student::rules());

        $student = Student::create($data + ['student_id' => Student::generateStudentId()]);

        // Parent email is optional, so the verification mail only goes out when it was given.
        if ($student->parent_email) {
            try {
                Mail::to($student->parent_email)->send(new StudentRegistered($student));
            } catch (\Throwable $e) {
                report($e); // a mail outage must not block registration
            }
        }

        return redirect()->route('login')->with('registered', $student->student_id);
    }

    public function verify(Student $student): RedirectResponse
    {
        $student->forceFill(['email_verified_at' => now()])->save();

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Selamat belajar! 🎉');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate(['student_id' => ['required', 'string', 'max:20']]);

        $student = Student::where('student_id', Str::upper(trim($request->student_id)))->first();

        if (! $student) {
            return back()->withErrors(['student_id' => 'Student ID tidak ditemukan. Cek lagi ya!'])->onlyInput('student_id');
        }

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();
        $request->session()->regenerateToken(); // no invalidate(): an admin may be signed in on the same browser

        return redirect()->route('home');
    }
}
