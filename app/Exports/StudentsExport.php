<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private array $filters = [])
    {
    }

    public function query()
    {
        return Student::query()->filter($this->filters)->latest();
    }

    public function headings(): array
    {
        return ['Student ID', 'Name', 'Date of birth', 'Class', 'School', 'Address',
            'Parent name', 'Parent WhatsApp', 'Parent email', 'Email verified', 'Registered at'];
    }

    public function map($s): array
    {
        return [
            $s->student_id, $s->name, $s->dob->format('Y-m-d'), $s->class_grade, $s->school_name,
            $s->address, $s->parent_name, $s->parent_wa, $s->parent_email,
            $s->email_verified_at ? 'Yes' : 'No', $s->created_at->format('Y-m-d H:i'),
        ];
    }
}
