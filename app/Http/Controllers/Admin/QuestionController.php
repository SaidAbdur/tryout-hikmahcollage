<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\TryoutSeries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $series = TryoutSeries::withCount('questions')->orderByDesc('id')->get();

        return view('admin.questions.index', compact('series'));
    }

    public function createSeries()
    {
        return view('admin.questions.series-form', ['series' => new TryoutSeries()]);
    }

    public function storeSeries(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tryout_series,name'],
            'type' => ['required', 'in:wajib,pilihan'],
            'status' => ['required', 'in:active,draft'],
        ]);

        $series = TryoutSeries::create($data);

        return redirect()->route('admin.questions.series', $series)->with('status', 'Tryout series berhasil dibuat.');
    }

    public function showSeries(TryoutSeries $tryoutSeries)
    {
        $subjectType = $tryoutSeries->type === 'wajib' ? 'mandatory' : 'elective';
        $subjects = Subject::where('type', $subjectType)
            ->withCount(['questions' => fn ($query) => $query->where('tryout_series_id', $tryoutSeries->id)])
            ->orderBy('name')
            ->get();

        return view('admin.questions.series', compact('tryoutSeries', 'subjects'));
    }

    public function showSubject(TryoutSeries $tryoutSeries, Subject $subject)
    {
        $this->assertSubjectBelongsToSeriesType($tryoutSeries, $subject);
        $questions = Question::where('tryout_series_id', $tryoutSeries->id)
            ->where('subject_id', $subject->id)
            ->orderBy('id')
            ->paginate(15);

        return view('admin.questions.subject', compact('tryoutSeries', 'subject', 'questions'));
    }

    public function create(TryoutSeries $tryoutSeries, Subject $subject)
    {
        $this->assertSubjectBelongsToSeriesType($tryoutSeries, $subject);

        return view('admin.questions.form-page', [
            'tryoutSeries' => $tryoutSeries,
            'subject' => $subject,
            'question' => new Question(),
        ]);
    }

    public function store(Request $request, TryoutSeries $tryoutSeries, Subject $subject): RedirectResponse
    {
        $this->assertSubjectBelongsToSeriesType($tryoutSeries, $subject);
        $question = Question::create($this->validated($request) + [
            'tryout_series_id' => $tryoutSeries->id,
            'subject_id' => $subject->id,
        ]);

        return redirect()->route('admin.questions.subject', [$tryoutSeries, $subject])->with('status', 'Soal berhasil ditambahkan.');
    }

    public function edit(TryoutSeries $tryoutSeries, Subject $subject, Question $question)
    {
        $this->assertQuestionContext($tryoutSeries, $subject, $question);

        return view('admin.questions.form-page', compact('tryoutSeries', 'subject', 'question'));
    }

    public function update(Request $request, TryoutSeries $tryoutSeries, Subject $subject, Question $question): RedirectResponse
    {
        $this->assertQuestionContext($tryoutSeries, $subject, $question);
        $question->update($this->validated($request));

        return redirect()->route('admin.questions.subject', [$tryoutSeries, $subject])->with('status', 'Soal berhasil diperbarui.');
    }

    public function destroy(TryoutSeries $tryoutSeries, Subject $subject, Question $question): RedirectResponse
    {
        $this->assertQuestionContext($tryoutSeries, $subject, $question);
        $question->delete();

        return redirect()->route('admin.questions.subject', [$tryoutSeries, $subject])->with('status', 'Soal berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'option_e' => ['required', 'string'],
            'correct_option' => ['required', 'in:A,B,C,D,E'],
            'explanation_text' => ['nullable', 'string'],
        ]);
    }

    private function assertSubjectBelongsToSeriesType(TryoutSeries $tryoutSeries, Subject $subject): void
    {
        $expectedSubjectType = $tryoutSeries->type === 'wajib' ? 'mandatory' : 'elective';
        abort_unless($subject->type === $expectedSubjectType, 404);
    }

    private function assertQuestionContext(TryoutSeries $tryoutSeries, Subject $subject, Question $question): void
    {
        $this->assertSubjectBelongsToSeriesType($tryoutSeries, $subject);
        abort_unless(
            $question->tryout_series_id === $tryoutSeries->id && $question->subject_id === $subject->id,
            404,
        );
    }
}