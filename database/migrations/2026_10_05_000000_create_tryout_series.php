<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_series', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type');
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        $hasQuestions = DB::table('questions')->exists();
        $hasSessions = DB::table('tryout_sessions')->exists();
        $legacyMappings = [];

        foreach (DB::table('subjects')->select('type')->distinct()->pluck('type') as $subjectType) {
            $subjectIds = DB::table('subjects')->where('type', $subjectType)->pluck('id');
            $hasQuestionsForType = DB::table('questions')->whereIn('subject_id', $subjectIds)->exists();
            $hasSessionsForType = DB::table('tryout_sessions')->whereIn('subject_id', $subjectIds)->exists();

            if (! $hasQuestionsForType && ! $hasSessionsForType) {
                continue;
            }

            $seriesType = $subjectType === 'elective' ? 'pilihan' : 'wajib';
            $legacySeriesId = DB::table('tryout_series')->insertGetId([
                'name' => 'Legacy '.ucfirst($seriesType).' Series',
                'type' => $seriesType,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $legacyMappings[] = ['subject_ids' => $subjectIds, 'series_id' => $legacySeriesId];
        }

        Schema::table('questions', function (Blueprint $table) use ($hasQuestions) {
            $table->foreignId('tryout_series_id')
                ->nullable($hasQuestions)
                ->constrained('tryout_series')
                ->cascadeOnDelete();
        });

        Schema::table('tryout_sessions', function (Blueprint $table) use ($hasSessions) {
            $table->foreignId('tryout_series_id')
                ->nullable($hasSessions)
                ->constrained('tryout_series')
                ->restrictOnDelete();
        });

        foreach ($legacyMappings as $mapping) {
            DB::table('questions')->whereIn('subject_id', $mapping['subject_ids'])->whereNull('tryout_series_id')
                ->update(['tryout_series_id' => $mapping['series_id']]);
            DB::table('tryout_sessions')->whereIn('subject_id', $mapping['subject_ids'])->whereNull('tryout_series_id')
                ->update(['tryout_series_id' => $mapping['series_id']]);
        }

        if ($hasQuestions) {
            Schema::table('questions', function (Blueprint $table) {
                $table->foreignId('tryout_series_id')->nullable(false)->change();
            });
        }

        if ($hasSessions) {
            Schema::table('tryout_sessions', function (Blueprint $table) {
                $table->foreignId('tryout_series_id')->nullable(false)->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('tryout_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tryout_series_id');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tryout_series_id');
        });

        Schema::dropIfExists('tryout_series');
    }
};