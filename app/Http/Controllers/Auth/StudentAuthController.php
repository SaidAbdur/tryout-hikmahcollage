<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.registration');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'region' => ['required', 'string', 'max:255'],
            'school' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'in:Kelas 10,Kelas 11,Kelas 12'],
            'dob' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:30'],
            'parent_email' => ['required', 'email', 'max:255'],
            'package_type' => ['required', 'in:free,paid'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        do {
            $studentId = 'STU'.now()->format('Y').Str::upper(Str::random(6));
        } while (Student::where('student_id', $studentId)->exists());

        $student = Student::create([
            ...$data,
            'student_id' => $studentId,
            'age' => Carbon::parse($data['dob'])->age,
            'password' => Hash::make($data['password']),
            'account_status' => 'pending',
        ]);

        $request->session()->put('registration_student_id', $student->id);

        return redirect()->route('registration.proofs');
    }

    public function showProofUpload(Request $request)
    {
        $student = $this->registrationStudent($request);

        return view('auth.proofs', compact('student'));
    }

    public function uploadProofs(Request $request): RedirectResponse
    {
        $student = $this->registrationStudent($request);
        $count = $student->package_type === 'free' ? 5 : 1;
        $data = $request->validate([
            'proofs' => ['required', 'array', 'size:'.$count],
            'proofs.*' => ['required', 'image', 'max:5120'],
        ]);

        $paths = collect($data['proofs'])
            ->map(fn ($file) => $file->store('proofs', 'local'))
            ->all();

        $student->update(['proof_files' => $paths]);
        Auth::guard('student')->login($student);
        $request->session()->regenerate();
        $request->session()->forget('registration_student_id');

        return redirect()->route('registration.waiting');
    }

    public function showWaiting(Request $request)
    {
        $student = Auth::guard('student')->user();

        if ($student->account_status === 'approved') {
            return redirect()->route('dashboard');
        }

        if ($student->account_status === 'rejected') {
            Auth::guard('student')->logout();

            return redirect()->route('login')->withErrors(['login' => 'Pendaftaran Anda belum disetujui. Hubungi Admin untuk informasi lebih lanjut.']);
        }

        return view('auth.waiting', compact('student'));
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim($credentials['login']);
        $student = Student::where('student_id', Str::upper($identifier))
            ->orWhereRaw('LOWER(parent_email) = ?', [Str::lower($identifier)])
            ->get()
            ->first(fn (Student $candidate) => Hash::check($credentials['password'], $candidate->password));

        if (! $student) {
            return back()->withErrors(['login' => 'Student ID/email atau password salah.'])->onlyInput('login');
        }

        if ($student->account_status === 'pending') {
            return back()->withErrors(['login' => 'Akun Anda sedang dalam proses verifikasi oleh Admin.'])->onlyInput('login');
        }

        if ($student->account_status !== 'approved') {
            return back()->withErrors(['login' => 'Akun Anda belum disetujui Admin.'])->onlyInput('login');
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

    private function registrationStudent(Request $request): Student
    {
        $student = Student::whereKey($request->session()->get('registration_student_id'))->first();

        abort_unless($student && $student->account_status === 'pending', 404);

        return $student;
    }
}
