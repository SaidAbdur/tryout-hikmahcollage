<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();      // 0-100
            $table->unsignedSmallInteger('total_correct')->default(0);
            $table->unsignedSmallInteger('total_wrong')->default(0);
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamps();

            $table->index(['student_id', 'subject_id', 'status']);
            $table->index('status');
        });

        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->char('selected_option', 1)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->boolean('is_flagged')->default(false);   // "ragu-ragu" marker for the navigator
            $table->timestamps();

            $table->unique(['tryout_session_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
        Schema::dropIfExists('tryout_sessions');
    }
};
