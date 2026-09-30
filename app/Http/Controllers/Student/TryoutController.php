<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\Subject;
use App\Models\TryoutSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TryoutController extends Controller
{
    public function start(Subject $subject): RedirectResponse
    {
        if ($subject->questions()->doesntExist()) {
            return back()->with('error', 'Soal untuk mapel ini belum tersedia.');
        }

        $student = Auth::guard('student')->user();

        $session = TryoutSession::with('subject')
            ->where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->where('status', 'in_progress')
            ->first();

        if ($session?->isExpired()) {
            $session->finalize();
            $session = null;
        }

        // Resume an unfinished attempt instead of starting a second clock.
        $session ??= TryoutSession::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('tryout.show', $session);
    }

    public function show(TryoutSession $session)
    {
        $this->own($session);

        if ($session->status === 'completed') {
            return redirect()->route('tryout.result', $session);
        }
        if ($session->isExpired()) {
            $session->finalize();

            return redirect()->route('tryout.result', $session)->with('just_submitted', true);
        }

        $session->load('subject');

        // The correct option and explanation are deliberately NOT sent to the browser.
        $questions = $session->subject->questions()->orderBy('id')->get()->map(fn (Question $q) => [
            'id' => $q->id,
            'text' => $q->question_text,
            'options' => $q->options(),
        ])->values();

        $answers = $session->answers()->get()->mapWithKeys(fn ($a) => [
            $a->question_id => ['o' => $a->selected_option, 'f' => $a->is_flagged],
        ]);

        return view('student.exam', [
            'session' => $session,
            'questions' => $questions,
            'answers' => $answers,
            'remaining' => $session->remainingSeconds(),
        ]);
    }

    /** Called on every click by the exam page, so nothing is lost if the browser closes. */
    public function answer(Request $request, TryoutSession $session): JsonResponse
    {
        $this->own($session);

        if ($session->status !== 'in_progress' || now()->timestamp > $session->deadline()->timestamp + 5) {
            return response()->json(['expired' => true], 410); // 5s grace for network latency
        }

        $data = $request->validate([
            'question_id' => ['required', Rule::exists('questions', 'id')->where('subject_id', $session->subject_id)],
            'selected_option' => ['nullable', Rule::in(['A', 'B', 'C', 'D', 'E'])],
            'is_flagged' => ['boolean'],
        ]);

        $question = Question::findOrFail($data['question_id']);
        $picked = $data['selected_option'] ?? null;

        StudentAnswer::updateOrCreate(
            ['tryout_session_id' => $session->id, 'question_id' => $question->id],
            [
                'selected_option' => $picked,
                'is_correct' => $picked !== null && $picked === $question->correct_option,
                'is_flagged' => $request->boolean('is_flagged'),
            ],
        );

        return response()->json(['ok' => true]);
    }

    public function submit(TryoutSession $session): RedirectResponse
    {
        $this->own($session);
        $session->finalize();

        return redirect()->route('tryout.result', $session)->with('just_submitted', true);
    }

    public function result(TryoutSession $session)
    {
        $this->own($session);

        if ($session->status !== 'completed') {
            return redirect()->route('tryout.show', $session);
        }

        $session->load('subject');
        $questions = $session->subject->questions()->orderBy('id')->get();
        $answers = $session->answers()->get()->keyBy('question_id');
        $total = $questions->count();
        $unanswered = max(0, $total - $session->total_correct - $session->total_wrong);

        return view('student.result', compact('session', 'questions', 'answers', 'total', 'unanswered'));
    }

    private function own(TryoutSession $session): void
    {
        abort_if($session->student_id != Auth::guard('student')->id(), 403);
    }
}
