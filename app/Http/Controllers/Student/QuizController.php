<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizSubmissionLog;
use App\Models\StudentAnswer;
use App\Models\Student;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Show available quizzes for student.
     */
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->first();
        $now = now();
        
        // Available quizzes (active, not started or ongoing)
        $availableQuizzes = Quiz::where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->whereDoesntHave('attempts', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->get();

        // Past quizzes (attempted or expired)
        $pastQuizzes = Quiz::where(function($query) use ($now, $student) {
                $query->where('end_date', '<', $now)
                    ->orWhereHas('attempts', function($q) use ($student) {
                        $q->where('student_id', $student->id);
                    });
            })
            ->where('is_active', true)
            ->get();

        return view('student.quizzes.index', compact('availableQuizzes', 'pastQuizzes'));
    }

    /**
     * Start a quiz.
     */
    public function start($id)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $quiz = Quiz::with('questions.options')
            ->where('is_active', true)
            ->findOrFail($id);

        // Check if quiz is available
        if (!$this->isQuizAvailable($quiz)) {
            return redirect()
                ->route('student.quizzes.index')
                ->with('error', 'This quiz is not available.');
        }

        // Check if student already attempted
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->whereIn('status', ['in_progress', 'submitted'])
            ->first();

        if ($existingAttempt && $existingAttempt->status === 'submitted') {
            return redirect()
                ->route('student.quizzes.index')
                ->with('error', 'You have already submitted this quiz.');
        }

        // Create new attempt or use existing in-progress
        if (!$existingAttempt) {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'start_time' => now(),
                'status' => 'in_progress',
            ]);
        } else {
            $attempt = $existingAttempt;
            
            // Check if time is up
            if (!$this->isWithinTime($attempt)) {
                return $this->autoSubmitQuiz($attempt);
            }
        }

        // Get questions with student answers if any
        $questions = $quiz->questions()
            ->with(['options', 'studentAnswers' => function($query) use ($attempt) {
                $query->where('attempt_id', $attempt->id);
            }])
            ->get();

        // Calculate remaining time
        $timeElapsed = now()->diffInSeconds($attempt->start_time);
        $totalSeconds = $quiz->duration_minutes * 60;
        $remainingSeconds = max(0, $totalSeconds - $timeElapsed);

        return view('student.quizzes.attempt', compact('quiz', 'attempt', 'questions', 'remainingSeconds'));
    }

    /**
     * Save answer (AJAX).
     */
    public function saveAnswer(Request $request, $attemptId)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $attempt = QuizAttempt::where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->findOrFail($attemptId);

        // Check time
        if (!$this->isWithinTime($attempt)) {
            return response()->json([
                'error' => 'Time is up! Quiz will be submitted automatically.'
            ], 403);
        }

        $question = Question::findOrFail($request->question_id);

        DB::transaction(function() use ($request, $attempt, $question) {
            // Find or create student answer
            $studentAnswer = StudentAnswer::updateOrCreate(
                [
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_option_id' => $request->selected_option_id,
                    'answer_text' => $request->answer_text,
                ]
            );

            // Check if correct
            if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false') {
                $option = QuestionOption::find($request->selected_option_id);
                $studentAnswer->is_correct = $option ? $option->is_correct : false;
            }
            
            $studentAnswer->save();
        });

        return response()->json(['success' => 'Answer saved!']);
    }

    /**
     * Submit quiz manually.
     */
    public function submitQuiz(Request $request, $attemptId)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $attempt = QuizAttempt::where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->findOrFail($attemptId);

        return $this->autoSubmitQuiz($attempt);
    }

    /**
     * Auto-submit quiz (when time expires or manually).
     */
    private function autoSubmitQuiz($attempt)
    {
        DB::transaction(function() use ($attempt) {
            // Calculate score
            $correctAnswers = StudentAnswer::where('attempt_id', $attempt->id)
                ->where('is_correct', true)
                ->count();

            $totalQuestions = $attempt->quiz->questions->count();
            $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

            $answeredQuestions = StudentAnswer::where('attempt_id', $attempt->id)->count();

            // Update attempt
            $attempt->update([
                'end_time' => now(),
                'score' => $score,
                'status' => 'submitted',
            ]);

            // Log the submission
            QuizSubmissionLog::create([
                'attempt_id' => $attempt->id,
                'quiz_id' => $attempt->quiz_id,
                'student_id' => $attempt->student_id,
                'submission_type' => 'auto_time_expired',
                'score_before_submission' => $score,
                'answered_questions' => $answeredQuestions,
                'total_questions' => $totalQuestions,
                'submitted_at' => now(),
                'notes' => 'Auto-submitted due to time expiration',
            ]);
        });

        return redirect()
            ->route('student.quizzes.results', $attempt->id)
            ->with('info', 'Quiz time expired! Auto-submitted.');
    }

    /**
     * View quiz results.
     */
    public function results($attemptId)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $attempt = QuizAttempt::with(['quiz', 'quiz.questions', 'answers'])
            ->where('student_id', $student->id)
            ->findOrFail($attemptId);

        $correctAnswers = $attempt->answers()->where('is_correct', true)->count();
        $totalQuestions = $attempt->quiz->questions->count();

        return view('student.quizzes.results', compact('attempt', 'correctAnswers', 'totalQuestions'));
    }

    /**
     * Check if quiz is available.
     */
    private function isQuizAvailable($quiz)
    {
        $now = now();
        return $quiz->is_active && 
               $now->between($quiz->start_date, $quiz->end_date);
    }

    /**
     * Check if attempt is still within time.
     */
    private function isWithinTime($attempt)
    {
        if ($attempt->status === 'submitted') {
            return false;
        }
        
        $timeElapsed = now()->diffInMinutes($attempt->start_time);
        return $timeElapsed < $attempt->quiz->duration_minutes;
    }
}