<!DOCTYPE html>
<html lang="id">
<body style="margin:0;background:#f5f3ff;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<div style="max-width:480px;margin:0 auto;padding:24px;">
    <div style="background:#fff;border-radius:24px;padding:32px;text-align:center;">
        <div style="font-size:40px;">🎉</div>
        <h1 style="margin:8px 0;font-size:22px;color:#1e293b;">Pendaftaran {{ $student->name }} berhasil</h1>
        <p style="color:#64748b;">Gunakan Student ID ini untuk masuk ke TryoutKu:</p>
        <p style="display:inline-block;margin:8px 0 20px;padding:14px 22px;border-radius:16px;background:#ede9fe;color:#6d28d9;font-size:26px;font-weight:bold;letter-spacing:2px;">{{ $student->student_id }}</p>
        <p><a href="{{ $verifyUrl }}" style="display:inline-block;padding:14px 24px;border-radius:16px;background:#8b5cf6;color:#fff;font-weight:bold;text-decoration:none;">Verifikasi email</a></p>
        <p style="font-size:12px;color:#94a3b8;">Tautan verifikasi berlaku 3 hari. Abaikan email ini jika kamu tidak mendaftar.</p>
    </div>
</div>
</body>
</html>
