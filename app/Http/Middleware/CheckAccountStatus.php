<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next)
    {
        $student = $request->user('student');

        if ($student->account_status === 'pending') {
            return redirect()->route('registration.waiting');
        }

        if ($student->account_status !== 'approved') {
            Auth::guard('student')->logout();

            return redirect()->route('login')->withErrors([
                'login' => 'Pendaftaran Anda belum disetujui. Hubungi Admin untuk informasi lebih lanjut.',
            ]);
        }

        return $next($request);
    }
}