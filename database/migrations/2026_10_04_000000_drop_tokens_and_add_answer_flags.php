<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tokens');

        if (Schema::hasTable('student_answers') && ! Schema::hasColumn('student_answers', 'is_flagged')) {
            Schema::table('student_answers', function (Blueprint $table) {
                $table->boolean('is_flagged')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('student_answers') && Schema::hasColumn('student_answers', 'is_flagged')) {
            Schema::table('student_answers', function (Blueprint $table) {
                $table->dropColumn('is_flagged');
            });
        }

    }
};