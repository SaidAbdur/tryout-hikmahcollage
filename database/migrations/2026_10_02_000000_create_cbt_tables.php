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
            $table->string('student_id')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('phone');
            $table->string('region');
            $table->string('school');
            $table->string('grade_level');
            $table->date('dob');
            $table->integer('age');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('parent_email')->nullable();
            $table->enum('package_type', ['free', 'paid'])->default('free');
            $table->json('proof_files')->nullable();
            $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // mandatory/elective
            $table->integer('duration_minutes');
            $table->dateTime('live_discussion_schedule')->nullable();
            $table->timestamps();
        });

        Schema::create('tryout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->integer('score')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->string('status')->default('pending'); // pending, ongoing, completed
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->text('question_text');
            $table->text('option_a')->nullable();
            $table->text('option_b')->nullable();
            $table->text('option_c')->nullable();
            $table->text('option_d')->nullable();
            $table->text('option_e')->nullable();
            $table->char('correct_option', 1);
            $table->text('explanation_text')->nullable();
            $table->timestamps();
        });

        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_session_id')->constrained('tryout_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->char('answered_option', 1)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('tryout_sessions');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('students');
    }
};
