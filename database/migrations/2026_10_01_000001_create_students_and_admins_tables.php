<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->unique();      // e.g. STU-2026-K7QP
            $table->string('name');
            $table->date('dob');
            $table->string('class_grade', 50);
            $table->string('school_name');
            $table->text('address');
            $table->string('parent_name');
            $table->string('parent_wa', 20);
            $table->string('parent_email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            $table->index(['class_grade', 'school_name']);
            $table->index('created_at');
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('students');
    }
};
