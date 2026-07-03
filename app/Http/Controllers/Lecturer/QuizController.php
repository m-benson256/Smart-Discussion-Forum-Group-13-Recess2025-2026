<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Category;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a list of quizzes.
     */
    public function index()
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();
        $quizzes = Quiz::where('lecturer_id', $lecturer->id)
            ->withCount('questions', 'attempts')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('lecturer.quizzes.index', compact('quizzes'));
    }

    /**
     * Show form to create a new quiz.
     */
    public function create()
    {
        $categories = Category::all();
        return view('lecturer.quizzes.create', compact('categories'));
    }

    /**
     * Store a new quiz.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'duration_minutes' => 'required|integer|min:1|max:180',
        ]);

        $lecturer = Lecturer::where('user_id', Auth::id())->first();

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'lecturer_id' => $lecturer->id,
            'category_id' => $request->category_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => true,
        ]);

        return redirect()
            ->route('lecturer.quizzes.edit', $quiz->id)
            ->with('success', 'Quiz created! Now add questions.');
    }

    /**
     * Show form to edit quiz and add questions.
     */
    public function edit($id)
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();
        $quiz = Quiz::with('questions.options')
            ->where('lecturer_id', $lecturer->id)
            ->findOrFail($id);
        
        $categories = Category::all();
        
        return view('lecturer.quizzes.edit', compact('quiz', 'categories'));
    }

    /**
     * Update quiz details.
     */
    public function update(Request $request, $id)
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();
        $quiz = Quiz::where('lecturer_id', $lecturer->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'duration_minutes' => 'required|integer|min:1|max:180',
        ]);

        $quiz->update($request->all());

        return redirect()
            ->route('lecturer.quizzes.edit', $quiz->id)
            ->with('success', 'Quiz updated successfully!');
    }

    /**
     * Add question to quiz.
     */
    public function addQuestion(Request $request, $id)
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();
        $quiz = Quiz::where('lecturer_id', $lecturer->id)->findOrFail($id);

        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer|min:0',
            'correct_answer' => 'required_if:question_type,true_false|in:true,false',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'points' => $request->points,
        ]);

        // Handle multiple choice options
        if ($request->question_type === 'multiple_choice') {
            foreach ($request->options as $index => $optionText) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct' => $index == $request->correct_option,
                ]);
            }
        }

        // Handle true/false
        if ($request->question_type === 'true_false') {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => 'True',
                'is_correct' => $request->correct_answer === 'true',
            ]);
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => 'False',
                'is_correct' => $request->correct_answer === 'false',
            ]);
        }

        return redirect()
            ->route('lecturer.quizzes.edit', $quiz->id)
            ->with('success', 'Question added successfully!');
    }

    /**
     * Delete a question.
     */
    public function deleteQuestion($quizId, $questionId)
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();
        $quiz = Quiz::where('lecturer_id', $lecturer->id)->findOrFail($quizId);
        
        $question = Question::where('quiz_id', $quiz->id)
            ->findOrFail($questionId);
        
        $question->delete();

        return redirect()
            ->route('lecturer.quizzes.edit', $quiz->id)
            ->with('success', 'Question deleted!');
    }

    /**
     * View quiz results.
     */
    public function results($id)
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();
        $quiz = Quiz::with(['attempts.student', 'questions'])
            ->where('lecturer_id', $lecturer->id)
            ->findOrFail($id);

        $attempts = $quiz->attempts()
            ->with('student')
            ->where('status', 'submitted')
            ->get();

        $stats = [
            'total_attempts' => $attempts->count(),
            'average_score' => $attempts->avg('score') ?? 0,
            'highest_score' => $attempts->max('score') ?? 0,
            'lowest_score' => $attempts->min('score') ?? 0,
        ];

        return view('lecturer.quizzes.results', compact('quiz', 'attempts', 'stats'));
    }

    /**
     * Delete quiz.
     */
    public function destroy($id)
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();
        $quiz = Quiz::where('lecturer_id', $lecturer->id)->findOrFail($id);
        
        $quiz->delete();

        return redirect()
            ->route('lecturer.quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }
}