<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;

class StudentRegistered extends Mailable
{
    public string $verifyUrl;

    public function __construct(public Student $student)
    {
        $this->verifyUrl = URL::temporarySignedRoute('student.verify', now()->addDays(3), ['student' => $student->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Student ID TryoutKu: '.$this->student->student_id);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.student-registered');
    }
}
