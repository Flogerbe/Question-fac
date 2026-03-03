<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('answers')->orderBy('ordre')->get();
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:1000',
            'coach_hint' => 'nullable|string|max:255',
            'ordre' => 'required|integer|min:1|max:20',
            'answers' => 'required|array|size:4',
            'answers.*.reponse' => 'required|string|max:255',
            'answers.*.is_correct' => 'sometimes|boolean',
            'correct_answer' => 'required|integer|between:0,3',
        ]);

        $question = Question::create([
            'question' => $data['question'],
            'coach_hint' => $data['coach_hint'] ?? null,
            'ordre' => $data['ordre'],
        ]);

        foreach ($data['answers'] as $index => $answerData) {
            Answer::create([
                'question_id' => $question->id,
                'reponse' => $answerData['reponse'],
                'is_correct' => ($index == $data['correct_answer']),
            ]);
        }

        return redirect()->route('admin.questions.index')->with('success', 'Question créée avec succès.');
    }

    public function edit(Question $question)
    {
        $question->load('answers');
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'question' => 'required|string|max:1000',
            'coach_hint' => 'nullable|string|max:255',
            'ordre' => 'required|integer|min:1|max:20',
            'answers' => 'required|array|size:4',
            'answers.*.reponse' => 'required|string|max:255',
            'correct_answer' => 'required|integer|between:0,3',
        ]);

        $question->update([
            'question' => $data['question'],
            'coach_hint' => $data['coach_hint'] ?? null,
            'ordre' => $data['ordre'],
        ]);

        $answers = $question->answers()->orderBy('id')->get();
        foreach ($data['answers'] as $index => $answerData) {
            if (isset($answers[$index])) {
                $answers[$index]->update([
                    'reponse' => $answerData['reponse'],
                    'is_correct' => ($index == $data['correct_answer']),
                ]);
            }
        }

        return redirect()->route('admin.questions.index')->with('success', 'Question mise à jour.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Question supprimée.');
    }

    public function toggle(Question $question)
    {
        $question->update(['is_active' => !$question->is_active]);
        return back()->with('success', 'Statut de la question modifié.');
    }

    public function show(string $id) {}
}
