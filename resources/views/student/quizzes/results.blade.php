<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h2>{{ $attempt->quiz->title }} - Results</h2>
                    </div>
                    <div class="card-body text-center">
                        <div class="display-1 mb-4">
                            {{ round($attempt->score, 1) }}%
                        </div>
                        <div class="progress mb-4" style="height: 30px;">
                            <div class="progress-bar 
                                @if($attempt->score >= 70) bg-success
                                @elseif($attempt->score >= 50) bg-warning
                                @else bg-danger @endif"
                                role="progressbar"
                                style="width: {{ $attempt->score }}%"
                                aria-valuenow="{{ $attempt->score }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                                {{ round($attempt->score, 1) }}%
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>Correct Answers</h5>
                                        <h2 class="text-success">{{ $correctAnswers }}/{{ $totalQuestions }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>Time Spent</h5>
                                        <h2>
                                            @php
                                                $timeSpent = $attempt->created_at->diffInMinutes($attempt->end_time ?? now());
                                            @endphp
                                            {{ $timeSpent }} minutes
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('student.quizzes.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Quizzes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>